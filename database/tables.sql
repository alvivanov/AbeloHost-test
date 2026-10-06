CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS posts (
                                     id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                     image_path VARCHAR(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    content LONGTEXT NOT NULL,
    view_count INT UNSIGNED NOT NULL DEFAULT 0,
    published_at DATE NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS post_categories (
                                               post_id INT UNSIGNED NOT NULL,
                                               category_id INT UNSIGNED NOT NULL,
                                               PRIMARY KEY (post_id, category_id),
    FOREIGN KEY (post_id) REFERENCES posts(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;