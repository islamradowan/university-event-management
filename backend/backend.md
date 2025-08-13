These are my Migration files
---------------------------------------------------------------------
create_users_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('role', ['student','organizer','admin'])->default('student');
            $table->string('avatar')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
}; 

create_events_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->nullable()->unique();
            $table->text('description')->nullable();
            $table->dateTime('start_at')->nullable();
            $table->dateTime('end_at')->nullable();
            $table->string('location')->nullable();
            $table->string('category')->nullable();
            $table->enum('status', ['pending','approved','rejected'])->default('pending');
            $table->unsignedBigInteger('organizer_id')->nullable();
            $table->string('poster_path')->nullable();
            $table->integer('capacity')->nullable();
            $table->boolean('featured')->default(false);
            $table->timestamps();

            $table->foreign('organizer_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}; 

create_event_registrations_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('event_id');
            $table->uuid('qr_token')->unique();
            $table->timestamp('checked_in_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}; 

create_feedback_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('rating'); // 1-5
            $table->text('comment')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
}; 

create_event_media_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_media', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('event_id');
            $table->string('file_path');
            $table->enum('type', ['poster','gallery'])->default('gallery');
            $table->string('caption')->nullable();
            $table->timestamps();

            $table->foreign('event_id')->references('id')->on('events')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

create_notifications_table.php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('title');
            $table->text('message');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
---------------------------------------------------------------------------

These are my models files
--------------------------------------------------------------------------
 app/Models/Event.php

<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title','slug','description','start_at','end_at','location','category','status','organizer_id','poster_path','capacity','featured'];

    protected $dates = ['start_at','end_at'];

    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations() {
        return $this->hasMany(EventRegistration::class);
    }

    public function media() {
        return $this->hasMany(EventMedia::class);
    }

    public function feedbacks() {
        return $this->hasMany(Feedback::class);
    }
}

and  app/Models/EventRegistration.php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','event_id','qr_token','checked_in_at'];

    protected $dates = ['checked_in_at'];

    public function user() { return $this->belongsTo(User::class); }
    public function event() { return $this->belongsTo(Event::class); }
}

and app/Models/user.php
<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = ['name','email','password','role','avatar'];

    protected $hidden = ['password','remember_token'];

    public function events() {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function registrations() {
        return $this->hasMany(EventRegistration::class);
    }
}

app/Models/feedback.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedbacks'; // <- add this

    protected $fillable = [
        'user_id',
        'event_id',
        'rating',     // e.g., 1-5 stars
        'comment'
    ];

    // Optional: cast rating to integer
    protected $casts = [
        'rating' => 'integer',
    ];

    // Relationships
    public function user() {
        return $this->belongsTo(User::class);
    }

    public function event() {
        return $this->belongsTo(Event::class);
    }
}

app/Models/notification.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'read_at'
    ];

    protected $dates = ['read_at'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}

app/Models/eventmedia.php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'type',       // 'image', 'video', 'document'
        'path',       // storage path
        'caption'
    ];

    // Relationships
    public function event() {
        return $this->belongsTo(Event::class);
    }
}
-------------------------------------------------------------------------

These are my factory files
-------------------------------------------------------------------------
database/factories/UserFactory.php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    public function definition()
    {
        $roles = ['student','organizer','admin'];
        return [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // default password
            'role' => $this->faker->randomElement($roles),
            'avatar' => null,
            'remember_token' => Str::random(10),
        ];
    }
}

database/factories/NotificationFactory.php

<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = \App\Models\Notification::class;

    public function definition()
    {
        return [
            'user_id' => null, // assign later
            'title' => $this->faker->sentence,
            'message' => $this->faker->paragraph,
            'read_at' => $this->faker->optional()->dateTime,
        ];
    }
}

database/factories/FeedbackFactory.php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FeedbackFactory extends Factory
{
    protected $model = \App\Models\Feedback::class;

    public function definition()
    {
        return [
            'user_id' => null, // assign later
            'event_id' => null, // assign later
            'rating' => rand(1,5),
            'comment' => $this->faker->optional()->sentence,
        ];
    }
}

database/factories/EventRegistrationFactory.php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventRegistrationFactory extends Factory
{
    protected $model = \App\Models\EventRegistration::class;

    public function definition()
    {
        return [
            'user_id' => null, // assign later
            'event_id' => null, // assign later
            'qr_token' => Str::uuid(),
            'checked_in_at' => $this->faker->optional()->dateTimeBetween('-1 days','+1 days'),
        ];
    }
}

database/factories/EventMediaFactory.php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventMediaFactory extends Factory
{
    protected $model = \App\Models\EventMedia::class;

    public function definition()
    {
        return [
            'event_id' => null,
            'type' => $this->faker->randomElement(['poster','gallery']),
            'file_path' => 'images/sample.png',
            'caption' => $this->faker->optional()->sentence,
        ];
    }
}

database/factories/EventFactory.php
<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EventFactory extends Factory
{
    protected $model = \App\Models\Event::class;

    public function definition()
    {
        $title = $this->faker->sentence(3);
        $start = $this->faker->dateTimeBetween('+1 days', '+30 days');
        $end = (clone $start)->modify('+'.rand(1,4).' hours');

        return [
            'title' => $title,
            'slug' => Str::slug($title) . '-' . Str::random(5),
            'description' => $this->faker->paragraph,
            'start_at' => $start,
            'end_at' => $end,
            'location' => $this->faker->city,
            'category' => $this->faker->randomElement(['Workshop','Seminar','Competition','Festival']),
            'status' => $this->faker->randomElement(['pending','approved','rejected']),
            'organizer_id' => null, // assign later in seeder
            'poster_path' => null,
            'capacity' => rand(10,100),
            'featured' => $this->faker->boolean(20),
        ];
    }
}
-------------------------------------------------------------------------

I have seeder file
---------------------------------------------------------------------------
database/seeders/DatabaseSeeder.php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\Feedback;
use App\Models\EventMedia;
use App\Models\Notification;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1) Create users
        $admins = User::factory()->count(2)->state(['role'=>'admin'])->create();
        $organizers = User::factory()->count(5)->state(['role'=>'organizer'])->create();
        $students = User::factory()->count(50)->state(['role'=>'student'])->create();

        // 2) Create events for organizers
        $events = collect();
        foreach ($organizers as $org) {
            $ev = Event::factory()->count(3)->create(['organizer_id'=>$org->id]);
            $events = $events->merge($ev);
        }

        // 3) Assign registrations randomly
        foreach ($students as $student) {
            $eventsToJoin = $events->random(rand(1,3));
            foreach ($eventsToJoin as $event) {
                EventRegistration::factory()->create([
                    'user_id' => $student->id,
                    'event_id'=> $event->id
                ]);

                // Optional: feedback
                if(rand(0,1)) {
                    Feedback::factory()->create([
                        'user_id' => $student->id,
                        'event_id'=> $event->id
                    ]);
                }
            }
        }

        // 4) Add media for events
        foreach ($events as $event) {
            EventMedia::factory()->count(rand(1,3))->create([
                'event_id' => $event->id
            ]);
        }

        // 5) Notifications
        foreach ($students->random(10) as $student) {
            Notification::factory()->count(2)->create([
                'user_id' => $student->id
            ]);
        }
    }
}
-----------------------------------------------------------------------

these are my controlller files
------------------------------------------------------------------------
app/Http/Controllers/API/AuthController.php
<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email'],
            'password' => ['required','string','min:6'],
            'role'     => ['required', Rule::in(['student','organizer','admin'])],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Auto-login after register (optional)
        Auth::login($user);

        return response()->json([
            'message' => 'Registered',
            'user'    => $user,
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required','email'],
            'password' => ['required','string'],
        ]);

        // Sanctum SPA: ensure CSRF cookie hit on client first (/sanctum/csrf-cookie)
        if (!Auth::attempt($credentials, true)) {
            return response()->json(['message' => 'Invalid credentials'], 422);
        }

        $request->session()->regenerate();

        return response()->json([
            'message' => 'Logged in',
            'user'    => $request->user(),
        ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Logged out']);
    }

    public function me(Request $request)
    {
        return $request->user();
    }
}

app/Http/Controllers/API/EventController.php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $events = Event::with(['organizer','media','registrations'])->get();
        return response()->json($events);
    }

    public function show(Event $event)
    {
        $event->load(['organizer','media','registrations','feedbacks']);
        return response()->json($event);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'=>'required|string|max:255',
            'description'=>'nullable|string',
            'start_at'=>'required|date',
            'end_at'=>'required|date|after:start_at',
            'location'=>'nullable|string',
            'category'=>'nullable|string',
            'capacity'=>'nullable|integer',
            'featured'=>'boolean',
        ]);

        $data['organizer_id'] = $request->user()->id;
        $event = Event::create($data);
        return response()->json($event,201);
    }

    public function update(Request $request, Event $event)
    {
        $this->authorize('update',$event); // optional: use policy
        $data = $request->validate([
            'title'=>'sometimes|required|string|max:255',
            'description'=>'nullable|string',
            'start_at'=>'sometimes|date',
            'end_at'=>'sometimes|date|after:start_at',
            'location'=>'nullable|string',
            'category'=>'nullable|string',
            'capacity'=>'nullable|integer',
            'featured'=>'boolean',
            'status'=>'in:pending,approved,rejected'
        ]);

        $event->update($data);
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete',$event); // optional
        $event->delete();
        return response()->json(['message'=>'Deleted']);
    }
}

app/Http/Controllers/API/EventRegistrationController.php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class EventRegistrationController extends Controller
{
    public function register(Request $request, Event $event)
    {
        $user = $request->user();

        if(EventRegistration::where('user_id',$user->id)->where('event_id',$event->id)->exists()) {
            return response()->json(['message'=>'Already registered'],422);
        }

        $reg = EventRegistration::create([
            'user_id'=>$user->id,
            'event_id'=>$event->id,
            'qr_token'=>Str::uuid()
        ]);

        return response()->json($reg,201);
    }

    public function checkIn(Request $request, EventRegistration $registration)
    {
        $registration->checked_in_at = now();
        $registration->save();

        return response()->json($registration);
    }

    public function myRegistrations(Request $request)
    {
        $regs = $request->user()->registrations()->with('event')->get();
        return response()->json($regs);
    }
}

app/Http/Controllers/API/FeedbackController.php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\Event;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function submit(Request $request, Event $event)
    {
        $data = $request->validate([
            'rating'=>'required|integer|min:1|max:5',
            'comment'=>'nullable|string',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['event_id'] = $event->id;

        $feedback = Feedback::create($data);

        return response()->json($feedback,201);
    }

    public function list(Event $event)
    {
        $feedbacks = $event->feedbacks()->with('user')->get();
        return response()->json($feedbacks);
    }
}

app/Http/Controllers/API/NotificationController.php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifs = $request->user()->notifications()->get();
        return response()->json($notifs);
    }

    public function markRead(Notification $notification)
    {
        $notification->read_at = now();
        $notification->save();

        return response()->json($notification);
    }
}
-----------------------------------------------------------------------

This is my rourtes file 
-----------------------------------------------------------------------
routes/api.php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\EventRegistrationController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\NotificationController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
//test start
// Route::get('/user', function() {
//     return response()->json(['message' => 'API works']);
// });

// Route::get('/login', function () {
//     return 'Web login page';
// })->name('login');
// //test end


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//login, register and logout
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');
Route::get('/user', [AuthController::class,'me'])->middleware('auth:sanctum');

// Event routes (CRUD)
Route::middleware('auth:sanctum')->group(function(){
    Route::get('/events','API\EventController@index');
    Route::get('/events/{event}','API\EventController@show');
    Route::post('/events','API\EventController@store'); // organizer only (use role middleware)
    Route::put('/events/{event}','API\EventController@update');
    Route::delete('/events/{event}','API\EventController@destroy');

    Route::post('/events/{event}/register','API\EventRegistrationController@register');
    Route::post('/registrations/{registration}/checkin','API\EventRegistrationController@checkIn');
    Route::get('/my-registrations','API\EventRegistrationController@myRegistrations');

    Route::post('/events/{event}/feedback','API\FeedbackController@submit');
    Route::get('/events/{event}/feedbacks','API\FeedbackController@list');

    Route::get('/notifications','API\NotificationController@index');
    Route::post('/notifications/{notification}/read','API\NotificationController@markRead');
});
--------------------------------------------------------------------------

This is my config/cors.php file
---------------------------------------------------------------------
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    | To learn more: https://developer.mozilla.org/en-US/docs/Web/HTTP/CORS
    |
    */

    'paths' => ['api/*', 'sanctum/csrf-cookie'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [env('CLIENT_URL', 'http://localhost:8080')],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 0,
    'supports_credentials' => true,
];
-------------------------------------------------------------------------

This is my config/sanctum.php file
--------------------------------------------------------------------------
<?php

use Laravel\Sanctum\Sanctum;

return [

    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies. Typically, these should include your local
    | and production domains which access your API via a frontend SPA.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', 'localhost,127.0.0.1')),
    
    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | This array contains the authentication guards that will be checked when
    | Sanctum is trying to authenticate a request. If none of these guards
    | are able to authenticate the request, Sanctum will use the bearer
    | token that's present on an incoming request for authentication.
    |
    */

    'guard' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued token will be
    | considered expired. This will override any values set in the token's
    | "expires_at" attribute, but first-party sessions are not affected.
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Token Prefix
    |--------------------------------------------------------------------------
    |
    | Sanctum can prefix new tokens in order to take advantage of numerous
    | security scanning initiatives maintained by open source platforms
    | that notify developers if they commit tokens into repositories.
    |
    | See: https://docs.github.com/en/code-security/secret-scanning/about-secret-scanning
    |
    */

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | When authenticating your first-party SPA with Sanctum you may need to
    | customize some of the middleware Sanctum uses while processing the
    | request. You may change the middleware listed below as required.
    |
    */

    'middleware' => [
        'authenticate_session' => Laravel\Sanctum\Http\Middleware\AuthenticateSession::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
    ],

];
-----------------------------------------------------------------------

this is my app/Http/Kernel.php file
------------------------------------------------------------------------
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'role' => [
            \App\Http\Middleware\RoleMiddleware::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class,
        'signed' => \App\Http\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    ];
}
-----------------------------------------------------------------------

This is my app/Http/Middleware/Authenticate.php file
-----------------------------------------------------------------------
<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     */
    protected function redirectTo(Request $request): ?string
    {
            // For API requests, just return null so Laravel sends 401 JSON
            if ($request->is('api/*') || $request->expectsJson()) {
                return null;
        }

        // For web routes, redirect to login page if it exists
        return route('login'); // make sure this route exists for web auth
    }
}
-------------------------------------------------------------------------

This is my app/Http/Middleware/Rolemiddleware.php file
-------------------------------------------------------------------------
<?php

namespace App\Http\Middleware;

use Closure;

class RoleMiddleware
{
    public function handle($request, Closure $next, ...$roles)
    {
        $user = $request->user();
        if (!$user || !in_array($user->role, $roles, true)) {
            return response()->json(['message' => 'Forbidden'], 403);
        }
        return $next($request);
    }
}

----------------------------------------------------------------------
