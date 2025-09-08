<?php
use Psr\Container\ContainerInterface;

return function (\DI\Container $container, array $settings) {
    $container->set('db', function(ContainerInterface $c) use ($settings) {
        $path = $settings['db']['sqlite_path'];
        $pdo = new PDO("sqlite:$path");
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Auto-create table if not exists
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS documents (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                title TEXT NOT NULL,
                content TEXT NOT NULL
            )
        ");

        // Insert sample doc if table empty
        $stmt = $pdo->query("SELECT COUNT(*) FROM documents");
        if ($stmt->fetchColumn() == 0) {
            $pdo->exec("
                INSERT INTO documents (title, content) VALUES
                ('Sample Document', 'This is a preloaded document for testing search.')
            ");
        }

        return $pdo;
    });
};
