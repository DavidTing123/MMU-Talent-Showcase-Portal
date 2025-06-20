-- insert_sample_data.sql
-- mysql -u root -p talent_portal < insert_sample_data.sql

INSERT INTO users (user_id, email, password_hash, role, created_at) VALUES
(1, 'david@gmail.com', '$2y$10$onBGI7uI3GJL5gR3/tkTVe9pecorF9lAhWQouXnASJvq2faveY7q.', 'student', '2025-06-16 16:31:20'),
(2, 'tingzx2005@gmail.com', '$2y$10$WJ7HRj2w5iTG43gNw.M07.3k5rHq7223e.ZEjgTTfbxoIyXlFrNJq', 'admin', '2025-06-16 16:34:01'),
(3, 'yongzheng1907@gmail.com', '$2y$10$x5dFvq0h8UTMcVs2GC.ujO/6tRdnL6JQpcevq8inobaZjxlMlwEZC', 'student', '2025-06-16 16:38:08'),
(4, 'davidtzx0314@gmail.com', '$2y$10$Ku3vhXF83i7XxlCYhksFveRvMHvYVzkpEROysYGJYm7GcKQ7nLgAS', 'student', '2025-06-16 21:26:40'),
(5, '242UC244PE@student.mmu.edu.my', '$2y$10$rVYN6DJLUCzsAlQq9CCzaO1ZnKBdGE5w.nUzYJfvJ9wnPXlAr7gCe', 'student', '2025-06-17 20:09:20');

INSERT INTO user_profile (profile_id, user_id, talent_category, bio, profile_picture, name) VALUES
(1, 4, 'Music', 'heloo', 'uploads/profile_4_1750163296.png', 'David Ting Zi Xiang'),
(2, 5, 'Tech', 'Sleep...', 'uploads/profile_5_1750163989.png', 'David Ting Zi Xiang');

INSERT INTO portfolio (portfolio_id, user_id, title, file_path, category, upload_date) VALUES
(10, 2, 'sleep', 'uploads/1750161289_Screenshot 2025-06-17 195344.png', 'Art', '2025-06-17 19:54:49'),
(11, 4, 'testing', 'uploads/1750163273_WhatsApp Video 2025-06-17 at 19.03.13.mp4', 'Music', '2025-06-17 20:27:53'),
(12, 4, 'database', 'uploads/1750163333_Screen Recording 2025-06-04 232043.mp4', 'Tech', '2025-06-17 20:28:53'),
(15, 4, 'thumbup', 'uploads/1750163968_1750153396_profile_photo.png', 'Art', '2025-06-17 20:39:28');


-- 插入使用者帳號
INSERT INTO users (user_id, email, password_hash, role) VALUES
(1, 'alice@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEFGHIJK', 'student'),
(2, 'bob@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEFGHIJK', 'student'),
(3, 'cindy@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEFGHIJK', 'student'),
(4, 'abc@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEFGHIJK', 'student'),
(5, 'bcd@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEFGHIJK', 'student'),
(6, 'nana@example.com', '$2y$10$abcdefghijklmnopqrstuv1234567890abcdEFGHIJK', 'student');
