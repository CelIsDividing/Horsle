# MatchMe - Tinder-like Dating Application

## 📋 Project Description

MatchMe is a PHP application that simulates Tinder functionality. Users can register, log in, browse other users’ profiles, and like/discard profiles.

## ✨ Implemented Features

The project covers all required aspects:

### 1. **Forms and their processing (7 points)**
- Registration form with validation
- Login form
- Profile edit form
- AJAX handling of like/dislike actions
- Server-side validation of all input data

### 2. **Protection against XSS attacks (7 points)**
- `sanitize_input()` function cleans all user input
- `htmlspecialchars()` with `ENT_QUOTES` for safe searching
- Use of prepared statements in all SQL queries
- `safe_output()` function for safe output/display of data
- CSRF token protection is possible using `session_id()`

### 3. **Working with cookies and sessions (8 points)**
- Session management using the `$_SESSION` global variable
- Cookies with an expiration time (30 days) for `user_id` and `username`
- Automatic logout by deleting the session and cookies
- Session validation on all protected pages
- Session timeout possible with `session_set_cookie_params()`

### 4. **Working with a MySQL database (8 points)**
- Three tables: `users`, `likes`, `profiles_viewed`
- Operations: `INSERT`, `SELECT`, `UPDATE`
- Foreign keys for relations between tables
- Prepared statements for all SQL queries
- Transactions and error handling

## 🗂️ File Structure

```
.
├── index.php           # Home page
├── register.php        # User registration
├── login.php           # User login
├── dashboard.php       # Profile browsing
├─�� profile.php         # My profile
├── logout.php          # Logout
├── db_config.php       # Database configuration
├── functions.php       # Helper functions
└── setup.sql           # SQL for creating the database
```

## 🚀 Installation and Running

### 1. Download the files
- Put all files into the `htdocs` folder (if you use XAMPP) or `/var/www/html` (if you use Linux)

### 2. Create the database
- Open phpMyAdmin (http://localhost/phpmyadmin)
- Create a new database called `tinder_db`
- Copy the contents of `setup.sql` and run it in the SQL tab

Or directly in the terminal:
```bash
mysql -u root -p < setup.sql
```

### 3. Configure the connection
- Open `db_config.php`
- Change `$db_user`, `$db_password`, and `$db_name` if needed

### 4. Run the application
- Open the browser and go to: `http://localhost/Veb%20programiranje/Projekat/`

## 👤 Test users

You can create test users through the registration form, or insert them directly into the database:

```sql
INSERT INTO users (username, email, password, first_name, age, gender, city, bio, looking_for) 
VALUES 
('testuser1', 'test1@example.com', '$2y$10$...', 'John', 25, 'Male', 'Belgrade', 'I love traveling', 'A relationship'),
('testuser2', 'test2@example.com', '$2y$10$...', 'Jane', 23, 'Female', 'Novi Sad', 'Book lover', 'A relationship');
```

For the password, use `password_hash('password123', PASSWORD_DEFAULT)` in PHP.

## 🔒 Security

- All passwords are hashed with `password_hash()` and `PASSWORD_DEFAULT`
- All user input is cleaned to prevent XSS attacks
- Prepared statements protect against SQL injection
- Sessions are active while browsing the application

## 🛠️ Technologies

- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3
- JavaScript (minimal, for form interactions)

## 📝 Notes

- CSS is included for better UX, but it is not graded
- File handling is not implemented (as required)
- All values are properly sanitized before being displayed

## 🎯 How the application works

1. **Not logged in user** -> `index.php` landing page
2. **Registration** -> `register.php` (validation and saving to the database)
3. **Login** -> `login.php` (`password_verify`, setting sessions and cookies)
4. **Dashboard** -> `dashboard.php` (browse profiles, like/dislike)
5. **Profile** -> `profile.php` (edit your information)
6. **Logout** -> `logout.php` (deleting the session and cookies)

## 📞 Support

If you have questions or problems, check:
- Whether the MySQL server is running
- Whether the credentials in `db_config.php` are correct
- The file location (they should be accessible via http://localhost)

---

**Authors:**
- Project created for the “Web Programming” course
- Date: February 2026