# University Event Management System

A comprehensive full-stack web application for managing university events with role-based access control, real-time notifications, and advanced analytics.

## 🚀 Tech Stack

### Frontend
- **Vue.js 2.6** - Progressive JavaScript framework
- **Vue Router 3.6** - Official router for Vue.js
- **Vuex 3.6** - State management pattern + library
- **Tailwind CSS 3.4** - Utility-first CSS framework
- **Chart.js 4.5** - JavaScript charting library
- **FullCalendar 6.1** - Full-sized drag & drop event calendar
- **Axios 1.11** - Promise-based HTTP client
- **Pusher.js 8.4** - Real-time WebSocket communication

### Backend
- **Laravel 10** - PHP web application framework
- **PHP 8.1+** - Server-side scripting language
- **MySQL** - Relational database management system
- **Laravel Sanctum** - API authentication system
- **Pusher** - Real-time broadcasting service
- **Simple QR Code** - QR code generation library
- **Mailtrap** - Email testing tool

## 📋 Features

### 🔐 Authentication & Authorization
- Multi-role system (Admin, Organizer, Student)
- Secure login/registration with Laravel Sanctum
- Password reset functionality
- Role-based route protection
- Session management

### 👨‍💼 Admin Dashboard
- **User Management**: Create, update, delete users
- **Event Approval**: Approve/reject organizer event submissions
- **Analytics Dashboard**: 
  - User role distribution charts
  - Event status breakdown
  - Registration trends
  - Real-time statistics
- **System Announcements**: Send emails and in-app notifications to all users, students, or organizers
- **Reports**: Export registration data and generate comprehensive reports
- **Event Management**: Full CRUD operations on all events

### 👔 Organizer Dashboard
- **Event Creation**: Create single or recurring events
- **Event Management**: Edit, delete, and manage own events
- **Registration Tracking**: View and manage event registrations
- **Attendance Management**: QR code-based check-in system
- **Waitlist Management**: Handle event capacity and waitlists
- **Announcements**: Send notifications to students
- **Event Calendar**: Visual calendar view of events
- **Analytics**: Event-specific attendance and engagement metrics

### 🎓 Student Dashboard
- **Event Discovery**: Browse and search available events
- **Event Registration**: Register for events with capacity management
- **My Events**: View registered events and attendance history
- **Event Calendar**: Personal calendar of registered events
- **QR Code Access**: Generate QR codes for event check-in
- **Feedback System**: Submit feedback for attended events
- **Notifications**: Real-time updates on event changes
- **Advanced Search**: Filter events by category, date, location

### 🔔 Notification System
- Real-time in-app notifications using Pusher
- Email notifications for important events
- Notification preferences management
- Mark as read/unread functionality
- Event reminders and updates

### 📊 Advanced Features
- **QR Code System**: Generate and scan QR codes for attendance tracking
- **Recurring Events**: Create events with daily, weekly, or monthly recurrence
- **Event Templates**: Save and reuse event configurations
- **Waitlist Management**: Automatic promotion when spots open
- **File Uploads**: Event posters, gallery images, user avatars
- **Calendar Export**: Export events to external calendars
- **Performance Optimization**: Virtual scrolling, lazy loading, image optimization
- **Responsive Design**: Mobile-first approach with Tailwind CSS

## 🗂️ Project Structure

```
university-event-management/
├── backend/                    # Laravel backend
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/
│   │   │   │   └── API/       # API controllers
│   │   │   └── Middleware/    # Custom middleware
│   │   ├── Models/            # Eloquent models
│   │   ├── Mail/              # Email templates
│   │   └── Services/          # Business logic services
│   ├── database/
│   │   ├── migrations/        # Database migrations
│   │   ├── factories/         # Model factories
│   │   └── seeders/           # Database seeders
│   ├── routes/
│   │   └── api.php            # API routes
│   └── storage/               # File storage
│
├── src/                       # Vue.js frontend
│   ├── components/            # Reusable Vue components
│   │   ├── AppNavbar.vue
│   │   ├── EventCard.vue
│   │   ├── QRCodeDisplay.vue
│   │   └── ...
│   ├── pages/                 # Page components
│   │   ├── Admin/             # Admin pages
│   │   ├── Organizer/         # Organizer pages
│   │   ├── Student/           # Student pages
│   │   ├── Profile/           # Profile pages
│   │   └── Public/            # Public pages
│   ├── router/                # Vue Router configuration
│   ├── store/                 # Vuex store
│   ├── utils/                 # Utility functions
│   ├── api.js                 # API client
│   └── main.js                # Application entry point
│
└── database/                  # SQL dumps
```

## 🛠️ Installation & Setup

### Prerequisites
- Node.js (v14+)
- PHP (v8.1+)
- Composer
- MySQL
- Git

### Backend Setup

1. **Navigate to backend directory**
```bash
cd backend
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Configure environment**
```bash
cp .env.example .env
```

4. **Update `.env` file with your database credentials**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=uinevents
DB_USERNAME=root
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_mailtrap_username
MAIL_PASSWORD=your_mailtrap_password

PUSHER_APP_ID=your_pusher_app_id
PUSHER_APP_KEY=your_pusher_key
PUSHER_APP_SECRET=your_pusher_secret
PUSHER_APP_CLUSTER=your_cluster
```

5. **Generate application key**
```bash
php artisan key:generate
```

6. **Run migrations**
```bash
php artisan migrate
```

7. **Seed database (optional)**
```bash
php artisan db:seed
```

8. **Create storage link**
```bash
php artisan storage:link
```

9. **Start Laravel development server**
```bash
php artisan serve
```
Backend will run on `http://localhost:8000`

### Frontend Setup

1. **Navigate to project root**
```bash
cd ..
```

2. **Install Node dependencies**
```bash
npm install
```

3. **Configure API endpoint**
Update `src/api.js` and `src/http.js` with your backend URL:
```javascript
const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  // ...
});
```

4. **Start development server**
```bash
npm run serve
```
Frontend will run on `http://localhost:8080`

5. **Build for production**
```bash
npm run build
```

## 📡 API Endpoints

### Authentication
- `POST /api/register` - User registration
- `POST /api/login` - User login
- `POST /api/logout` - User logout
- `POST /api/forgot-password` - Request password reset
- `POST /api/reset-password` - Reset password

### Events
- `GET /api/events` - List all events
- `GET /api/events/{id}` - Get event details
- `POST /api/events` - Create event (Organizer/Admin)
- `PUT /api/events/{id}` - Update event (Organizer/Admin)
- `DELETE /api/events/{id}` - Delete event (Organizer/Admin)
- `PUT /api/events/{id}/approve` - Approve event (Admin)
- `PUT /api/events/{id}/reject` - Reject event (Admin)

### Registrations
- `POST /api/events/{id}/register` - Register for event
- `DELETE /api/events/{id}/unregister` - Unregister from event
- `GET /api/my-registrations` - Get user's registrations
- `POST /api/registrations/{id}/checkin` - Check-in attendee

### Notifications
- `GET /api/notifications` - Get user notifications
- `POST /api/notifications/{id}/read` - Mark notification as read
- `POST /api/admin/send-announcement` - Send system announcement (Admin)
- `POST /api/organizer/send-announcement` - Send announcement to students (Organizer)

### Admin
- `GET /api/admin/users` - List all users
- `PUT /api/admin/users/{id}` - Update user
- `DELETE /api/admin/users/{id}` - Delete user
- `GET /api/admin/stats` - Get system statistics

### QR Code
- `GET /api/events/{id}/qr-code` - Get event QR code
- `POST /api/qr/scan` - Scan QR code for check-in
- `GET /api/registrations/{id}/qr` - Generate registration QR code

## 🎨 User Roles & Permissions

### Admin
- Full system access
- User management (CRUD)
- Event approval/rejection
- System-wide announcements
- Analytics and reports
- All organizer and student permissions

### Organizer
- Create and manage own events
- View event registrations
- Check-in attendees via QR code
- Send announcements to students
- Access event analytics
- Manage waitlists

### Student
- Browse and search events
- Register/unregister for events
- View personal event calendar
- Submit event feedback
- Receive notifications
- Generate QR codes for check-in

## 🔒 Security Features

- **CSRF Protection**: Laravel CSRF tokens
- **API Authentication**: Laravel Sanctum tokens
- **Password Hashing**: Bcrypt encryption
- **Input Validation**: Server-side validation
- **XSS Protection**: Output escaping
- **SQL Injection Prevention**: Eloquent ORM
- **Role-based Access Control**: Middleware protection
- **Secure File Uploads**: File type and size validation

## 📱 Responsive Design

The application is fully responsive and optimized for:
- Desktop (1920px+)
- Laptop (1024px - 1919px)
- Tablet (768px - 1023px)
- Mobile (320px - 767px)

## 🚀 Performance Optimizations

- Virtual scrolling for large lists
- Lazy loading of images
- Code splitting and chunking
- Debounced search inputs
- Optimized database queries with indexes
- Image compression and optimization
- Service worker for offline support
- Caching strategies

## 🧪 Testing

### Backend Tests
```bash
cd backend
php artisan test
```

### Frontend Linting
```bash
npm run lint
```

## 📝 Database Schema

### Main Tables
- **users** - User accounts with roles
- **events** - Event information
- **event_registrations** - Event registrations
- **event_waitlist** - Waitlist entries
- **event_media** - Event images/posters
- **notifications** - User notifications
- **feedbacks** - Event feedback

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit changes (`git commit -m 'Add AmazingFeature'`)
4. Push to branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License.

## 👥 Authors

University Event Management System Development Team

## 🐛 Known Issues

- Email confirmation temporarily disabled to prevent registration failures
- Broadcast events removed to fix 500 errors in CRUD operations
- CSRF token handling requires proper configuration

## 📞 Support

For support, email support@university-events.com or open an issue in the repository.

## 🔮 Future Enhancements

- Mobile application (React Native)
- Social media integration
- Payment gateway for paid events
- Advanced analytics with AI insights
- Multi-language support
- Event recommendation system
- Integration with university LMS
- Video conferencing integration for virtual events

---

**Built with ❤️ for University Event Management**
