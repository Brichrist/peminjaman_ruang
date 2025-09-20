# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a Laravel-based Room Booking System (Sistem Peminjaman Ruangan) with the following core features:
- User registration with WhatsApp number integration
- 30-minute time slot booking system with auto-approval
- Admin can cancel any booking
- WhatsApp contact integration with auto-formatted messages for contacting room borrowers
- Real-time availability checking

## Essential Commands

### Development Server
```bash
# Start Laravel development server
php artisan serve

# Start frontend build (in separate terminal)
npm run dev
```

### Database Operations
```bash
# Run all migrations
php artisan migrate

# Seed initial data (creates admin user and sample rooms)
php artisan db:seed --class=AdminUserSeeder

# Rollback migrations if needed
php artisan migrate:rollback
```

### Build & Deployment
```bash
# Build frontend assets for production
npm run build

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan optimize
```

## Architecture & Key Components

### Database Structure
- **users** - Extended with `whatsapp` (string) and `is_admin` (boolean) fields
- **rooms** - Contains room details: name, description, capacity, status (available/unavailable)
- **bookings** - Tracks all bookings with relationships to users and rooms, includes cancellation tracking

### Core Models & Relationships
- `User` → hasMany → `Booking` (User.php includes `isAdmin()` helper)
- `Room` → hasMany → `Booking` (Room.php includes availability checking methods)
- `Booking` → belongsTo → `User` & `Room` (Booking.php includes time slot validation logic)

### Controllers Architecture
- **BookingController** - Handles user bookings, includes `schedule()` for viewing room schedules and `checkAvailability()` for AJAX availability checks
- **RoomController** - Admin-only CRUD for rooms, except `list()` which is public
- **AdminController** - Dashboard, user management, booking management, and reporting features

### Authentication & Authorization
- Uses Laravel Breeze for authentication
- Admin middleware implemented inline in controllers using `isAdmin()` check
- Registration flow modified to include WhatsApp field (RegisteredUserController.php)

### Time Slot System
- Slots are 30-minute intervals from 08:00 to 20:00
- `Booking::getTimeSlots()` generates available time slots
- `Booking::isTimeSlotAvailable()` validates slot availability
- Booking duration can be 30, 60, 90, or 120 minutes

### WhatsApp Integration
- Auto-formats Indonesian phone numbers (0xx → 62xx)
- Pre-formatted message template: "Shalom saya [name], permisi saya mau berbicara mengenai peminjaman ruang [room] di jam [time]."
- Integrated in booking creation view and schedule view

## Default Credentials

After running seeders:
- **Admin**: admin@example.com / password
- **User**: user@example.com / password

## Key Views & Routes

### User Routes
- `/dashboard` - Main dashboard with quick access menu
- `/bookings` - User's booking history
- `/bookings/create` - Create new booking
- `/bookings-schedule` - View room schedule grid with WhatsApp contact buttons

### Admin Routes (prefix: `/admin`)
- `/admin/dashboard` - Admin statistics and overview
- `/admin/rooms` - Manage rooms (CRUD)
- `/admin/bookings` - Manage all bookings
- `/admin/users` - User management and admin role assignment

## Frontend Stack
- Blade templates with Tailwind CSS
- Alpine.js for interactivity (via Laravel Breeze)
- Vite for asset bundling
- AJAX availability checking using vanilla JavaScript

## Important Implementation Details

### Booking Status Flow
- All user bookings are auto-approved (`status: 'approved'`)
- Only future bookings can be cancelled (`canBeCancelled()` method)
- Cancelled bookings track who cancelled and reason

### Room Availability Logic
The system checks for overlapping bookings using complex time range queries in `isTimeSlotAvailable()` method - be careful when modifying this logic.

### WhatsApp Button Rendering
WhatsApp contact buttons are conditionally rendered only when user has whatsapp number. The message is URL-encoded and includes context about the specific time slot.

## Database Configuration
Default .env expects MySQL database named 'master' on localhost:3306. The system will prompt to create the database if it doesn't exist during first migration.

## IMPORTANT DEVELOPMENT RULES

1. **Database Structure Documentation**
   - ALWAYS check `.claude/table/structure.md` for current database structure before any CRUD operations
   - If columns are missing in the table, adjust the CRUD logic accordingly

2. **Major Changes Analysis**
   - When user states "MAJOR":
     - Review ALL related code comprehensively
     - Double-check to ensure nothing is missed
     - Analyze impact of changes and identify all risks
     - Check for conflicts with current flow
     - ALWAYS confirm implementation approach with user
     - Ask for clarification on anything unclear

3. **Git Commit Rules**
   - NEVER commit without explicit user instruction
   - Always wait for user confirmation before any git operations
   - **IMPORTANT**: All commits MUST start with `V_Upgrade:` prefix
   - Example: `V_Upgrade: Implement mobile-first UI/UX improvements`
   - Document every planned and developed feature in `.claude/task/{feature}.md`
   - Include: problem statement, solution, files changed, testing notes
   - After commit completion: Check upcoming tasks for related items
   - If related tasks found, ask user if they're connected to current files
   - If yes, update status from "upcoming" → "done"
   - Update `.claude/table/structure.md` if database changes were made
   - Write migration last file checked in `structure.md`

4. **Upcoming Task Management**
   - When user requests "upcoming task":
     - Perform comprehensive review of all systems
     - Inform user of possible approaches
     - Confirm roughly if requested flow will work as intended
     - After discussion, ask user for estimated timeline
     - Create summary including:
       - Request details
       - Acceptance criteria
       - Status (done/upcoming)
       - Start date/timeline

5. **CRUD Development Validation**
   - Before developing any CRUD feature:
     - Check database structure in `.claude/table/structure.md`
     - Validate all required fields exist
     - Ensure data types match
     - Verify foreign key relationships
     - If new columns need to be added, MUST confirm with user first

6. **Repeated Issue Analysis & Auto-Major Authority**
   - When user repeatedly (5-6 times) feels a newly added feature is not suitable:
     - Review ALL existing code comprehensively
     - Verify if current code is efficient and effective
     - Check if understanding/interpretation was insufficient
     - Identify if current running code has conflicts with other features
   - After confirmation, gain authority to perform MAJOR changes for that specific feature only
   - **ALWAYS confirm with user** before implementing any MAJOR changes
   - Document the analysis process and findings before proceeding