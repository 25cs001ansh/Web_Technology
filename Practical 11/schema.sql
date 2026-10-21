CREATE DATABASE IF NOT EXISTS `student_db`;
USE `student_db`;

DROP TABLE IF EXISTS `students`;

CREATE TABLE IF NOT EXISTS `students` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL,
  `course` VARCHAR(50) NOT NULL,
  `gender` VARCHAR(10) NOT NULL,
  `status` VARCHAR(20) NOT NULL DEFAULT 'Active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Sample Records
INSERT INTO `students` (`name`, `email`, `course`, `gender`, `status`) VALUES
('John Doe', 'john@example.com', 'Computer Science', 'Male', 'Active'),
('Jane Smith', 'jane.smith@example.com', 'Web Development', 'Female', 'Active'),
('Priya Patel', 'priya.patel@example.com', 'Data Science', 'Female', 'Active'),
('Rohan Mehta', 'rohan.mehta@example.com', 'Web Development', 'Male', 'Active'),
('Aarav Sharma', 'aarav.sharma@example.com', 'Computer Science', 'Male', 'Active'),
('Diya Joshi', 'diya.joshi@example.com', 'Data Science', 'Female', 'Active'),
('Karan Desai', 'karan.desai@example.com', 'Computer Science', 'Male', 'Inactive');
