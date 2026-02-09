-- Database Setup for Job Seekers
-- Run this in phpMyAdmin or MySQL command line

-- Create database
CREATE DATABASE IF NOT EXISTS job_seekers;
USE job_seekers;

-- Users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User profiles table
CREATE TABLE IF NOT EXISTS user_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    about TEXT,
    skills TEXT,
    linkedin VARCHAR(255),
    github VARCHAR(255),
    photo LONGTEXT,
    resume VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Jobs table
CREATE TABLE IF NOT EXISTS jobs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    company VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    location VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    date_posted TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE SET NULL
);

-- Job applications table (optional - for tracking applications)
CREATE TABLE IF NOT EXISTS applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    user_id INT NOT NULL,
    applied_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status VARCHAR(50) DEFAULT 'pending',
    FOREIGN KEY (job_id) REFERENCES jobs(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert demo admin user (password: admin123)
INSERT INTO users (name, email, password, is_admin) VALUES 
('Admin User', 'admin@jobseekers.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 1);

-- Insert demo regular user (password: user123)
INSERT INTO users (name, email, password, is_admin) VALUES 
('John Doe', 'user@jobseekers.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 0);

-- Insert demo user profiles
INSERT INTO user_profiles (user_id, about, skills) VALUES 
(1, 'System Administrator', 'Management, HR, Recruitment'),
(2, 'Software Developer', 'JavaScript, React, Node.js');

-- Insert demo jobs
INSERT INTO jobs (title, company, type, location, description, created_by) VALUES 
('Full-stack Developer', 'T-Corp', 'Full-Time', 'Silicon Valley, CA', 
'We are looking for a skilled Full-stack Developer with experience in front-end and back-end frameworks.<br><br>🔹 Responsibilities:<ul><li>Develop and maintain scalable web applications</li><li>Collaborate with designers, engineers, and product managers</li><li>Write clean, testable, and efficient code</li></ul>🔹 Requirements:<ul><li>3+ years experience with JavaScript, Node.js, React</li><li>Understanding of databases (MySQL, MongoDB)</li><li>Strong problem-solving skills</li></ul>', 1),

('Therapeutic Specialist', 'Yellow Corp', 'Full-Time', 'Florida, MIA',
'Yellow Corp is seeking a passionate Therapeutic Specialist to support our healthcare programs.<br><br>🔹 Responsibilities:<ul><li>Provide guidance to patients in therapy sessions</li><li>Collaborate with medical professionals and caregivers</li><li>Maintain detailed progress reports</li></ul>🔹 Requirements:<ul><li>Background in Psychology or Healthcare</li><li>Empathetic communication and listening skills</li><li>Experience in therapy or counseling is a plus</li></ul>', 1),

('Marketing Specialist', 'The Whole Market', 'Part-Time', 'Remote',
'We are seeking a creative Marketing Specialist to join our growing team remotely.<br><br>🔹 Responsibilities:<ul><li>Develop and execute marketing campaigns</li><li>Manage social media and content strategies</li><li>Conduct market research and competitor analysis</li></ul>🔹 Requirements:<ul><li>Experience in digital marketing and SEO</li><li>Strong writing and communication skills</li><li>Ability to work independently in a remote setting</li></ul>', 1);
