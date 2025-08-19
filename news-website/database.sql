CREATE TABLE users ( -- Table for site users
  id INT AUTO_INCREMENT PRIMARY KEY, -- Unique user ID
  email VARCHAR(255) UNIQUE NOT NULL, -- User email
  password VARCHAR(255) NOT NULL, -- Password
  is_admin TINYINT(1) DEFAULT 0 
);

CREATE TABLE articles ( -- Table for news articles
  id INT AUTO_INCREMENT PRIMARY KEY, --  Article ID
  title VARCHAR(255), -- Article title
  content TEXT, -- Article content
  image VARCHAR(255), -- Optional image URL
  published TINYINT(1) DEFAULT 0,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP -- When it was created
);