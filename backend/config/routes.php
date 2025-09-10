<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// List all documents
$app->get('/api/documents', function(Request $request, Response $response) use ($container) {
    $db = $container->get('db');
    $stmt = $db->query("SELECT id, title, filename FROM documents ORDER BY id DESC");
    $documents = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response->getBody()->write(json_encode($documents));
    return $response->withHeader('Content-Type', 'application/json');
});

// Search documents
$app->get('/api/search', function(Request $request, Response $response) use ($container) {
    $query = $request->getQueryParams()['q'] ?? '';
    $db = $container->get('db');

    if (!$query) {
        $response->getBody()->write(json_encode([]));
        return $response->withHeader('Content-Type', 'application/json');
    }

    $stmt = $db->prepare("
        SELECT id, title, filename, content
        FROM documents
        WHERE content LIKE :q OR title LIKE :q
        ORDER BY id DESC
    ");
    $stmt->execute([':q' => "%$query%"]);
    $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Highlight query in content snippet
    $results = array_map(function($doc) use ($query) {
        $snippet = substr($doc['content'], 0, 200); // first 200 chars
        $highlighted = str_ireplace($query, "<mark>$query</mark>", $snippet);
        return [
            'id' => $doc['id'],
            'title' => $doc['title'],
            'snippet' => $highlighted
        ];
    }, $docs);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader('Content-Type', 'application/json');
});

// Upload document
$app->post('/api/upload', function(Request $request, Response $response) use ($container) {
    $uploadedFiles = $request->getUploadedFiles();
    $db = $container->get('db');

    if (!isset($uploadedFiles['file'])) {
        $response->getBody()->write(json_encode(['error' => 'No file uploaded']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $file = $uploadedFiles['file'];

    if ($file->getError() !== UPLOAD_ERR_OK) {
        $response->getBody()->write(json_encode(['error' => 'File upload error']));
        return $response->withStatus(500)->withHeader('Content-Type', 'application/json');
    }

    $filename = $file->getClientFilename();
    $title = pathinfo($filename, PATHINFO_FILENAME);
    $contents = $file->getStream()->getContents();

    $stmt = $db->prepare("INSERT INTO documents (title, filename, content) VALUES (:title, :filename, :content)");
    $stmt->execute([
        ':title' => $title,
        ':filename' => $filename,
        ':content' => $contents
    ]);

    $response->getBody()->write(json_encode(['success' => true, 'message' => 'File uploaded']));
    return $response->withHeader('Content-Type', 'application/json');
});