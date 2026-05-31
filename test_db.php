<?php
$host = 'aws-1-us-east-1.pooler.supabase.com';
$db   = 'postgres';
$user = 'postgres.ehepeqyzcxbsjrbelymp';
$pass = 'Dxe3dcfYRm95uUXK';
$port = '5432';

$start = microtime(true);
echo "Testing connection to $host...\n";
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $time = microtime(true) - $start;
    echo "Connection successful! Time: {$time} seconds\n";
} catch (\PDOException $e) {
    $time = microtime(true) - $start;
    echo "Connection failed in {$time}s: " . $e->getMessage() . "\n";
}
