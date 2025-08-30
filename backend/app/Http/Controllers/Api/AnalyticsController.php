<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function getDashboardStats()
    {
        $stats = [
            'overview' => [
                'total_events' => Event::count(),
                'total_users' => User::count(),
                'total_registrations' => EventRegistration::count(),
                'active_events' => Event::where('status', 'approved')
                    ->where('start_at', '>', now())->count(),
            ],
            'recent_activity' => [
                'events_this_month' => Event::whereMonth('created_at', now()->month)->count(),
                'registrations_this_month' => EventRegistration::whereMonth('created_at', now()->month)->count(),
                'new_users_this_month' => User::whereMonth('created_at', now()->month)->count(),
            ],
            'user_distribution' => [
                'students' => User::where('role', 'student')->count(),
                'organizers' => User::where('role', 'organizer')->count(),
                'admins' => User::where('role', 'admin')->count(),
            ]
        ];

        return response()->json($stats);
    }

    public function getEventMetrics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = Carbon::now()->subDays($period);

        $metrics = [
            'event_performance' => Event::with(['registrations', 'feedbacks'])
                ->where('created_at', '>=', $startDate)
                ->get()
                ->map(function ($event) {
                    $avgRating = $event->feedbacks->avg('rating') ?? 0;
                    return [
                        'id' => $event->id,
                        'title' => $event->title,
                        'registrations_count' => $event->registrations->count(),
                        'attendance_rate' => $event->registrations->count() > 0 
                            ? ($event->registrations->whereNotNull('checked_in_at')->count() / $event->registrations->count()) * 100 
                            : 0,
                        'average_rating' => round($avgRating, 2),
                        'feedback_count' => $event->feedbacks->count(),
                        'capacity_utilization' => $event->capacity 
                            ? ($event->registrations->count() / $event->capacity) * 100 
                            : 0,
                    ];
                }),
            'category_stats' => Event::select('category', DB::raw('count(*) as count'))
                ->where('created_at', '>=', $startDate)
                ->groupBy('category')
                ->get(),
            'monthly_trends' => $this->getMonthlyTrends(),
        ];

        return response()->json($metrics);
    }

    public function getUserEngagement()
    {
        $engagement = [
            'registration_patterns' => EventRegistration::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as registrations')
            )
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->groupBy('date')
                ->orderBy('date')
                ->get(),
            
            'top_participants' => User::withCount('registrations')
                ->where('role', 'student')
                ->orderBy('registrations_count', 'desc')
                ->limit(10)
                ->get(['id', 'name', 'email', 'registrations_count']),
            
            'organizer_activity' => User::withCount(['events as total_events', 'events as approved_events' => function($query) {
                $query->where('status', 'approved');
            }])
                ->where('role', 'organizer')
                ->get(['id', 'name', 'email', 'total_events', 'approved_events']),
            
            'attendance_rates' => EventRegistration::select(
                'events.title',
                DB::raw('count(*) as total_registrations'),
                DB::raw('sum(case when checked_in_at is not null then 1 else 0 end) as attended')
            )
                ->join('events', 'event_registrations.event_id', '=', 'events.id')
                ->where('events.start_at', '<', now())
                ->groupBy('events.id', 'events.title')
                ->get()
                ->map(function($item) {
                    $item->attendance_rate = $item->total_registrations > 0 
                        ? ($item->attended / $item->total_registrations) * 100 
                        : 0;
                    return $item;
                }),
        ];

        return response()->json($engagement);
    }

    public function getAdvancedReports(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
        $category = $request->get('category');

        $query = Event::with(['registrations', 'feedbacks', 'organizer'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($category) {
            $query->where('category', $category);
        }

        $events = $query->get();

        $report = [
            'summary' => [
                'total_events' => $events->count(),
                'total_registrations' => $events->sum(fn($e) => $e->registrations->count()),
                'total_attendance' => $events->sum(fn($e) => $e->registrations->whereNotNull('checked_in_at')->count()),
                'average_rating' => $events->flatMap->feedbacks->avg('rating') ?? 0,
            ],
            'detailed_events' => $events->map(function($event) {
                return [
                    'id' => $event->id,
                    'title' => $event->title,
                    'organizer' => $event->organizer->name ?? 'Unknown',
                    'category' => $event->category,
                    'status' => $event->status,
                    'start_date' => $event->start_at?->format('Y-m-d H:i'),
                    'registrations' => $event->registrations->count(),
                    'attendance' => $event->registrations->whereNotNull('checked_in_at')->count(),
                    'attendance_rate' => $event->registrations->count() > 0 
                        ? round(($event->registrations->whereNotNull('checked_in_at')->count() / $event->registrations->count()) * 100, 2)
                        : 0,
                    'average_rating' => round($event->feedbacks->avg('rating') ?? 0, 2),
                    'feedback_count' => $event->feedbacks->count(),
                ];
            }),
            'charts_data' => [
                'events_by_category' => $events->groupBy('category')->map->count(),
                'events_by_status' => $events->groupBy('status')->map->count(),
                'registrations_timeline' => $this->getRegistrationsTimeline($startDate, $endDate),
            ]
        ];

        return response()->json($report);
    }

    public function exportReport(Request $request)
    {
        $format = $request->get('format', 'csv');
        $reportType = $request->get('type', 'events');

        if ($reportType === 'events') {
            $data = Event::with(['registrations', 'organizer', 'feedbacks'])
                ->get()
                ->map(function($event) {
                    return [
                        'Event ID' => $event->id,
                        'Title' => $event->title,
                        'Organizer' => $event->organizer->name ?? 'Unknown',
                        'Category' => $event->category,
                        'Status' => $event->status,
                        'Start Date' => $event->start_at?->format('Y-m-d H:i'),
                        'Registrations' => $event->registrations->count(),
                        'Attendance' => $event->registrations->whereNotNull('checked_in_at')->count(),
                        'Average Rating' => round($event->feedbacks->avg('rating') ?? 0, 2),
                    ];
                });
        } else {
            $data = EventRegistration::with(['user', 'event'])
                ->get()
                ->map(function($reg) {
                    return [
                        'Registration ID' => $reg->id,
                        'User Name' => $reg->user->name ?? 'Unknown',
                        'User Email' => $reg->user->email ?? 'Unknown',
                        'Event Title' => $reg->event->title ?? 'Unknown',
                        'Registration Date' => $reg->created_at->format('Y-m-d H:i'),
                        'Checked In' => $reg->checked_in_at ? 'Yes' : 'No',
                        'Check-in Time' => $reg->checked_in_at?->format('Y-m-d H:i') ?? 'N/A',
                    ];
                });
        }

        if ($format === 'csv') {
            $filename = $reportType . '_report_' . now()->format('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => "attachment; filename=\"$filename\"",
            ];

            $callback = function() use ($data) {
                $file = fopen('php://output', 'w');
                if (!empty($data)) {
                    fputcsv($file, array_keys($data->first()));
                    foreach ($data as $row) {
                        fputcsv($file, $row);
                    }
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }

        return response()->json($data);
    }

    private function getMonthlyTrends()
    {
        return collect(range(0, 11))->map(function($i) {
            $date = Carbon::now()->subMonths($i);
            return [
                'month' => $date->format('M Y'),
                'events' => Event::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
                'registrations' => EventRegistration::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)->count(),
            ];
        })->reverse()->values();
    }

    private function getRegistrationsTimeline($startDate, $endDate)
    {
        return EventRegistration::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as count')
        )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    }
}