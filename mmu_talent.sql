CREATE TABLE IF NOT EXISTS portfolio (
    portfolio_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(100) NOT NULL,
    category ENUM('Music', 'Tech', 'Art', 'Writing') NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    upload_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    resource_type ENUM('Portfolio', 'CV', 'demo')
);

-- Insert some sample data for testing
INSERT INTO portfolio (user_id, title, category, file_path, resource_type)
VALUES 
(1, 'Alice Singing', 'Music', 'uploads/1.jpg', 'Portfolio'),
(2, 'Bob Coding', 'Tech', 'uploads/2.jpg', 'Portfolio'),
(3, 'Cindy Drawing', 'Art', 'uploads/3.jpg', 'Portfolio'),
(4, 'ABC', 'Art', 'uploads/4.jpg', 'Portfolio'),
(5, 'BCD Drawing', 'Tech', 'uploads/5.jpg', 'Portfolio'),
(6, 'NANA Drawing', 'Art', 'uploads/6.jpg', 'Portfolio'),