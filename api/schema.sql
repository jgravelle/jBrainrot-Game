-- jBrainRot leaderboard — run once in phpMyAdmin (or `mysql < schema.sql`)
CREATE TABLE IF NOT EXISTS scores (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  mode ENUM('global','daily') NOT NULL,
  day DATE NULL,                        -- NULL for global entries
  initials CHAR(3) NOT NULL,
  score INT UNSIGNED NOT NULL,
  wave SMALLINT UNSIGNED NOT NULL,
  combo SMALLINT UNSIGNED NOT NULL,
  ip_hash CHAR(32) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_board (mode, day, score DESC),
  INDEX idx_rate (ip_hash, created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
