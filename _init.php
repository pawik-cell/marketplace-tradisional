<?php
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if($_SERVER['REQUEST_METHOD']==='OPTIONS'){ http_response_code(204); exit; }
$cfg = require __DIR__.'/../config.local.php';
mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli($cfg['host'],$cfg['user'],$cfg['pass'],$cfg['db']);
function out($code,$arr){ http_response_code($code); echo json_encode($arr,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES|JSON_PRETTY_PRINT); exit; }
if($db->connect_errno) out(500,['status'=>'error','message'=>'Koneksi database gagal']);
$db->set_charset('utf8mb4');
function allow($m){ if($_SERVER['REQUEST_METHOD']!==$m) out(405,['status'=>'error','message'=>"Method harus $m"]); }
