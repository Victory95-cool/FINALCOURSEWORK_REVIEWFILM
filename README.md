"# Film Review Management System

A comprehensive web application for managing film reviews with dual-role access (Admin & User/Reviewer). Built with PHP, MySQL, HTML, JavaScript and modern CSS styling.

## 🎯 Project Overview

This Film Review Management System allows:
- **Admins** to manage films, reviewers, and moderate reviews
- **Users/Reviewers** to submit and edit film reviews
- **Secure authentication** with role-based access control
- **Interactive UI** with search, filtering, and modal dialogs
- **Real-time messaging** between admins and reviewers

---

## 📁 Project Structure

```
d:\INNOVATION DATABASE XAMPP\htdocs\COMP1842\Coursework - Baseline\Films\
├── .git/                       # Git repository
├── Admin/                      # Admin section
│   ├── login/                  # Admin login pages
│   │   ├── Check.php
│   │   ├── index.php
│   │   ├── Login.php
│   │   ├── Logout.php
│   │   ├── Notauthorised.php
│   │   └── Validate.php
│   ├── addfilms.php           # Add new films
│   ├── addreview.php          # Add reviews (admin)
│   ├── addreviewers.php       # Manage reviewers
│   ├── admin_review.php       # View all reviews
│   ├── contact_rev.php        # Message reviewers
│   ├── conversation.php       # Conversation thread
│   ├── delete.php             # Delete operations
│   ├── editfilms.php          # Edit film details
│   ├── editreview.php         # Edit reviews
│   ├── editreviewers.php      # Edit reviewer info
│   ├── films.php              # Manage films list
│   ├── mailbox_admin.php      # Admin messages
│   ├── reviewers.php          # Manage and ban/block reviewers
│   ├── ban_reviewer.php       # Ban and unban reviewer accounts
│   └── profile.php            # Admin profile management
│
├── classes/                    # OOP domain and service classes
│   ├── Database.php
│   ├── UserController.php
│   ├── Reviewer.php / ReviewerAuth.php
│   ├── Admin.php / AdminAuth.php
│   ├── Film.php / Review.php
│   ├── Csrf.php / ImageUpload.php
│   └── Watchlist.php / ProfileImage.php / Pagination.php
├── includes/
│   └── bootstrap.php           # Loads classes and creates shared instances
│
├── templates/                  # HTML templates
│   ├── admin_addfilms.html.php
│   ├── admin_addreview.html.php
│   ├── admin_addreviewers.html.php
│   ├── admin_contactrev.html.php
│   ├── admin_editfilms.html.php
│   ├── admin_editreview.html.php
│   ├── admin_editreviewers.html.php
│   ├── admin_films.html.php
│   ├── admin_layout.html.php
│   ├── admin_mailbox.html.php
│   ├── admin_review.html.php
│   ├── admin_reviewers.html.php
│   ├── admin_conversation.html.php
│   ├── admin_login.html.php
│   ├── pagination.html.php
│   ├── user_addreview.html.php
│   ├── user_addreviewers.html.php
│   ├── user_contact_admin.html.php
│   ├── user_conversation.html.php
│   ├── user_editreview.html.php
│   ├── user_filmdetail.html.php
│   ├── user_films.html.php
│   ├── user_home.html.php
│   ├── user_layout.html.php
│   ├── user_pagination.html.php
│   ├── user_review.html.php
│   ├── user_profile.html.php
│   ├── user_password_reset.html.php
│   ├── user_banned.html.php
│   └── user_reviewer_mailbox.html.php
│
├── images/                     # Static images & media
├── style.css                   # User interface styling
├── admin_style.css             # Admin interface styling
├── index.php                   # User homepage
├── films.php                   # Film listing (user)
├── filmdetail.php              # Film detail page
├── review.php                  # Reviews listing
├── addreview.php               # Add review (user)
├── editreview.php              # Edit review (user)
├── contact_admin.php           # Message admin
├── conversation.php            # View conversations
├── mailbox_reviewer.php        # Reviewer mailbox
├── delete.php                  # Delete operations
├── Login/                      # Reviewer sign-in, sign-up, reset, and sign-out
├── watchlist.php               # Reviewer watchlist mutations
├── user_profile.php            # Reviewer profile management
├── finalcoursework.sql         # Database schema and seed data
├── database_review_image_migration.sql
├── README.md                   # This file
└── .gitignore                  # Git ignore file
```

---

## 🎨 User Interfaces

### Admin Dashboard (`admin_style.css`)
- **Theme**: Navy (#122033) & Gold (#d4af37) - Professional IMDb-inspired
- **Components**: 
  - Sticky navigation with gold underline effects
  - Search bar + Filter button (left-aligned, no background wrapper)
  - Modal dialogs with uniform height buttons (50px min-height, 12px/24px padding)
  - Glass-morphism mailbox items
  - Select2 multi-select for Cast/Directors with navy & gold styling

### User Interface (`style.css`)
- **Theme**: Dark (#121212) & Gold (#f5c518) - IMDb-inspired
- **Components**:
  - Similar layout to Admin with dark background
  - Search + Filter UI matching Admin design
  - Responsive tables with film posters
  - Review boxes with hover animations
  - Role-based access controls

---

## 🔑 Key Features

### 1. **Role-Based Access Control**
- Admin login required for management features
- User/Reviewer login for submission
- Session-based authentication
- Separate dashboards for each role

### 2. **Film Management** (Admin)
- Add, edit, delete films
- Upload film posters
- Assign directors and cast
- Filter by genre, year range, sort options
- Reject duplicate title and release-year combinations
- Reject duplicate poster files using exact SHA-256 content comparison

### 3. **Review Management**
- Users can submit reviews with ratings
- Users can attach an optional meme image to a review
- User and admin edit pages can replace or retain the existing meme image
- Meme images appear only on film metadata pages, not review-list pages
- Film metadata displays 3 reviews per page
- Admins can view, edit, and delete reviews
- Sort by date, rating (high-to-low, low-to-high)
- Search across film, user, and review text

### 4. **Reviewer Management** (Admin)
- Add new reviewers
- Edit reviewer information
- Ban or unban reviewer accounts with an admin message
- View the total number of banned reviewers
- View reviewer activity

### 5. **Profiles and Watchlists**
- Reviewers can edit their own username, email, phone number, and avatar
- New reviewers start with `images/avatars/unknown avatar.jpg` as their profile picture
- Reviewer profiles show account status, film ratings, and a personal watchlist
- Add or remove films from the watchlist on the film detail page
- Admins can edit their own name, email, and avatar from a separate profile page
- User and admin profile editors open in centered dialogs, with update feedback shown above the profile

### 6. **Messaging System**
- Admin ↔ Reviewer conversations
- Message inbox with unread badges
- Real-time conversation threads
- Delete message functionality

### 7. **UI/UX Enhancements**
- Responsive design for mobile & desktop
- Smooth animations & transitions
- Modal dialogs for filters and sorting
- Font Awesome icons (6.5.1)
- Google Fonts (Playfair Display, Roboto)

---

## 🛠️ Technologies Used

| Category | Technology |
|----------|-----------|
| **Backend** | PHP 7+ |
| **Database** | MySQL |
| **Frontend** | HTML5, CSS3, JavaScript |
| **CSS Framework** | Custom (IMDb-inspired) |
| **Icons** | Font Awesome 6.5.1 |
| **Fonts** | Google Fonts (Playfair Display, Roboto) |
| **UI Library** | Select2 4.1.0 (multi-select) |

---

## 📋 Setup Instructions

### Prerequisites
- XAMPP (Apache, MySQL, PHP)
- Web browser (Chrome, Firefox, Edge)
- Git for version control

### Installation
1. **Clone the repository**
   ```bash
   git clone https://github.com/Victory95-cool/FINALCOURSEWORK.git
   cd Coursework
   ```

2. **Database Setup**
   - Create MySQL database
   - Import `finalcoursework.sql` for a new database. For an existing database, run `database_review_image_migration.sql` once in phpMyAdmin to add optional review-image storage. Existing installations must also have the reviewer authentication tables and columns described in `finalcoursework.sql`.
   - Update the PDO connection values in `classes/Database.php` if MySQL credentials or the database name differ

### Reviewer Authentication
- Sign up with a username, Gmail address, phone number, and password (minimum 8 characters), or sign in with username/email and password.
- Existing reviewer records have no password. Request a reset link using that account's email; on `localhost`, the link is displayed on the reset page for XAMPP testing. Outside localhost, PHP `mail()` must be configured.
- Reviews and reviewer conversations are editable/deletable only by their owner. Admin routes and permissions remain separate.
- Banned reviewers cannot sign in and see the admin-provided ban message.
- Reviewer and admin sign-out both redirect to the public home page, where Sign In, Sign Up, and Admin buttons are available.
- Review meme images accept JPG, PNG, or WebP up to 5 MB and are displayed only with film-detail reviews.
- Reviewer and admin edit forms can replace the existing meme image. Meme images remain absent from review-list tables.

3. **Configure Database Connection**
   ```php
   // Edit classes/Database.php
   'mysql:host=localhost;dbname=finalcoursework;charset=utf8mb4',
   'root',
   ''
   ```

4. **Start XAMPP Services**
   - Start Apache & MySQL from XAMPP Control Panel
   - Access: `http://localhost/COMP1842/Coursework - Baseline/Films/`

5. **First Login**
   - Admin: Navigate to `/Admin/login/`
   - User: Use main login page

---

## 📖 Usage Guide

### Admin Tasks
1. **Manage Films**: Go to `Admin/films.php` → Add/Edit/Delete
2. **Moderate Reviews**: Go to `Admin/admin_review.php` → Filter, sort, edit, or delete
3. **Manage Users**: Go to `Admin/reviewers.php` → Add/Edit reviewers
4. **Check Messages**: Go to `Admin/mailbox_admin.php` → Respond to reviewers

### User Tasks
1. **Browse Films**: `/films.php` → Search by title/genre/year
2. **View Reviews**: `/review.php` → Filter and sort reviews
3. **Create Account / Sign In**: `/Login/addreviewers.php` or `/Login/signin.php`
4. **Submit Review**: `/addreview.php` → Fill form and submit
5. **Edit Review**: `/editreview.php` → Update existing review
6. **Contact Admin**: `/contact_admin.php` → Send message
7. **Check Messages**: `/mailbox_reviewer.php` → View responses

---

## 🎯 CSS Features

### Admin Style (`admin_style.css`)
- **Color Variables**: Navy background, Gold accents, White text
- **Responsive Buttons**: 50px height, 12px/24px padding
- **Modal Dialogs**: Centered, with backdrop blur
- **Form Styling**: Navy surface with gold labels and borders
- **Animations**: slideDown, slideInLeft, fadeIn, slideUp
- **Select2 Customization**: Navy + Gold theme, white text

### User Style (`style.css`)
- **Dark IMDb Theme**: Black background, Gold accents
- **Glass Morphism**: Backdrop blur effects
- **Animations**: Smooth transitions and hover effects
- **Search Bar**: White input, flex layout, positioned icon
- **Responsive Tables**: Poster images, proper spacing

---

## 🔒 Security Features

✅ Session-based authentication
✅ Password hashing with PHP `password_hash()` and verification with `password_verify()`
✅ SQL injection prevention
✅ XSS protection with `htmlspecialchars()`
✅ Role-based access control
✅ CSRF validation for state-changing POST requests
✅ Server-side input, file type, MIME, and size validation
✅ Owner-only reviewer edit/delete permissions
✅ Separate reviewer and admin session states
✅ Managed poster and review-image upload directories

---

## 📱 Responsive Design

- Mobile-first approach
- Breakpoints at 768px for mobile optimization
- Flexible layouts with Flexbox
- Scalable font sizes and spacing

---

## 🚀 Recent Updates

- ✅ OOP controller/model/service structure with shared instances from `bootstrap.php`
- ✅ Separate reviewer and admin authentication
- ✅ Password reset and reviewer ban/block support
- ✅ Reviewer/admin sign-out redirects to the public home page with Sign In, Sign Up, and Admin buttons
- ✅ Review pagination with three reviews per film-detail page
- ✅ Meme image upload and replacement on user/admin review edit forms
- ✅ Meme images shown only in film metadata, never in review-list tables
- ✅ Duplicate validation for film title/release year, poster file content, and reviewer name/email/phone values
- ✅ Inline CSRF errors on reviewer and admin sign-in forms

---

## 📝 File Descriptions

| File | Purpose |
|------|---------|
| `classes/Database.php` | PDO connection factory and prepared-query helper |
| `includes/bootstrap.php` | Loads classes and initializes shared instances |
| `classes/Csrf.php` | Session CSRF token generation and validation |
| `classes/ImageUpload.php` | Validates and stores managed review/poster images |
| `classes/Film.php` | Film CRUD, filtering, poster duplicate checks, and review pagination queries |
| `classes/Review.php` | Review CRUD and optional `image_path` support |
| `classes/Reviewer.php` | Reviewer CRUD, password reset, profile, duplicate, and ban operations |
| `admin_style.css` | Admin dashboard styling (Navy & Gold) |
| `style.css` | User interface styling (Dark & Gold) |
| `delete.php` | Unified deletion handler |
| Various `.php` pages | Route handlers with template rendering |

---

## 🐛 Troubleshooting

| Issue | Solution |
|-------|----------|
| Styles not loading | Clear browser cache (Ctrl+Shift+Delete) |
| Search bar not aligned | Check `search-filter-wrapper` CSS specificity |
| Button heights inconsistent | Verify `min-height: 50px` and `padding` in modal CSS |
| Select2 not styling | Ensure `admin_style.css` Select2 rules have high specificity |
| Images not showing | Verify image paths and `images/` folder permissions |
| Reviewer sign-out opens the wrong page | Verify the request returns to `Login/signin.php` |
| Admin sign-out opens the wrong page | Verify the request returns to `Admin/login/Login.php` |
| Duplicate poster rejected | Upload a different JPG, PNG, or WebP poster file |
| Review image missing | Run `database_review_image_migration.sql` and verify `review.image_path` exists |

---

## 📞 Support & Contact

For issues or questions:
- Create an issue on GitHub
- Review code comments in `templates/` and `Admin/`
- Check `admin_style.css` and `style.css` comments for styling details

---

## 📄 License & Author

This project is part of COMP1842 Coursework.

**Author**: [Victory95-cool](https://github.com/Victory95-cool)  
**Repository**: [FINALCOURSEWORK](https://github.com/Victory95-cool/FINALCOURSEWORK)  
**License**: Educational Use

---

**Last Updated**: November 23, 2026
**Version**: 1.0  
**Status**: Active Development" 
