CREATE DATABASE IF NOT EXISTS dashboard_db;
USE dashboard_db;

-- 1. Dynamic Navigation Menu Table (RBAC Restricted)
DROP TABLE IF EXISTS menu_items;
CREATE TABLE menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(50) NOT NULL,
    link VARCHAR(100) NOT NULL,
    min_role ENUM('user', 'editor', 'admin') DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: RBAC Menu Items
INSERT INTO menu_items (title, link, min_role) VALUES
('Dashboard', '#dashboard', 'user'),
('View Inventory', '#inventory', 'user'),
('Manage Content', '#content', 'editor'),
('User Management', '#users', 'admin'),
('System Settings', 'unauthorized.php?role=guest', 'admin');

-- 2. Inventory Items Table for AJAX/Fetch CRUD Operations
DROP TABLE IF EXISTS items;
CREATE TABLE items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    category VARCHAR(50) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: Inventory Items
INSERT INTO items (name, category, price) VALUES
('Laptop Pro 16', 'Electronics', 1299.00),
('Wireless Ergonomic Mouse', 'Accessories', 29.50),
('Mechanical RGB Keyboard', 'Accessories', 89.00),
('Ultra-HD 4K Monitor', 'Displays', 349.99),
('Noise Cancelling Headphones', 'Audio', 199.50);

-- 3. Users Table (for Student & User Registration Stats)
DROP TABLE IF EXISTS users;
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor', 'user') DEFAULT 'user',
    user_type ENUM('student', 'faculty', 'staff') DEFAULT 'student',
    status ENUM('active', 'pending', 'inactive') DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: Users / Student Registrations
INSERT INTO users (username, email, role, user_type, status, created_at) VALUES
('ansh_admin', '25cs001@charusat.edu.in', 'admin', 'student', 'active', '2026-10-21 09:15:00'),
('priya_editor', 'priya.cs@charusat.ac.in', 'editor', 'faculty', 'active', '2026-10-21 11:30:00'),
('rohit_sharma', 'rohit.25cs045@charusat.edu.in', 'user', 'student', 'active', '2026-10-21 14:10:00'),
('sneha_patel', 'sneha.25cs089@charusat.edu.in', 'user', 'student', 'active', '2026-10-21 16:45:00'),
('dhruv_shah', 'dhruv.25cs102@charusat.edu.in', 'user', 'student', 'active', '2026-10-21 18:20:00'),
('aarav_mehta', 'aarav.25cs012@charusat.edu.in', 'user', 'student', 'pending', '2026-10-21 20:05:00');

-- 4. Events Table (for Dynamic Event Stats)
DROP TABLE IF EXISTS events;
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(50) NOT NULL,
    event_date DATE NOT NULL,
    status ENUM('upcoming', 'completed', 'cancelled') DEFAULT 'upcoming',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: Events
INSERT INTO events (title, category, event_date, status) VALUES
('Cognizance 2026 Tech Fest', 'Technical', '2026-11-05', 'upcoming'),
('Web Development Hackathon', 'Coding', '2026-11-12', 'upcoming'),
('AI & Cloud Computing Workshop', 'Seminar', '2026-11-20', 'upcoming'),
('CSPIT Alumni & Placement Meet', 'Networking', '2026-12-01', 'upcoming');

-- 5. Audit Logs Table (Proof of Recorded Admin Actions)
DROP TABLE IF EXISTS audit_logs;
CREATE TABLE audit_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    role VARCHAR(20) NOT NULL,
    action VARCHAR(50) NOT NULL,
    details TEXT NOT NULL,
    ip_address VARCHAR(45) NOT NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data: Recorded Audit Actions
INSERT INTO audit_logs (username, role, action, details, ip_address, timestamp) VALUES
('ansh_admin', 'admin', 'SYSTEM_INIT', 'Database schema initialized with RBAC & tables', '127.0.0.1', '2026-10-21 09:00:00'),
('ansh_admin', 'admin', 'AUTH_LOGIN', 'Admin authenticated into dashboard', '127.0.0.1', '2026-10-21 09:15:30'),
('ansh_admin', 'admin', 'CREATE_ITEM', 'Added inventory item: Ultra-HD 4K Monitor ($349.99)', '127.0.0.1', '2026-10-21 09:40:12'),
('priya_editor', 'editor', 'UPDATE_ITEM', 'Updated inventory item ID 2: Wireless Ergonomic Mouse ($29.50)', '127.0.0.1', '2026-10-21 11:42:05'),
('ansh_admin', 'admin', 'ROLE_SWITCH', 'RBAC test role switch to editor mode', '127.0.0.1', '2026-10-21 14:05:22'),
('ansh_admin', 'admin', 'SECURITY_CHECK', 'RBAC permissions verified for restricted admin endpoints', '127.0.0.1', '2026-10-21 15:30:18');
