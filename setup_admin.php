<?php

require_once "config/database.php";

$name = "System Admin";
$email = "admin@studentportal.com";
$password = "admin123";

$hashedPassword = password_hash(
    $password,
    PASSWORD_DEFAULT
);

try {

    $stmt = $pdo->prepare(
        "INSERT INTO users (name, email, password, role)
         VALUES (?, ?, ?, 'admin')"
    );

    $stmt->execute([
        $name,
        $email,
        $hashedPassword
    ]);

    echo "Admin account created successfully.";

} catch (PDOException $e) {

    echo "Admin account already exists or could not be created.";

}