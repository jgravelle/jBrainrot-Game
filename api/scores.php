<?php
// jBrainRot leaderboard API
//   GET  scores.php?mode=global&limit=10
//   GET  scores.php?mode=daily&day=YYYY-MM-DD&limit=10
//   POST scores.php  {initials, score, wave, combo, mode, day?, t}
// Deploy next to config.php (see config.sample.php) on Hostinger.

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://jgravelle.github.io');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
// JSON POSTs trigger a CORS preflight — answer it before anything else
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

require __DIR__ . '/config.php';

function fail($code, $msg){ http_response_code($code); echo json_encode(['ok'=>false,'error'=>$msg]); exit; }

// FNV-1a 32-bit — mirrors jbrToken() in index.html
function jbrToken($s){
  $h = 2166136261;
  for ($i = 0, $n = strlen($s); $i < $n; $i++) {
    $h ^= ord($s[$i]);
    $h = ($h * 16777619) & 0xFFFFFFFF;
  }
  return dechex($h);
}

try {
  $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) { fail(500, 'db'); }

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
  $mode  = (($_GET['mode'] ?? 'global') === 'daily') ? 'daily' : 'global';
  $limit = min(50, max(1, (int)($_GET['limit'] ?? 25)));
  if ($mode === 'daily') {
    $day = $_GET['day'] ?? gmdate('Y-m-d');
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $day)) fail(400, 'bad day');
    $st = $pdo->prepare("SELECT initials, score, wave, combo FROM scores WHERE mode='daily' AND day=? ORDER BY score DESC LIMIT $limit");
    $st->execute([$day]);
  } else {
    $st = $pdo->prepare("SELECT initials, score, wave, combo FROM scores WHERE mode='global' ORDER BY score DESC LIMIT $limit");
    $st->execute();
  }
  echo json_encode($st->fetchAll(PDO::FETCH_ASSOC));
  exit;
}

if ($method === 'POST') {
  $in = json_decode(file_get_contents('php://input'), true);
  if (!is_array($in)) fail(400, 'bad json');

  // honeypot: anything that fills "email" gets a quiet thumbs-up and nothing stored
  if (!empty($in['email'])) { echo json_encode(['ok'=>true]); exit; }

  $initials = strtoupper(trim((string)($in['initials'] ?? '')));
  $score = (int)($in['score'] ?? -1);
  $wave  = (int)($in['wave'] ?? -1);
  $combo = (int)($in['combo'] ?? -1);
  $mode  = (($in['mode'] ?? '') === 'daily') ? 'daily' : 'global';
  $day   = $in['day'] ?? null;
  $token = (string)($in['t'] ?? '');

  if (!preg_match('/^[A-Z]{1,3}$/', $initials)) fail(400, 'bad initials');
  if ($score < 1 || $wave < 1 || $combo < 1)    fail(400, 'bad numbers');
  // plausibility ceiling — generous, but a curl kiddie can't post 999999999
  if ($wave > 150 || $combo > 99 || $score > 3000 + $wave * 9000) fail(400, 'implausible');

  if ($mode === 'daily') {
    if (!is_string($day) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $day)) fail(400, 'bad day');
    // accept today UTC ±1 day for clock skew
    if (!in_array($day, [gmdate('Y-m-d'), gmdate('Y-m-d', time()-86400), gmdate('Y-m-d', time()+86400)], true)) {
      fail(400, 'stale day');
    }
  } else {
    $day = null;
  }

  $expect = jbrToken($score . '|' . $wave . '|' . ($day ?? '') . '|' . JBR_SALT);
  if (!hash_equals($expect, $token)) fail(403, 'bad token');

  // rate limit: 20 submissions per IP hash per hour
  $ipHash = md5(($_SERVER['REMOTE_ADDR'] ?? '') . JBR_SALT);
  $st = $pdo->prepare('SELECT COUNT(*) FROM scores WHERE ip_hash=? AND created_at > (NOW() - INTERVAL 1 HOUR)');
  $st->execute([$ipHash]);
  if ((int)$st->fetchColumn() >= 20) fail(429, 'slow down');

  $st = $pdo->prepare('INSERT INTO scores (mode, day, initials, score, wave, combo, ip_hash) VALUES (?,?,?,?,?,?,?)');
  $st->execute([$mode, $day, $initials, $score, $wave, $combo, $ipHash]);

  $st = $pdo->prepare('SELECT COUNT(*)+1 FROM scores WHERE mode=? AND (day <=> ?) AND score > ?');
  $st->execute([$mode, $day, $score]);
  echo json_encode(['ok'=>true, 'rank'=>(int)$st->fetchColumn()]);
  exit;
}

fail(405, 'method');
