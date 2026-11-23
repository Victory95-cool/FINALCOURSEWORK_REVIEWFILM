# Film Review Management System

A web application for managing films and film reviews with two roles:

- **Reviewer**: register, sign in, browse films, write reviews, manage a watchlist, and message administrators.
- **Admin**: manage films, reviewers, reviews, avatars, banned accounts, and messages.

The project is built with plain PHP, MySQL/MariaDB, HTML, CSS, and JavaScript, and is intended to run on XAMPP.

## Features

### Reviewers

- Register and sign in with a username or email address.
- Reset passwords through a time-limited reset link.
- Browse, search, filter, and view film details.
- Submit reviews using a 1-10 rating with an optional meme image.
- Edit or delete reviews owned by the reviewer.
- Manage profile details, avatars, watchlists, and rating statistics.
- Send messages to administrators and follow conversation threads.
- View the ban message assigned by an administrator.

### Administrators

- Sign in through a separate admin interface.
- Add, edit, and delete films.
- Manage directors, genres, and cast members.
- Moderate, edit, and delete reviews.
- Add, edit, ban, or unban reviewer accounts.
- Update administrator details and avatar.
- Manage the mailbox and respond to reviewers.

### Security and data handling

- Database access uses PDO and parameterized queries.
- Passwords are stored with `password_hash()` and verified with `password_verify()`.
- CSRF tokens protect state-changing operations.
- Input, ownership, upload type, MIME type, and file size are validated server-side.
- Poster and review images support JPG, PNG, and WebP formats up to 5 MB.
- Duplicate validation covers film title and release year, reviewer name/email/phone, and poster content.
- Pages and update operations are restricted according to session and role.

## Technology

| Component | Technology |
|---|---|
| Backend | PHP 7+ / PHP 8 |
| Database | MySQL or MariaDB |
| Database API | PDO |
| Frontend | HTML5, CSS3, JavaScript |
| Icons | Font Awesome 6.5.1 |
| Fonts | Google Fonts |
| Multi-select UI | Select2 4.1.0 |

## Project structure

```text
Films/
├── index.php                       # Public home page
├── films.php                       # Film list and search
├── filmdetail.php                  # Film details, reviews, and watchlist
├── review.php                      # Public review list
├── addreview.php                   # Submit a reviewer review
├── editreview.php                  # Edit an owned reviewer review
├── delete.php                      # Shared public deletion handler
├── contact_admin.php               # Send a message to an administrator
├── conversation.php                # Reviewer conversation thread
├── mailbox_reviewer.php            # Reviewer inbox and unread messages
├── user_profile.php                # Reviewer profile, ratings, and watchlist
├── watchlist.php                   # Add/remove films from a watchlist
│
├── Login/
│   ├── addreviewers.php            # Reviewer registration
│   ├── signin.php                  # Reviewer sign-in
│   ├── password_reset.php          # Time-limited password reset
│   └── logout.php                  # Reviewer sign-out
│
├── Admin/
│   ├── addfilms.php                # Add a film
│   ├── editfilms.php               # Edit a film
│   ├── films.php                   # Film management and filters
│   ├── addreview.php               # Create a review as admin
│   ├── editreview.php              # Edit a review
│   ├── admin_review.php            # Review moderation and search
│   ├── delete.php                  # Admin deletion handler
│   ├── addreviewers.php            # Create a reviewer account
│   ├── editreviewers.php           # Edit reviewer details
│   ├── reviewers.php               # Reviewer management
│   ├── ban_reviewer.php            # Ban or unban a reviewer
│   ├── profile.php                 # Administrator profile
│   ├── mailbox_admin.php           # Administrator inbox
│   ├── contact_rev.php             # Start or respond to a reviewer conversation
│   ├── conversation.php            # Administrator conversation thread
│   └── login/
│       ├── Login.php               # Main administrator sign-in page
│       ├── index.php               # Administrator login entry point
│       ├── Check.php               # Login credential check
│       ├── Validate.php            # Session and authorization validation
│       ├── Notauthorised.php       # Unauthorized-access page
│       ├── Wrongpassword.php       # Invalid-password view
│       └── Logout.php              # Administrator sign-out
│
├── classes/
│   ├── Database.php                # PDO connection and prepared-query helper
│   ├── Admin.php                   # Administrator data operations
│   ├── AdminAuth.php               # Administrator sessions and authorization
│   ├── Reviewer.php                # Reviewer CRUD and password reset operations
│   ├── ReviewerAuth.php            # Reviewer sessions and authorization
│   ├── User.php                    # Shared user identity model
│   ├── UserController.php          # Reviewer sign-up and sign-in workflow
│   ├── Film.php                    # Film CRUD, filters, and pagination
│   ├── Review.php                  # Review CRUD and image metadata
│   ├── Conversation.php            # Conversations and mailbox operations
│   ├── Watchlist.php               # Add/remove/list reviewer watchlists
│   ├── ProfileImage.php            # Profile avatar validation and storage
│   ├── ImageUpload.php             # Poster and review image validation/storage
│   ├── Pagination.php              # Shared pagination calculations
│   └── Csrf.php                    # CSRF token generation and validation
│
├── includes/
│   └── bootstrap.php               # Loads classes and initializes shared instances
│
├── templates/
│   ├── admin_layout.html.php       # Shared administrator page layout
│   ├── admin_login.html.php        # Administrator login form
│   ├── admin_films.html.php        # Administrator film list
│   ├── admin_addfilms.html.php     # Add-film form
│   ├── admin_editfilms.html.php    # Edit-film form
│   ├── admin_review.html.php       # Administrator review list
│   ├── admin_addreview.html.php    # Admin add-review form
│   ├── admin_editreview.html.php   # Admin edit-review form
│   ├── admin_reviewers.html.php    # Reviewer management list
│   ├── admin_addreviewers.html.php # Admin add-reviewer form
│   ├── admin_editreviewers.html.php
│   │                               # Admin edit-reviewer form
│   ├── admin_profile.html.php      # Administrator profile dialog
│   ├── admin_mailbox.html.php      # Administrator mailbox
│   ├── admin_contactrev.html.php   # Admin reviewer-contact form
│   ├── admin_conversation.html.php # Administrator conversation
│   ├── pagination.html.php         # Administrator pagination controls
│   ├── user_layout.html.php        # Shared reviewer/public layout
│   ├── user_home.html.php          # Public home-page content
│   ├── user_films.html.php         # Public film list
│   ├── user_filmdetail.html.php    # Film details and paginated reviews
│   ├── user_review.html.php        # Public review list
│   ├── user_addreview.html.php     # Reviewer review form
│   ├── user_editreview.html.php    # Reviewer edit-review form
│   ├── user_profile.html.php       # Reviewer profile and watchlist
│   ├── user_signin.html.php        # Reviewer sign-in form
│   ├── user_addreviewers.html.php  # Reviewer registration form
│   ├── user_password_reset.html.php
│   │                               # Password reset form
│   ├── user_banned.html.php        # Banned-reviewer message
│   ├── user_contact_admin.html.php # Reviewer admin-contact form
│   ├── user_conversation.html.php  # Reviewer conversation
│   ├── user_reviewer_mailbox.html.php
│   │                               # Reviewer mailbox
│   └── user_pagination.html.php    # Public/reviewer pagination controls
│
├── images/
│   ├── *.jpg/png/webp              # Film posters and static media
│   ├── avatars/                    # Reviewer/admin profile images
│   └── review-images/              # Meme images attached to reviews
│
├── style.css                       # Public/reviewer stylesheet
├── admin_style.css                 # Administrator stylesheet
├── finalcoursework.sql             # Database schema and seed data
└── README.md                       # Project documentation
```

## XAMPP installation

### Requirements

- XAMPP for Windows.
- Apache and MySQL/MariaDB.
- PHP 7 or newer; PHP 8.2 is recommended.
- A modern web browser.

### Steps

1. Place the project in the XAMPP web directory:

   ```text
   D:\xampp\htdocs\COMP1842\Coursework - Baseline\Films
   ```

2. Open XAMPP Control Panel and start **Apache** and **MySQL**.

3. Open phpMyAdmin:

   ```text
   http://localhost/phpmyadmin
   ```

4. Create a database named `finalcoursework`, then import:

   ```text
   finalcoursework.sql
   ```

5. Check the database settings in `classes/Database.php`:

   ```php
   new PDO(
       'mysql:host=localhost;dbname=finalcoursework;charset=utf8mb4',
       'root',
       ''
   );
   ```

   Change `dbname`, the username, or the password if your local MySQL configuration differs.

6. Open the application:

   ```text
   http://localhost/COMP1842/Coursework%20-%20Baseline/Films/
   ```

   Alternatively, use the actual project path configured on your machine.

## Sign-in accounts

### Reviewer

- Registration: `Login/addreviewers.php`
- Sign-in: `Login/signin.php`
- Some sample SQL accounts do not have passwords. Use `Login/password_reset.php` to set a password using the account email.
- On localhost, the password reset link is shown directly for testing convenience.

### Admin

- Open `Admin/login/Login.php`.
- Sample administrator credentials are stored in the `admins` table in `finalcoursework.sql`.
- The sample account stores a password hash, not a plain-text password.

## Main pages

| Role | Path | Purpose |
|---|---|---|
| Public | `index.php` | Home page |
| Public | `films.php` | Film list |
| Public | `filmdetail.php?id=<film_id>` | Film details and reviews |
| Reviewer | `Login/signin.php` | Sign in |
| Reviewer | `Login/addreviewers.php` | Registration |
| Reviewer | `Login/password_reset.php` | Password reset |
| Reviewer | `addreview.php` | Submit a review |
| Reviewer | `user_profile.php` | Profile and watchlist |
| Reviewer | `mailbox_reviewer.php` | Mailbox |
| Admin | `Admin/login/Login.php` | Sign in |
| Admin | `Admin/films.php` | Manage films |
| Admin | `Admin/admin_review.php` | Manage reviews |
| Admin | `Admin/reviewers.php` | Manage reviewers |
| Admin | `Admin/mailbox_admin.php` | Administrator mailbox |

## Usage

### Reviewer

1. Register an account or reset the password for an existing account.
2. Sign in and open the film list.
3. Select a film to view details and reviews, and add it to the watchlist.
4. Open the review page to submit a text review and rating.
5. Open the profile page to update account details, avatar, or watchlist.
6. Use `Contact Admin` or `Mail Box` to communicate with the administrator.

### Admin

1. Sign in through the administrator area.
2. Open `Films` to add, edit, or delete films.
3. Open `Reviews` to moderate reviews.
4. Open `Reviewers` to update or ban accounts.
5. Open `Mail Box` to read and respond to messages.

## Data and upload directories

- Film posters: `images/`
- Reviewer/admin avatars: `images/avatars/`
- Review meme images: `images/review-images/`
- SQL file: `finalcoursework.sql`

Ensure that `images/`, `images/avatars/`, and `images/review-images/` exist and that Apache has write permissions.

## Troubleshooting

| Issue | Solution |
|---|---|
| Database connection fails | Verify that MySQL is running and check the database name and PDO settings |
| SQL import fails | Create an empty `finalcoursework` database, select `utf8mb4`, and import again |
| CSS or images do not load | Check folder names, URLs, and file permissions |
| Image uploads fail | Check upload directory write permissions and the 5 MB file-size limit |
| Reviewer sign-in fails | Use password reset or create a new account |
| Reset link does not work | Verify that the token is valid, unused, and accessed through the correct host |
| Admin/reviewer sessions end unexpectedly | Check browser cookies and the PHP session configuration |

## Project information

- **Course:** COMP1842
- **Author:** [Victory95-cool](https://github.com/Victory95-cool)
- **Repository:** [Victory95-cool/FINALCOURSEWORK_COMP1841_REVIEWFILM](https://github.com/Victory95-cool/FINALCOURSEWORK_COMP1841_REVIEWFILM)
- **Purpose:** Coursework and educational demonstration
- **Last updated:** November 23, 2026
