<?php
$host = 'aws-1-us-east-1.pooler.supabase.com';
$db   = 'postgres';
$user = 'postgres.ehepeqyzcxbsjrbelymp';
$pass = 'Dxe3dcfYRm95uUXK';
$port = '5432';

echo "Testing connection to $host...\n";
try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$db;sslmode=require";
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    echo "Connection successful!\n";
} catch (\PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
