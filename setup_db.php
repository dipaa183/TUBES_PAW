<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected successfully via PDO\n";

    // Create Database
    $pdo->exec("CREATE DATABASE IF NOT EXISTS fotokopi_online");
    echo "Database created successfully or already exists\n";

    $pdo->exec("USE fotokopi_online");

    function runSqlFile($pdo, $file)
    {
        if (!file_exists($file)) {
            echo "File not found: $file\n";
            return;
        }

        echo "Importing $file...\n";
        $commands = file_get_contents($file);

        // Simple split by semicolon
        $lines = explode(";", $commands);
        foreach ($lines as $line) {
            $line = trim($line);
            if ($line == "" || strpos($line, "--") === 0)
                continue;
            try {
                $pdo->exec($line);
            } catch (PDOException $e) {
                // Ignore errors for duplicates
                // echo "Error: " . $e->getMessage() . "\n";
            }
        }
        echo "Finished importing $file\n";
    }

    runSqlFile($pdo, 'services/backend/database/fotokopi_online.sql');
    runSqlFile($pdo, 'services/backend/database/chat_schema.sql');

} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage() . "\n");
}
?>