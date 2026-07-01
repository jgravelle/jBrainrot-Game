<?php
// Copy this file to config.php ON THE SERVER and fill in your values.
// config.php must never be committed to the repo.

// Hostinger MySQL credentials (hPanel → Databases)
define('DB_DSN',  'mysql:host=localhost;dbname=YOUR_DB_NAME;charset=utf8mb4');
define('DB_USER', 'YOUR_DB_USER');
define('DB_PASS', 'YOUR_DB_PASSWORD');

// Must match JBR_SALT in index.html (search for "JBR_SALT").
// This is light deterrence, not security — the client is open source.
define('JBR_SALT', 'jbr-v1-gyatt');
