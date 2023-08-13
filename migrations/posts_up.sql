CREATE TABLE IF NOT EXISTS posts (
    id        INT AUTO INCREMENT PRIMARY KEY,
    title     VARCHAR(255) NOT NULL,
    body      TEXT NOT NULL,
    author    VARCHAR(255) NOT NULL
              REFERENCES users(username) ON UPDATE RESTRICT
                                         ON DELETE RESTRICT,
    posted_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
