<?php
namespace App\Repository;

use PDO;

class DocRepository {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    public function insert(string $title, string $filename, string $content): array {
        $stmt = $this->pdo->prepare("INSERT INTO documents (title, filename, content) VALUES (?, ?, ?)");
        $stmt->execute([$title, $filename, $content]);
        return ['id' => $this->pdo->lastInsertId(), 'title' => $title, 'filename' => $filename];
    }

    public function all(): array {
        return $this->pdo->query("SELECT * FROM documents ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
    }

    public function search(string $q): array {
        $stmt = $this->pdo->prepare("SELECT * FROM documents WHERE content LIKE ? OR title LIKE ?");
        $stmt->execute(['%' . $q . '%', '%' . $q . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
