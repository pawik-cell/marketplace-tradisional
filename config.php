<?php
$cfg = require __DIR__.'/config.local.php';
mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli($cfg['host'],$cfg['user'],$cfg['pass'],$cfg['db']);
if ($db->connect_errno) { http_response_code(500); die('Koneksi database gagal.'); }
$db->set_charset('utf8mb4');
function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function rp($n){ return 'Rp '.number_format($n,0,',','.'); }
