# University Event Management System - Project Context

## 🎯 Project Overview
**Full-featured web-based University Event Management System** built with Vue.js 2, Laravel 10, and MySQL. The system provides comprehensive event management capabilities with real-time features, role-based access control, and modern UI/UX.

## 📋 Tech Stack & Architecture

### Frontend Stack
- **Framework**: Vue.js 2.6.14
- **Styling**: Tailwind CSS 3.4.17
- **State Management**: Vuex 3.6.2
- **Routing**: Vue Router 3.6.5
- **HTTP Client**: Axios 1.11.0
- **Calendar**: FullCalendar 6.1.19
- **Build Tool**: Vue CLI 5.0.0

### Backend Stack
- **Framework**: Laravel 10.10
- **Database**: MySQL
- **Authentication**: Laravel Sanctum 3.3
- **File Storage**: Laravel Storage (local)
- **Email**: Gmail SMTP
- **QR Codes**: SimpleSoftwareIO QR Code 4.2
- **Broadcasting**: Pusher (configured but not active)

### Development Environment
- **Frontend Server**: http://localhost:8080
- **Backend Server**: http://localhost:8000
- **Database**: MySQL (uinevents)
- **Node.js**: Required for Vue.js development
- **PHP**: ^8.1 required for Laravel 10

## 🏗️ Project Structure

```
university-event-management/
├── backend/                    # Laravel 10 API Backend
│   ├── app/
│   │   ├── Console/Commands/   # Artisan commands
│   │   ├── Events/            # Broadcasting events (configured)
│   │   ├── Http/
│   │   │   ├── Controllers/API/  # 11 API Controllers
│   │   │   └── Middleware/      # Custom middleware
│   │   ├── Mail/              # Email templates
│   │   └── Models/            # 6 Eloquent models
│   ├── config/                # Laravel configuration
│   ├── database/
│   │   ├── factories/         # Model factories for seeding
│   │   ├── migrations/        # 9 database migrations
│   │   └── seeders/           # Database seeders
│   ├── routes/
│   │   ├── api.php           # 25+ API endpoints
│   │   └── channels.php      # Broadcasting channels
│   └── storage/              # File uploads storage
├── src/                      # Vue.js 2 Frontend
│   ├── assets/               # Static assets
│   ├── components/           # 14 Vue components
│   ├── pages/                # 20+ page components
│   │   ├── Admin/           # Admin dashboard & management
│   │   ├── Organizer/       # Event creation & management
│   │   ├── Student/         # Event browsing & registration
│   │   ├── Public/          # Landing, login, register
│   │   ├── Profile/         # User profile management
│   │   └── Utility/         # Error pages, maintenance
│   ├── plugins/             # Vue plugins
│   ├── router/              # Vue Router with guards
│   ├── store/               # Vuex store
│   └── utils/               # Helper utilities
├── public/                  # Static files
└── Configuration files
```

## 🔐 User Roles & Permissions

### Student Role
- Browse and search events
- Register for events
- View personal registrations
- Generate QR codes for check-in
- Submit event feedback
- Receive notifications

### Organizer Role
- Create and edit events
- Upload event media (posters, gallery)
- View event registrations
- Track attendance via QR scanning
- Manage event details
- Access organizer dashboard

### Admin Role
- Approve/reject events
- Manage all users
- Send system announcements
- Export registration reports
- View system statistics
- Full system access

## 📊 Database Schema

### Core Tables
1. **users** - User authentication and profiles
   - Fields: id, name, email, password, role, avatar
   - Roles: student, organizer, admin

2. **events** - Event information and management
   - Fields: id, title, slug, description, start_at, end_at, location, category, status, organizer_id, poster_path, capacity, featured
   - Status: pending, approved, rejected

3. **event_registrations** - User event registrations
   - Fields: id, user_id, event_id, qr_token, checked_in_at
   - Includes QR token for check-in system

4. **feedbacks** - Event ratings and comments
   - Fields: id, event_id, user_id, rating (1-5), comment

5. **notifications** - In-app notification system
   - Fields: id, user_id, title, message, read_at

6. **event_media** - File uploads for events
   - Fields: id, event_id, file_path, type (poster/gallery), caption

### Supporting Tables
- **password_reset_tokens** - Password recovery
- **failed_jobs** - Queue management
- **personal_access_tokens** - Sanctum authentication

## 🚀 API Architecture

### Authentication Endpoints
```
POST /api/register     # User registration
POST /api/login        # User login (Sanctum)
POST /api/logout       # User logout
GET  /api/user         # Get authenticated user
```

### Event Management
```
GET    /api/events              # List all events
GET    /api/events/{id}         # Event details
POST   /api/events              # Create event (organizer/admin)
PUT    /api/events/{id}         # Update event
DELETE /api/events/{id}         # Delete event
GET    /api/my-events           # Organizer's events
PUT    /api/events/{id}/approve # Approve event (admin)
PUT    /api/events/{id}/reject  # Reject event (admin)
```

### Registration & Attendance
```
POST /api/events/{id}/register           # Register for event
GET  /api/my-registrations               # User's registrations
POST /api/registrations/{id}/checkin     # Check-in via QR
```

### File Upload System
```
POST   /api/upload/avatar                # Upload user avatar
POST   /api/events/{id}/upload/poster    # Upload event poster
POST   /api/events/{id}/upload/gallery   # Upload gallery images
DELETE /api/media/{id}                   # Delete media file
```

### QR Code System
```
GET  /api/events/{id}/qr-code           # Get registration QR
POST /api/qr/scan                       # Scan QR for check-in
GET  /api/registrations/{id}/qr         # Generate QR code
```

### Admin Features
```
GET    /api/admin/users                 # User management
PUT    /api/admin/users/{id}            # Update user
DELETE /api/admin/users/{id}            # Delete user
GET    /api/admin/stats                 # Dashboard statistics
POST   /api/admin/send-announcement     # Email announcements
GET    /api/admin/export-registrations  # CSV export
```

## 🎨 Frontend Components

### Core Components
- **AppNavbar.vue** - Dynamic role-based navigation
- **AppFooter.vue** - Application footer
- **EventCard.vue** - Event display component
- **Modal.vue** - Reusable modal component
- **SearchBar.vue** - Event search functionality

### Advanced Components
- **AdvancedSearch.vue** - Multi-criteria search with filters
- **EventCalendar.vue** - FullCalendar integration
- **Pagination.vue** - Reusable pagination
- **Breadcrumb.vue** - Hierarchical navigation
- **FileUpload.vue** - File upload component
- **AvatarUpload.vue** - User avatar management

### QR & Real-time Components
- **QRCodeDisplay.vue** - QR code generation
- **QRScanner.vue** - QR code scanning
- **RealTimeNotifications.vue** - Live notifications (configured)
- **LiveEventUpdates.vue** - Real-time event updates (configured)
- **LiveAttendanceTracker.vue** - Live attendance tracking (configured)

## 🔧 Configuration & Environment

### Backend Environment (.env)
```env
APP_NAME=UniEvents
APP_URL=http://localhost:8000
DB_DATABASE=uinevents
SANCTUM_STATEFUL_DOMAINS=localhost:8080,127.0.0.1:8080
CLIENT_URL=http://localhost:8080
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
BROADCAST_DRIVER=log
```

### Frontend Environment (.env)
```env
VUE_APP_API_BASE=http://localhost:8000
```

### Key Configuration Files
- **backend/config/cors.php** - CORS configuration for SPA
- **backend/config/sanctum.php** - Sanctum authentication
- **backend/config/broadcasting.php** - Pusher configuration (ready)
- **tailwind.config.js** - Tailwind CSS configuration
- **vue.config.js** - Vue CLI configuration

## 🛠️ Development Setup

### Prerequisites
- Node.js (for Vue.js frontend)
- PHP 8.1+ (for Laravel backend)
- MySQL database
- Composer (PHP package manager)
- npm/yarn (Node package manager)

### Installation Steps
1. **Backend Setup**:
   ```bash
   cd backend
   composer install
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   php artisan db:seed
   php artisan serve
   ```

2. **Frontend Setup**:
   ```bash
   npm install
   npm run serve
   ```

3. **Database Setup**:
   - Create MySQL database: `uinevents`
   - Run migrations and seeders
   - Configure database credentials in `.env`

## 📱 Features Implementation Status

### ✅ Completed Features
- **Authentication System** - Sanctum-based SPA authentication
- **Role-Based Access Control** - Student/Organizer/Admin roles
- **Event Management** - Full CRUD with approval workflow
- **Registration System** - One-click registration with QR codes
- **File Upload System** - Avatars, posters, gallery images
- **Search & Filtering** - Advanced search with multiple criteria
- **Calendar Integration** - FullCalendar with export functionality
- **Email System** - Gmail SMTP with notifications
- **Admin Dashboard** - User management and analytics
- **Feedback System** - Event ratings and comments
- **QR Code System** - Generation and scanning for check-ins
- **Responsive Design** - Mobile-friendly Tailwind CSS
- **Navigation System** - Dynamic role-based navigation
- **Pagination** - Comprehensive pagination across all lists

### 🔧 Configured but Inactive
- **Real-time Features** - Pusher WebSocket integration ready
- **Broadcasting System** - Events and channels configured
- **Live Notifications** - Components and backend ready
- **Live Attendance Tracking** - Real-time check-in updates

## 🔒 Security Features

### Authentication & Authorization
- **Laravel Sanctum** - SPA authentication with CSRF protection
- **Role-based Middleware** - Route protection by user roles
- **CORS Configuration** - Proper cross-origin setup
- **Password Hashing** - Bcrypt password encryption

### File Security
- **Upload Validation** - File type and size restrictions
- **Storage Security** - Local storage with proper permissions
- **Media Management** - Secure file deletion and access

### API Security
- **CSRF Protection** - Cross-site request forgery prevention
- **Input Validation** - Request validation on all endpoints
- **Rate Limiting** - Built-in Laravel rate limiting
- **Sanctum Guards** - Protected API routes

## 📈 Performance Considerations

### Frontend Optimization
- **Component Lazy Loading** - Route-based code splitting
- **Axios Interceptors** - Efficient API request handling
- **Vuex State Management** - Centralized state management
- **Tailwind CSS** - Utility-first CSS framework

### Backend Optimization
- **Eloquent Relationships** - Efficient database queries
- **API Resource Classes** - Structured API responses
- **File Storage** - Local storage with proper organization
- **Database Indexing** - Optimized database structure

## 🚀 Deployment Considerations

### Production Requirements
- **Web Server** - Apache/Nginx with PHP 8.1+
- **Database** - MySQL 8.0+ or MariaDB
- **SSL Certificate** - HTTPS for production
- **File Permissions** - Proper Laravel storage permissions

### Environment Configuration
- **Production .env** - Secure environment variables
- **Database Migration** - Production database setup
- **File Storage** - Production file storage configuration
- **Email Configuration** - Production SMTP settings

## 📚 Documentation Files

### Project Documentation
- **README.md** - Basic project setup instructions
- **backend.md** - Comprehensive backend documentation
- **Fronted.md** - Frontend structure and components
- **Project Details.text** - Detailed project specifications
- **PROJECT_CONTEXT.md** - This comprehensive context file

### Code Documentation
- **API Routes** - Documented in routes/api.php
- **Model Relationships** - Documented in model files
- **Component Props** - Documented in Vue components
- **Database Schema** - Documented in migration files

## 🔄 Development Workflow

### Git Workflow
- **Main Branch** - Production-ready code
- **Feature Branches** - Individual feature development
- **Code Reviews** - Pull request reviews
- **Version Control** - Semantic versioning

### Testing Strategy
- **Unit Tests** - PHPUnit for backend testing
- **Integration Tests** - API endpoint testing
- **Frontend Testing** - Vue component testing
- **Manual Testing** - User acceptance testing

## 🎯 Future Enhancements

### Planned Features
- **Real-time Activation** - Enable Pusher WebSocket features
- **Mobile App** - React Native or Flutter mobile app
- **Advanced Analytics** - Enhanced reporting and analytics
- **Social Features** - Event sharing and social integration
- **Payment Integration** - Paid event registration
- **Multi-language Support** - Internationalization

### Technical Improvements
- **Performance Optimization** - Database query optimization
- **Caching Strategy** - Redis caching implementation
- **API Documentation** - Swagger/OpenAPI documentation
- **Automated Testing** - CI/CD pipeline setup
- **Monitoring** - Application performance monitoring

---

**Last Updated**: January 2025
**Version**: 1.0.0
**Maintainer**: Development Team