<?php
require realpath(dirname(__FILE__) . '/env.php');

function getDatabaseConnection($host, $dbname, $username, $password, $port) {
    try {
        $dsn = "mysql:host=$host;port=$port;dbname=$dbname";
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $pdo;
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        die("A database connection error occurred. Please try again later.");
    }
}

$pdo = getDatabaseConnection($host, $dbname, $username, $dbpassword, $port);