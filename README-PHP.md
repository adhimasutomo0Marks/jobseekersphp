# Job Seekers Web Application - PHP + MySQL Version

A modern job search platform with full CRUD functionality, user authentication, and MySQL database integration.

## 🚀 New Features (PHP Version)

- ✅ **MySQL Database Integration** - Real database instead of localStorage
- ✅ **Server-side Authentication** - Secure PHP sessions
- ✅ **Password Hashing** - bcrypt encryption for security
- ✅ **Job Applications Tracking** - Database table for applications
- ✅ **Admin Controls** - Role-based access control
- ✅ **Production Ready** - Can be deployed to live server

## 📁 File Structure (PHP Version)

```
job-seekers/
│
├── config.php           # Database configuration
├── database.sql         # Database setup script
├── session.php          # Session management
│
├── login.php           # Login page with PHP
├── register.php        # Registration page with PHP
├── logout.php          # Logout handler
│
├── home.php            # Main job listings page
├── job-detail.php      # Individual job details page
├── profile.php         # User profile page
│
├── job_operations.php  # CRUD API for jobs
├── apply_job.php       # Job application handler
│
├── styles.css          # All CSS styles
├── home-php.js         # Home page JavaScript
│
└── README-PHP.md       # This file
```

## 🛠️ Setup Instructions

### Step 1: Install XAMPP
1. Download and install XAMPP from https://www.apachefriends.org/
2. Start **Apache** and **MySQL** from XAMPP Control Panel

### Step 2: Create Database
1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Click "New" to create a database
3. Name it: `job_seekers`
4. Click on the database, then go to "SQL" tab
5. Copy and paste the entire contents of `database.sql`
6. Click "Go" to execute

### Step 3: Configure Database Connection
1. Open `config.php`
2. Make sure these settings match your XAMPP:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = ""; // Usually empty for XAMPP
   $dbname = "job_seekers";
   ```

### Step 4: Deploy Files
1. Copy ALL project files to: `C:\xampp\htdocs\job-seekers\`
2. Make sure all these files are in that folder:
   - All .php files
   - styles.css
   - home-php.js
   - database.sql

### Step 5: Access Your Site
1. Open browser and go to: `http://localhost/job-seekers/login.php`
2. Done! 🎉

## 🔐 Demo Accounts

The database comes pre-loaded with demo accounts:

**Admin Account:**
- Email: `admin@jobseekers.com`
- Password: `admin123`
- Can create, edit, and delete jobs

**User Account:**
- Email: `user@jobseekers.com`
- Password: `user123`
- Can browse and apply for jobs

## 📊 Database Structure

### Tables Created:

1. **users** - User accounts
   - id, name, email, password (hashed), is_admin, created_at

2. **user_profiles** - User profile information
   - id, user_id, about, skills, linkedin, github, photo

3. **jobs** - Job listings
   - id, title, company, type, location, description, date_posted, created_by

4. **applications** - Job applications tracking
   - id, job_id, user_id, applied_at, status

## 🎯 How to Use

### For Regular Users

1. **Register/Login**
   - Go to `http://localhost/job-seekers/login.php`
   - Register a new account or use demo credentials

2. **Browse Jobs**
   - All jobs are loaded from MySQL database
   - Use filters to search
   - Click any job to see details

3. **Apply for Jobs**
   - Click "Apply Now" on job detail page
   - Application is saved to database
   - Can view your applications (future feature)

4. **Update Profile**
   - Click profile icon
   - All changes saved to database
   - Upload profile photo (saved as base64)

### For Admin Users

1. **Login as Admin**
   - Use admin credentials
   - Admin buttons appear automatically

2. **Add New Job (CREATE)**
   - Click "+ Add New Job" in sidebar
   - Fill form and submit
   - Saved to MySQL database

3. **Edit Job (UPDATE)**
   - Click "Edit" on any job card
   - Modify details
   - Changes saved to database

4. **Delete Job (DELETE)**
   - Click "Delete" on job card or detail page
   - Confirm deletion
   - Removed from database

## 🔄 Key Differences from JavaScript Version

| Feature | Old (JS) | New (PHP) |
|---------|----------|-----------|
| Data Storage | localStorage | MySQL Database |
| Authentication | Client-side | Server-side Sessions |
| Password Security | Plain text | Hashed (bcrypt) |
| CRUD Operations | JavaScript | PHP + MySQL |
| File Extension | .html | .php |
| Production Ready | No | Yes |

## 🔒 Security Features

✅ **Password Hashing** - Uses PHP's `password_hash()` with bcrypt
✅ **SQL Injection Protection** - Uses `mysqli_real_escape_string()`
✅ **Session Management** - Secure PHP sessions
✅ **Role-based Access** - Admin-only functions protected
✅ **XSS Protection** - HTML escaping on outputs

## 🌐 Deploying to Live Server

### Option 1: Shared Hosting (Easiest)
1. Export database from phpMyAdmin
2. Upload all PHP files via FTP
3. Import database on live server
4. Update `config.php` with live database credentials

### Option 2: AWS EC2
```bash
# SSH into EC2 instance
sudo apt update
sudo apt install apache2 php mysql-server php-mysql

# Upload files to /var/www/html/
# Import database
mysql -u root -p job_seekers < database.sql

# Restart Apache
sudo systemctl restart apache2
```

### Option 3: AWS RDS + S3
- Use RDS for MySQL database
- Update config.php with RDS endpoint
- Host PHP files on EC2 or Elastic Beanstalk

## 📝 Database Queries Examples

**Get all jobs:**
```sql
SELECT * FROM jobs ORDER BY date_posted DESC;
```

**Get user profile:**
```sql
SELECT u.*, p.* FROM users u 
LEFT JOIN user_profiles p ON u.id = p.user_id 
WHERE u.id = 1;
```

**Check applications for a user:**
```sql
SELECT j.*, a.applied_at, a.status 
FROM applications a 
JOIN jobs j ON a.job_id = j.id 
WHERE a.user_id = 1;
```

## 🐛 Troubleshooting

**Problem:** "Connection failed" error
- **Solution:** Check if MySQL is running in XAMPP
- Check config.php database credentials
- Make sure database `job_seekers` exists

**Problem:** "Access denied for user 'root'"
- **Solution:** In config.php, make sure password is empty: `$password = "";`
- Or set the correct password if you changed it

**Problem:** "Table doesn't exist"
- **Solution:** Run the `database.sql` file in phpMyAdmin
- Make sure you selected the `job_seekers` database first

**Problem:** "Headers already sent" error
- **Solution:** Make sure there's no whitespace before `<?php` tags
- Check file encoding (should be UTF-8 without BOM)

**Problem:** Login works but redirects to login again
- **Solution:** Check if sessions are enabled in php.ini
- Make sure cookies are enabled in browser

## 🔧 Configuration

### Change Database Name
1. In phpMyAdmin, create database with new name
2. Update `config.php`: `$dbname = "your_new_name";`
3. Import `database.sql` to new database

### Change Admin Credentials
```sql
UPDATE users 
SET email = 'newemail@example.com', 
    password = '$2y$10$...' 
WHERE id = 1;
```
Use this PHP code to generate new password hash:
```php
echo password_hash('newpassword', PASSWORD_DEFAULT);
```

## 📧 Support

Common issues:
1. ✅ XAMPP not starting - Check port conflicts (80, 3306)
2. ✅ Database errors - Verify credentials in config.php
3. ✅ File not found - Check all files are in htdocs/job-seekers/
4. ✅ Session issues - Clear browser cookies

## 🎓 Learning Resources

- PHP: https://www.php.net/manual/en/
- MySQL: https://dev.mysql.com/doc/
- XAMPP: https://www.apachefriends.org/faq.html

## 📄 License

Free to use for personal and educational projects.

---

**Now with Real Database Power! 🚀**

### Quick Start Command (for experienced users):
```bash
# Create database and import
mysql -u root -p -e "CREATE DATABASE job_seekers;"
mysql -u root -p job_seekers < database.sql

# Access site
http://localhost/job-seekers/login.php
```
