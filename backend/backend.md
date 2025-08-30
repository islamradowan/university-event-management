# **COMPLETE BACKEND ANALYSIS**

## 📁 **Current Structure**

### **Controllers (11 Total)**
- ✅ **AdminController.php** - User management, statistics
- ✅ **AdminReportsController.php** - CSV exports, analytics
- ✅ **AuthController.php** - Login/register/logout with Sanctum
- ✅ **EmailController.php** - Gmail SMTP announcements
- ✅ **EventController.php** - CRUD + real-time broadcasting
- ✅ **EventRegistrationController.php** - Registration + real-time check-ins
- ✅ **FeedbackController.php** - Event ratings & comments
- ✅ **FileUploadController.php** - Avatars, posters, gallery uploads
- ✅ **NotificationController.php** - In-app notifications + broadcasting
- ✅ **QRCodeController.php** - QR generation & scanning
- ✅ **UserController.php** - Profile management

### **Broadcasting Events (3 Total)**
- ✅ **EventUpdated.php** - Real-time event changes
- ✅ **NewNotification.php** - Live notifications
- ✅ **AttendanceUpdated.php** - Live check-in updates

### **Mail Classes (3 Total)**
- ✅ **AdminAnnouncement.php** - Professional email template
- ✅ **EventRegistrationConfirmation.php** - Auto-confirmation emails
- ✅ **EventReminder.php** - Scheduled reminder emails

### **Models (6 Total)**
- ✅ **User.php** - Roles, relationships, authentication
- ✅ **Event.php** - Full event model with media, registrations
- ✅ **EventRegistration.php** - QR tokens, check-in timestamps
- ✅ **EventMedia.php** - File uploads, gallery management
- ✅ **Feedback.php** - Ratings & comments system
- ✅ **Notification.php** - In-app notification system

### **Database (9 Migrations)**
- ✅ **users** - Roles, avatars, authentication
- ✅ **events** - Full event data, approval workflow
- ✅ **event_registrations** - QR tokens, attendance tracking
- ✅ **event_media** - File uploads, gallery system
- ✅ **feedbacks** - Rating & comment system
- ✅ **notifications** - In-app notification system
- ✅ **password_reset_tokens** - Password recovery
- ✅ **failed_jobs** - Queue management
- ✅ **personal_access_tokens** - Sanctum tokens

### **Configuration Files**
- ✅ **broadcasting.php** - Pusher WebSocket setup
- ✅ **cors.php** - Enhanced CORS with broadcasting auth
- ✅ **sanctum.php** - SPA authentication configuration
- ✅ **channels.php** - Broadcasting channels with authorization
- ✅ **mail.php** - Gmail SMTP configuration

### **Middleware & Commands**
- ✅ **RoleMiddleware.php** - Role-based access control
- ✅ **SendEventReminders.php** - Automated email scheduler

## 🔧 **Environment Configuration**
```env
# Real-time Features
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2043057
PUSHER_APP_KEY=a68bdc09fdd21617929d
PUSHER_APP_SECRET=48cdefafbf63648e6efd
PUSHER_APP_CLUSTER=mt1

# Email System
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_ENCRYPTION=tls

# Database
DB_CONNECTION=mysql
DB_DATABASE=uinevents

# CORS & Authentication
CLIENT_URL=http://localhost:8080
SANCTUM_STATEFUL_DOMAINS=localhost:8080,127.0.0.1:8080
```

## 📦 **Dependencies**
- ✅ **pusher/pusher-php-server**: ^7.2 - Real-time broadcasting
- ✅ **simplesoftwareio/simple-qrcode**: ^4.2 - QR code system
- ✅ **laravel/sanctum**: ^3.2 - SPA authentication
- ✅ **laravel/framework**: ^10.10 - Core framework

## 🚀 **API Endpoints (25+ Routes)**

### **Authentication**
- POST `/api/register` - User registration
- POST `/api/login` - User login
- POST `/api/logout` - User logout
- POST `/api/broadcasting/auth` - Broadcasting authentication

### **Events**
- GET `/api/events` - List all events
- GET `/api/events/{event}` - Event details
- POST `/api/events` - Create event (organizer/admin)
- PUT `/api/events/{event}` - Update event + broadcasting
- DELETE `/api/events/{event}` - Delete event
- GET `/api/my-events` - Organizer's events

### **Registrations & Attendance**
- POST `/api/events/{event}/register` - Register for event
- GET `/api/my-registrations` - User's registrations
- POST `/api/registrations/{registration}/checkin` - Check-in + broadcasting

### **File Uploads**
- POST `/api/upload/avatar` - Upload user avatar
- POST `/api/events/{event}/upload/poster` - Upload event poster
- POST `/api/events/{event}/upload/gallery` - Upload gallery images
- DELETE `/api/media/{media}` - Delete media files

### **QR Code System**
- GET `/api/events/{event}/qr-code` - Get registration QR
- POST `/api/qr/scan` - Scan QR code for check-in
- GET `/api/registrations/{registration}/qr` - Generate QR code

### **Notifications**
- GET `/api/notifications` - User notifications
- POST `/api/notifications/{notification}/read` - Mark as read
- POST `/api/notifications/send` - Send notification + broadcasting

### **Admin Management**
- GET `/api/admin/users` - User management
- PUT `/api/admin/users/{user}` - Update user
- DELETE `/api/admin/users/{user}` - Delete user
- GET `/api/admin/stats` - Dashboard statistics
- POST `/api/admin/send-announcement` - Email announcements
- GET `/api/admin/export-registrations` - CSV export

### **Feedback System**
- POST `/api/events/{event}/feedback` - Submit feedback
- GET `/api/events/{event}/feedbacks` - List feedbacks

## ✅ **Real-time Broadcasting**
- **EventUpdated** - Broadcasts to `events` channel
- **NewNotification** - Broadcasts to `user.{id}` channel
- **AttendanceUpdated** - Broadcasts to `event.{id}` channel
- **Channel Authorization** - Role-based access control

## 📧 **Email System**
- **Gmail SMTP Integration** - Production-ready emails
- **Registration Confirmations** - Automatic on signup
- **Event Reminders** - Scheduled command
- **Admin Announcements** - Role-based targeting
- **Professional Templates** - HTML email designs

## 🔐 **Security Features**
- **Laravel Sanctum** - SPA authentication
- **Role-based Middleware** - Admin/Organizer/Student access
- **CSRF Protection** - Cross-site request forgery prevention
- **CORS Configuration** - Cross-origin resource sharing
- **File Upload Validation** - Size & type restrictions

**UPDATED BACKEND STRUCTURE**

**NEW FEATURES ADDED:**
✅ Real-time notifications (WebSocket/Pusher)
✅ Live event updates
✅ Real-time attendance tracking
✅ Broadcasting channels and events
✅ Enhanced email system with Gmail SMTP
✅ File upload capabilities
✅ QR code generation and scanning
✅ Admin management features

**BROADCASTING EVENTS:**
- app/Events/EventUpdated.php - Real-time event updates
- app/Events/NewNotification.php - Real-time notifications
- app/Events/AttendanceUpdated.php - Real-time attendance tracking

**NEW CONTROLLERS:**
- FileUploadController.php - Handle file uploads
- QRCodeController.php - QR code generation and scanning
- EmailController.php - Email announcements
- AdminController.php - Admin management
- UserController.php - User profile management

**ENHANCED CONFIGURATIONS:**
- config/broadcasting.php - Pusher configuration
- routes/channels.php - Broadcasting channels with authorization
- Enhanced CORS for broadcasting auth

**ENVIRONMENT UPDATES:**
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2043057
PUSHER_APP_KEY=a68bdc09fdd21617929d
PUSHER_APP_SECRET=48cdefafbf63648e6efd
PUSHER_APP_CLUSTER=mt1

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

**DEPENDENCIES ADDED:**
- pusher/pusher-php-server: ^7.2
- simplesoftwareio/simple-qrcode: ^4.2

**UPDATED BACKEND STRUCTURE**

**BACKEND STRUCTURE:**
✅ Real-time notifications (WebSocket/Pusher)
✅ Live event updates
✅ Real-time attendance tracking
✅ Broadcasting channels and events
✅ Enhanced email system with Gmail SMTP
✅ File upload capabilities
✅ QR code generation and scanning
✅ Admin management features

**BROADCASTING EVENTS:**
- app/Events/EventUpdated.php - Real-time event updates
- app/Events/NewNotification.php - Real-time notifications
- app/Events/AttendanceUpdated.php - Real-time attendance tracking

**NEW CONTROLLERS:**
- FileUploadController.php - Handle file uploads
- QRCodeController.php - QR code generation and scanning
- EmailController.php - Email announcements
- AdminController.php - Admin management
- UserController.php - User profile management

**ENHANCED CONFIGURATIONS:**
- config/broadcasting.php - Pusher configuration
- routes/channels.php - Broadcasting channels with authorization
- Enhanced CORS for broadcasting auth

**ENVIRONMENT UPDATES:**
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2043057
PUSHER_APP_KEY=a68bdc09fdd21617929d
PUSHER_APP_SECRET=48cdefafbf63648e6efd
PUSHER_APP_CLUSTER=mt1

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your_email@gmail.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
```

**DEPENDENCIES ADDED:**
- pusher/pusher-php-server: ^7.2
- simplesoftwareio/simple-qrcode: ^4.2

**BROADCASTING EVENTS:**

app/Events/EventUpdated.php
<?php

namespace App\Events;

use App\Models\Event;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EventUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event;

    public function __construct(Event $event)
    {
        $this->event = $event->load('organizer');
    }

    public function broadcastOn()
    {
        return new Channel('events');
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->event->id,
            'title' => $this->event->title,
            'status' => $this->event->status,
            'start_at' => $this->event->start_at,
            'organizer' => $this->event->organizer->name ?? 'Unknown'
        ];
    }
}

app/Events/NewNotification.php
<?php

namespace App\Events;

use App\Models\Notification;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NewNotification implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $notification;

    public function __construct(Notification $notification)
    {
        $this->notification = $notification;
    }

    public function broadcastOn()
    {
        return new Channel('user.' . $this->notification->user_id);
    }

    public function broadcastWith()
    {
        return [
            'id' => $this->notification->id,
            'title' => $this->notification->title,
            'message' => $this->notification->message,
            'created_at' => $this->notification->created_at
        ];
    }
}

app/Events/AttendanceUpdated.php
<?php

namespace App\Events;

use App\Models\EventRegistration;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $registration;

    public function __construct(EventRegistration $registration)
    {
        $this->registration = $registration->load(['user', 'event']);
    }

    public function broadcastOn()
    {
        return new Channel('event.' . $this->registration->event_id);
    }

    public function broadcastWith()
    {
        return [
            'registration_id' => $this->registration->id,
            'user_name' => $this->registration->user->name,
            'checked_in_at' => $this->registration->checked_in_at,
            'event_id' => $this->registration->event_id
        ];
    }
}

**BROADCASTING CHANNELS:**

routes/channels.php
<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channels
Broadcast::channel('events', function () {
    return true;
});

// User-specific notifications
Broadcast::channel('user.{userId}', function ($user, $userId) {
    return (int) $user->id === (int) $userId;
});

// Event-specific channels for attendance
Broadcast::channel('event.{eventId}', function ($user, $eventId) {
    // Allow organizers and admins to listen to event channels
    return in_array($user->role, ['organizer', 'admin']);
});

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

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'start_at',
        'end_at',
        'location',
        'category',
        'status',
        'organizer_id',
        'poster_path',
        'capacity',
        'featured'
    ];

    // Use casts in Laravel 10
    protected $casts = [
        'start_at' => 'datetime',
        'end_at'   => 'datetime',
    ];

    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function media()
    {
        return $this->hasMany(EventMedia::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }
}


 app/Models/EventRegistration.php
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
        'poster_path',       // storage path
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

**ENHANCED CONTROLLERS:**

app/Http/Controllers/API/FileUploadController.php
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

app/Http/Controllers/API/UserController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = $request->user();
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update($data);

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => $user
        ]);
    }
}

app/Http/Controllers/API/QRCodeController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRCodeController extends Controller
{
    public function generateQR(EventRegistration $registration)
    {
        $qrData = json_encode([
            'registration_id' => $registration->id,
            'user_id' => $registration->user_id,
            'event_id' => $registration->event_id,
            'token' => $registration->qr_token
        ]);

        $qrCode = QrCode::size(200)->generate($qrData);

        return response()->json([
            'qr_code' => base64_encode($qrCode),
            'qr_token' => $registration->qr_token
        ]);
    }

    public function scanQR(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string'
        ]);

        $registration = EventRegistration::where('qr_token', $request->qr_token)->first();

        if (!$registration) {
            return response()->json(['message' => 'Invalid QR code'], 404);
        }

        if ($registration->checked_in_at) {
            return response()->json([
                'message' => 'Already checked in',
                'checked_in_at' => $registration->checked_in_at
            ], 400);
        }

        $registration->update(['checked_in_at' => now()]);

        return response()->json([
            'message' => 'Check-in successful',
            'registration' => $registration->load(['user', 'event'])
        ]);
    }

    public function getRegistrationQR(Request $request, $eventId)
    {
        $user = $request->user();
        
        $registration = EventRegistration::where('user_id', $user->id)
            ->where('event_id', $eventId)
            ->first();

        if (!$registration) {
            return response()->json(['message' => 'Registration not found'], 404);
        }

        return $this->generateQR($registration);
    }
}

app/Http/Controllers/API/EmailController.php
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\AdminAnnouncement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendAnnouncement(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'recipients' => 'required|in:all,students,organizers'
        ]);

        $query = User::query();
        
        if ($request->recipients === 'students') {
            $query->where('role', 'student');
        } elseif ($request->recipients === 'organizers') {
            $query->where('role', 'organizer');
        }

        $users = $query->get();
        $sentCount = 0;

        foreach ($users as $user) {
            try {
                Mail::to($user->email)->send(new AdminAnnouncement(
                    $request->title,
                    $request->message,
                    $user->name
                ));
                $sentCount++;
            } catch (\Exception $e) {
                \Log::error('Failed to send email to ' . $user->email . ': ' . $e->getMessage());
            }
        }

        return response()->json([
            'message' => "Announcement sent to {$sentCount} users",
            'sent_count' => $sentCount,
            'total_users' => $users->count()
        ]);
    }
}

app/Console/Commands/SendEventReminders.php
<?php

namespace App\Console\Commands;

use App\Mail\EventReminder;
use App\Models\Event;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendEventReminders extends Command
{
    protected $signature = 'events:send-reminders';
    protected $description = 'Send reminder emails for events happening tomorrow';

    public function handle()
    {
        $tomorrow = now()->addDay()->startOfDay();
        $endOfTomorrow = now()->addDay()->endOfDay();

        $events = Event::where('status', 'approved')
            ->whereBetween('start_at', [$tomorrow, $endOfTomorrow])
            ->with(['registrations.user'])
            ->get();

        $sentCount = 0;

        foreach ($events as $event) {
            foreach ($event->registrations as $registration) {
                if ($registration->user) {
                    try {
                        Mail::to($registration->user->email)->send(
                            new EventReminder($registration->user, $event)
                        );
                        $sentCount++;
                    } catch (\Exception $e) {
                        $this->error('Failed to send reminder to ' . $registration->user->email);
                    }
                }
            }
        }

        $this->info("Sent {$sentCount} reminder emails for " . $events->count() . " events");
        return 0;
    }
}

app/Mail/EventRegistrationConfirmation.php
app/Mail/EventReminder.php
app/Mail/AdminAnnouncement.php
- Professional HTML email templates
- Automatic registration confirmations
- Event reminder system
- Admin announcement functionality

app/Http/Controllers/API/EventController.php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Events\EventUpdated;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($request->query('status') === 'pending') {
            if (!$user || $user->role !== 'admin') {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return Event::with(['organizer','registrations'])
                ->where('status', 'pending')
                ->orderByDesc('start_at')
                ->get();
        }

        return Event::with(['organizer','media'])
            ->where('status', 'approved')
            ->orderBy('start_at')
            ->get();
    }
    
    public function publicIndex()
    {
        return Event::with('organizer')
            ->where('status', 'approved')
            ->orderBy('start_at')
            ->get();
    }

    public function show(Event $event)
    {
        $event->load(['organizer','media','registrations','feedbacks']);
        return response()->json($event);
    }

    public function myEvents(Request $request)
    {
        $user = $request->user();
        return Event::with('registrations', 'feedbacks')
                    ->where('organizer_id', $user->id)
                    ->orderByDesc('start_at')
                    ->get();
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
        
        // Broadcast event update
        broadcast(new EventUpdated($event));
        
        return response()->json($event);
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return response()->json(['message'=>'Deleted']);
    }

    public function approveEvent(Event $event)
    {
        $event->update(['status' => 'approved']);
        return response()->json($event);
    }

    public function rejectEvent(Event $event)
    {
        $event->update(['status' => 'rejected']);
        return response()->json($event);
    }
}


app/Http/Controllers/API/EventRegistrationController.php
<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Mail\EventRegistrationConfirmation;
use App\Events\AttendanceUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
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

        // Send confirmation email
        try {
            Mail::to($user->email)->send(new EventRegistrationConfirmation($user, $event));
        } catch (\Exception $e) {
            \Log::error('Failed to send registration confirmation email: ' . $e->getMessage());
        }

        return response()->json($reg,201);
    }

    public function checkIn(Request $request, EventRegistration $registration)
    {
        $registration->checked_in_at = now();
        $registration->save();

        // Broadcast attendance update
        broadcast(new AttendanceUpdated($registration));

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
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Notification;
use App\Events\NewNotification;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifs = $request->user()->notifications()->orderBy('created_at', 'desc')->get();
        return response()->json($notifs);
    }

    public function markRead(Notification $notification)
    {
        $notification->read_at = now();
        $notification->save();

        return response()->json($notification);
    }
    
    public function sendNotificationToAll(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'message' => 'required|string',
            'recipients' => 'sometimes|in:all,students,organizers'
        ]);

        $query = User::query();
        $recipients = $data['recipients'] ?? 'all';
        
        if ($recipients === 'students') {
            $query->where('role', 'student');
        } elseif ($recipients === 'organizers') {
            $query->where('role', 'organizer');
        }

        $users = $query->get();
        foreach ($users as $user) {
            $notification = Notification::create([
                'user_id' => $user->id,
                'title' => $data['title'],
                'message' => $data['message'],
            ]);
            
            // Broadcast notification
            broadcast(new NewNotification($notification));
        }

        return response()->json([
            'message' => "In-app notifications sent to {$users->count()} users",
            'sent_count' => $users->count()
        ]);
    }
}
-----------------------------------------------------------------------

**UPDATED API ROUTES:**

routes/api.php
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\FeedbackController;
use App\Http\Controllers\API\AdminReportsController;
use App\Http\Controllers\API\NotificationController;
use App\Http\Controllers\API\EventRegistrationController;
use App\Http\Controllers\API\FileUploadController;
use App\Http\Controllers\API\UserController;
use App\Http\Controllers\API\QRCodeController;
use App\Http\Controllers\API\EmailController;
use App\Http\Controllers\API\AdminController;

Route::get('/public/events', [EventController::class, 'publicIndex']);

// Public auth
Route::post('/register', [AuthController::class,'register']);
Route::post('/login', [AuthController::class,'login']);
Route::post('/logout', [AuthController::class,'logout'])->middleware('auth:sanctum');

// Broadcasting auth
Route::post('/broadcasting/auth', function() {
    return auth()->user();
})->middleware('auth:sanctum');

// Read: all events for authenticated users
Route::middleware(['auth:sanctum', 'role:organizer,admin'])->group(function () {
    Route::get('/my-events', [EventController::class, 'myEvents']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn (Request $request) => $request->user());

    // Read: all authenticated users
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{event}', [EventController::class, 'show']);

    // Write: organizers/admins only
    Route::post('/events', [EventController::class, 'store'])->middleware('role:organizer,admin');
    Route::put('/events/{event}', [EventController::class, 'update'])->middleware('role:organizer,admin');
    Route::delete('/events/{event}', [EventController::class, 'destroy'])->middleware('role:organizer,admin');

    //event approve and reject
    Route::put('/events/{event}/approve', [EventController::class, 'approveEvent'])->middleware('role:admin');
    Route::put('/events/{event}/reject',  [EventController::class, 'rejectEvent'])->middleware('role:admin');

    // Registrations & attendance
    Route::post('/events/{event}/register', [EventRegistrationController::class, 'register']);
    Route::post('/registrations/{registration}/checkin', [EventRegistrationController::class, 'checkIn'])->middleware('role:organizer,admin');
    Route::get('/my-registrations', [EventRegistrationController::class, 'myRegistrations']);

    //Export Registration
    Route::get('/admin/export-registrations', [AdminReportsController::class, 'exportRegistrations'])->middleware('role:admin');

    // Feedback
    Route::post('/events/{event}/feedback', [FeedbackController::class, 'submit']);
    Route::get('/events/{event}/feedbacks', [FeedbackController::class, 'list']);

    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markRead']);
    Route::post('/notifications/send-all', [NotificationController::class, 'sendNotificationToAll'])->middleware('role:admin');
    Route::post('/notifications/send', [NotificationController::class, 'sendNotificationToAll'])->middleware('role:admin');

    // File uploads
    Route::post('/upload/avatar', [FileUploadController::class, 'uploadUserAvatar']);
    Route::post('/events/{event}/upload/poster', [FileUploadController::class, 'uploadEventPoster'])->middleware('role:organizer,admin');
    Route::post('/events/{event}/upload/gallery', [FileUploadController::class, 'uploadEventGallery'])->middleware('role:organizer,admin');
    Route::delete('/media/{media}', [FileUploadController::class, 'deleteEventMedia'])->middleware('role:organizer,admin');

    // User profile
    Route::put('/user/profile', [UserController::class, 'updateProfile']);

    // QR Code system
    Route::get('/events/{event}/qr-code', [QRCodeController::class, 'getRegistrationQR']);
    Route::post('/qr/scan', [QRCodeController::class, 'scanQR'])->middleware('role:organizer,admin');
    Route::get('/registrations/{registration}/qr', [QRCodeController::class, 'generateQR'])->middleware('role:organizer,admin');

    // Email system
    Route::post('/admin/send-announcement', [EmailController::class, 'sendAnnouncement'])->middleware('role:admin');

    // Admin management
    Route::middleware('role:admin')->group(function () {
        Route::get('/admin/users', [AdminController::class, 'getUsers']);
        Route::put('/admin/users/{user}', [AdminController::class, 'updateUser']);
        Route::delete('/admin/users/{user}', [AdminController::class, 'deleteUser']);
        Route::get('/admin/stats', [AdminController::class, 'getStats']);
    });
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

    'paths' => ['api/*', 'sanctum/csrf-cookie', 'login', 'logout', 'register'],
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
        'role' => \App\Http\Middleware\RoleMiddleware::class,
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
        // Accept both: role:admin,organizer  or role:admin organizer
        if (count($roles) === 1 && strpos($roles[0], ',') !== false) {
            $roles = array_map('trim', explode(',', $roles[0]));
        }

        $user = $request->user();
            if (!$user || !in_array(strtolower($user->role), array_map('strtolower', $roles))) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}

----------------------------------------------------------------------
