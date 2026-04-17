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
d:\INNOVATION DATABASE XAMPP\htdocs\COMP1841\Coursework\
├── .git/                       # Git repository
├── Admin/                      # Admin section
│   ├── login/                  # Admin login pages
│   │   ├── Check.php
│   │   ├── index.php
│   │   ├── Login.php
│   │   ├── Logout.php
│   │   ├── Notauthorised.php
│   │   ├── Validate.php
│   │   └── Wrongpassword.php
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
│   └── reviewers.php          # Manage reviewers list
│
├── includes/                   # Backend utilities
│   ├── DatabaseConnection.php # DB connection config
│   └── DatabaseFunction.php   # Database operations
│
├── templates/                  # HTML templates
│   ├── addreview.html.php
│   ├── addreviewers.html.php
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
│   ├── contact_admin.html.php
│   ├── conversation.html.php
│   ├── editreview.html.php
│   ├── filmdetail.html.php
│   ├── films.html.php
│   ├── home.html.php
│   ├── layout.html.php
│   ├── login.html.php
│   ├── rev_conversation.html.php
│   ├── review.html.php
│   └── reviewer_mailbox.html.php
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
├── addreviewers.php            # Add reviewers (user)
├── mailbox_reviewer.php        # Reviewer mailbox
├── delete.php                  # Delete operations
├── logout.php                  # User logout
├── test_hash.php               # Password testing utility
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

### 3. **Review Management**
- Users can submit reviews with ratings
- Admins can view, edit, approve, and delete reviews
- Sort by date, rating (high-to-low, low-to-high)
- Search across film, user, and review text

### 4. **Reviewer Management** (Admin)
- Add new reviewers
- Edit reviewer information
- Manage reviewer access
- View reviewer activity

### 5. **Messaging System**
- Admin ↔ Reviewer conversations
- Message inbox with unread badges
- Real-time conversation threads
- Delete message functionality

### 6. **UI/UX Enhancements**
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
   - Run SQL migrations (if available)
   - Update `includes/DatabaseConnection.php` with credentials

3. **Configure Database Connection**
   ```php
   // Edit includes/DatabaseConnection.php
   $host = 'localhost';
   $user = 'root';
   $pass = '';
   $db = 'your_database_name';
   ```

4. **Start XAMPP Services**
   - Start Apache & MySQL from XAMPP Control Panel
   - Access: `http://localhost/COMP1841/Coursework/`

5. **First Login**
   - Admin: Navigate to `/Admin/login/`
   - User: Use main login page

---

## 📖 Usage Guide

### Admin Tasks
1. **Manage Films**: Go to `Admin/films.php` → Add/Edit/Delete
2. **Moderate Reviews**: Go to `Admin/admin_review.php` → Filter, Sort, Approve
3. **Manage Users**: Go to `Admin/reviewers.php` → Add/Edit reviewers
4. **Check Messages**: Go to `Admin/mailbox_admin.php` → Respond to reviewers

### User Tasks
1. **Browse Films**: `/films.php` → Search by title/genre/year
2. **View Reviews**: `/review.php` → Filter and sort reviews
3. **Submit Review**: `/addreview.php` → Fill form and submit
4. **Edit Review**: `/editreview.php` → Update existing review
5. **Contact Admin**: `/contact_admin.php` → Send message
6. **Check Messages**: `/mailbox_reviewer.php` → View responses

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
✅ Encrypted: Password hashing (test available in `test_hash.php`) and verifying password
✅ SQL injection prevention  
✅ XSS protection with `htmlspecialchars()`  
✅ Role-based access control  
✅ Input validation  

---

## 📱 Responsive Design

- Mobile-first approach
- Breakpoints at 768px for mobile optimization
- Flexible layouts with Flexbox
- Scalable font sizes and spacing

---

## 🚀 Recent Updates

- ✅ Fixed Select2 styling (Navy & Gold theme)
- ✅ Implemented unified search+filter layout (left-aligned)
- ✅ Standardized modal button heights (50px min-height)
- ✅ Applied Director input styling (background color, padding)
- ✅ User/Reviewer interface alignment with Admin
- ✅ CSS specificity improvements for search-bar-container

---

## 📝 File Descriptions

| File | Purpose |
|------|---------|
| `DatabaseConnection.php` | MySQL connection configuration |
| `DatabaseFunction.php` | Reusable database query functions |
| `admin_style.css` | Admin dashboard styling (Navy & Gold) |
| `style.css` | User interface styling (Dark & Gold) |
| `test_hash.php` | Password hash testing utility |
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

---

## 📞 Support & Contact

For issues or questions:
- Create an issue on GitHub
- Review code comments in `templates/` and `Admin/`
- Check `admin_style.css` and `style.css` comments for styling details

---

## 📄 License & Author

This project is part of COMP1841 Coursework.

**Author**: [Victory95-cool](https://github.com/Victory95-cool)  
**Repository**: [FINALCOURSEWORK](https://github.com/Victory95-cool/FINALCOURSEWORK)  
**License**: Educational Use

---

**Last Updated**: April 13, 2026  
**Version**: 1.0  
**Status**: Active Development" 
