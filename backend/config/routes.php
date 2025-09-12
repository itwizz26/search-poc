<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

/**
 * Helper to safely JSON-encode data.
 */
function safeJsonEncode($data): string {
    array_walk_recursive($data, function (&$item) {
        if (is_string($item)) {
            // Convert invalid UTF-8 sequences to valid UTF-8
            $item = mb_convert_encoding($item, 'UTF-8', 'UTF-8');
        }
    });
    $json = json_encode($data, JSON_UNESCAPED_UNICODE);
    if ($json === false) {
        $json = json_encode(['error' => 'Failed to encode JSON']);
    }
    return $json;
}

return function ($app) {

    $app->get('/api/documents', function (Request $request, Response $response) {
        $db = $this->get('db');
        $stmt = $db->query("SELECT id, title, filename FROM documents ORDER BY id DESC");
        $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $payload = safeJsonEncode($documents);

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->post('/api/upload', function (Request $request, Response $response) {
        $db = $this->get('db');

        $uploadedFiles = $request->getUploadedFiles();
        if (empty($uploadedFiles['file'])) {
            $response->getBody()->write(safeJsonEncode(['error' => 'No file uploaded']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $file = $uploadedFiles['file'];
        if ($file->getError() !== UPLOAD_ERR_OK) {
            $response->getBody()->write(safeJsonEncode(['error' => 'Upload failed']));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        $filename = $file->getClientFilename();
        $contents = $file->getStream()->getContents();
        $contents = mb_convert_encoding($contents, 'UTF-8', 'UTF-8'); // Ensure UTF-8

        // Store file in DB
        $stmt = $db->prepare("INSERT INTO documents (title, filename, content) VALUES (:title, :filename, :content)");
        $stmt->execute([
            ':title' => pathinfo($filename, PATHINFO_FILENAME),
            ':filename' => $filename,
            ':content' => $contents
        ]);

        $response->getBody()->write(safeJsonEncode(['success' => true]));
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->get('/api/search', function (Request $request, Response $response) {
    $db = $this->get('db');
    $queryParams = $request->getQueryParams();
    $q = trim($queryParams['q'] ?? '');
    if ($q === '') {
        $response->getBody()->write(safeJsonEncode([]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    // Search in title or content
    $stmt = $db->prepare("
        SELECT id, title, filename, content 
        FROM documents 
        WHERE content LIKE :q OR title LIKE :q
        ORDER BY id DESC
    ");
    $stmt->execute([':q' => "%$q%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Highlight matches in content
    foreach ($results as &$doc) {
        $doc['highlighted_content'] = preg_replace("/(" . preg_quote($q, '/') . ")/i", '<mark>$1</mark>', $doc['content']);
    }

    $payload = safeJsonEncode($results);
    $response->getBody()->write($payload);
    return $response->withHeader('Content-Type', 'application/json');
});

    $app->delete('/api/documents/{id}', function (Request $request, Response $response, array $args) {
        $db = $this->get('db');
        $id = (int)$args['id'];
        $stmt = $db->prepare("DELETE FROM documents WHERE id = :id");
        $stmt->execute([':id' => $id]);

        $deleted = $stmt->rowCount() > 0;
$response->getBody()->write(safeJsonEncode(['success' => $deleted]));
        return $response->withHeader('Content-Type', 'application/json');
    });
};