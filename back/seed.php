<?php

$host     = getenv('DATABASE_HOST')     ?: 'db';
$dbname   = getenv('DATABASE_NAME')     ?: 'docker';
$user     = getenv('DATABASE_USER')     ?: 'docker';
$password = trim(file_get_contents('/run/secrets/db_password')) ?: 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage() . "\n");
}

$users = [
    ['username' => 'admin', 'password' => 'admin123'],
    ['username' => 'alice', 'password' => 'alice123'],
    ['username' => 'bob',   'password' => 'bob123'],
];

$stmt = $pdo->prepare('INSERT IGNORE INTO users (username, password) VALUES (:username, :password)');

foreach ($users as $u) {
    $hashed = password_hash($u['password'], PASSWORD_DEFAULT);
    $stmt->execute([':username' => $u['username'], ':password' => $hashed]);
    echo "Utilisateur '{$u['username']}' ajouté.\n";
}

echo "Seed terminé.\n";
