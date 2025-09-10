<?php
use Psr\Container\ContainerInterface;

$container->set('db', function(ContainerInterface $c) {
    $settings = $c->get('settings');
    $path = $settings['db']['sqlite_path'];

    $pdo = new PDO("sqlite:$path");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create table if it doesn't exist
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS documents (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            filename TEXT NOT NULL,
            content TEXT NOT NULL
        )
    ");

    // Insert a sample document if table empty
    $stmt = $pdo->query("SELECT COUNT(*) FROM documents");
    if ($stmt->fetchColumn() == 0) {
        $pdo->exec("
            INSERT INTO documents (title, filename, content) VALUES
            ('Sample Document', 'sample.txt', 'This is a preloaded research document included with the proof of concept.')
        ");
    }

    return $pdo;
});