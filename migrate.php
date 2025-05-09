<?php

require 'db.php';

if ($pdo === false) {
    echo "Database connection failed.\n";
    exit;
}

$pdo->exec("CREATE TABLE IF NOT EXISTS migrations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    migration VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

function getAppliedMigrations(PDO $pdo)
{
    $stmt = $pdo->query("SELECT migration FROM migrations");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}

function saveMigration(PDO $pdo, $migration)
{
    // Check if the migration already exists in the database
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM migrations WHERE migration = ?");
    $stmt->execute([$migration]);
    $count = $stmt->fetchColumn();

    if ($count == 0) {
        // If the migration does not exist, insert it
        $stmt = $pdo->prepare("INSERT INTO migrations (migration) VALUES (?)");
        $stmt->execute([$migration]);
    } else {
        // If the migration exists, output a message
        // echo "Migration $migration already exists in the database.\n";
    }
}

function deleteMigration(PDO $pdo, $migration)
{
    $stmt = $pdo->prepare("DELETE FROM migrations WHERE migration = ?");
    $stmt->execute([$migration]);
}

$migrations = glob(__DIR__ . '/migrations/*.php');
$appliedMigrations = getAppliedMigrations($pdo);

$rollback = in_array('--rollback', $argv);

if ($rollback) {
    print_r($appliedMigrations);
    foreach (array_reverse($appliedMigrations) as $migration) {
        $migrationFile = realpath(dirname(__FILE__)) . "/migrations/$migration.php";
        if (file_exists($migrationFile)) {
            require_once $migrationFile;
            $migrationName = basename($migrationFile, '.php');
            // Generate class name based on migration file name (adjust as per your naming convention)
            $className = 'Migration_' . $migrationName;
            if (class_exists($className)) {
                echo "Rolling back $migrationName\n";
                $migrationObject = new $className();
                $migrationObject->down($pdo);
                deleteMigration($pdo, $migrationName);
                echo "Delete migrations\n";
            } else {
                echo "Migration class $className not found for migration $migrationName\n";
            }
        }else {
            echo "Migration file not found" . $migrationFile ."\n";
        }
    }
    echo "All migrations rolled back.\n";
} else {
    $appliedMigrationsSet = array_flip($appliedMigrations); // Create a set of applied migrations for quick lookup
    foreach ($migrations as $migrationFile) {
        $migrationName = basename($migrationFile, '.php');
        if (!isset($appliedMigrationsSet[$migrationName])) { // Check if the migration has not been applied yet
            require_once $migrationFile;
            // Generate class name based on migration file name (adjust as per your naming convention)
            $className = 'Migration_' . $migrationName;
            if (class_exists($className)) {
                echo "Applying $migrationName\n";
                $migrationObject = new $className();
                $migrationObject->up($pdo);
                saveMigration($pdo, $migrationName);
            } else {
                echo "Migration class $className not found for migration $migrationName\n";
            }
        }
    }
    echo "All migrations applied.\n";
}
