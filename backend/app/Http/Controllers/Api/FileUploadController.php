<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventMedia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function uploadEventPoster(Request $request, Event $event)
    {
        $request->validate([
            'poster' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            // Delete old poster if exists
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }

            $file = $request->file('poster');
            $filename = 'event_poster_' . $event->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('events/posters', $filename, 'public');

            $event->update(['poster_path' => $path]);

            return response()->json([
                'message' => 'Poster uploaded successfully',
                'poster_url' => Storage::url($path)
            ]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function uploadEventGallery(Request $request, Event $event)
    {
        $request->validate([
            'images' => 'required|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $uploadedFiles = [];

        foreach ($request->file('images') as $file) {
            $filename = 'event_gallery_' . $event->id . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('events/gallery', $filename, 'public');

            $media = EventMedia::create([
                'event_id' => $event->id,
                'file_path' => $path,
                'type' => 'gallery'
            ]);

            $uploadedFiles[] = [
                'id' => $media->id,
                'url' => Storage::url($path)
            ];
        }

        return response()->json([
            'message' => 'Gallery images uploaded successfully',
            'files' => $uploadedFiles
        ]);
    }

    public function uploadUserAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:1024'
        ]);

        $user = $request->user();

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('avatars', $filename, 'public');

            $user->update(['avatar' => $path]);

            return response()->json([
                'message' => 'Avatar uploaded successfully',
                'avatar_url' => Storage::url($path)
            ]);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function deleteEventMedia(EventMedia $media)
    {
        Storage::disk('public')->delete($media->file_path);
        $media->delete();

        return response()->json(['message' => 'Media deleted successfully']);
    }
}