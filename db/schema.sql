-- schema.sql
--mysql -u root -p talent_portal < schema.sql


CREATE TABLE users (
  user_id INT AUTO_INCREMENT PRIMARY KEY,
  email VARCHAR(255) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  role ENUM('student','admin') DEFAULT 'student',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE user_profile (
  profile_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  talent_category ENUM('Music','Tech','Art','Writing') NOT NULL,
  bio TEXT DEFAULT NULL,
  profile_picture VARCHAR(255) DEFAULT NULL,
  name VARCHAR(100) DEFAULT NULL,
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);

CREATE TABLE portfolio (
  portfolio_id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  title VARCHAR(100) NOT NULL,
  file_path VARCHAR(255) NOT NULL,
  category ENUM('Music','Tech','Art','Writing') DEFAULT NULL,
  upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
  resource_type ENUM('Portfolio', 'CV', 'demo') DEFAULT 'Portfolio',
  FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);



