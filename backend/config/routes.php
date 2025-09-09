<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

// Bootstrap container
/** @var Psr\Container\ContainerInterface $container */
global $container;

// ------------------- DOCUMENTS ROUTES ------------------- //

// List all documents
$app->get('/documents', function (Request $request, Response $response) use ($container) {
    $db = $container->get('db');
    $stmt = $db->query("SELECT id, title FROM documents ORDER BY id DESC");
    $docs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $response->getBody()->write(json_encode($docs));
    return $response->withHeader('Content-Type', 'application/json');
});

// Upload a new document
$app->post('/documents', function (Request $request, Response $response) use ($container) {
    $uploadedFiles = $request->getUploadedFiles();
    if (!isset($uploadedFiles['file'])) {
        $response->getBody()->write(json_encode(['error' => 'No file uploaded']));
        return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
    }

    $file = $uploadedFiles['file'];
    $title = $file->getClientFilename();
    $content = file_get_contents($file->getStream()->getMetadata('uri'));

    $db = $container->get('db');
    $stmt = $db->prepare("INSERT INTO documents (title, content) VALUES (:title, :content)");
    $stmt->execute([':title' => $title, ':content' => $content]);

    $response->getBody()->write(json_encode(['success' => true]));
    return $response->withHeader('Content-Type', 'application/json');
});

// Delete a document by ID
$app->delete('/documents/{id}', function (Request $request, Response $response, $args) use ($container) {
    $id = (int)$args['id'];
    $db = $container->get('db');
    $stmt = $db->prepare("DELETE FROM documents WHERE id = :id");
    $stmt->execute([':id' => $id]);

    $response->getBody()->write(json_encode(['success' => true]));
    return $response->withHeader('Content-Type', 'application/json');
});

// ------------------- SEARCH ROUTE ------------------- //

// Search documents
$app->get('/search', function (Request $request, Response $response) use ($container) {
    $query = $request->getQueryParams()['q'] ?? '';

    $db = $container->get('db');
    $stmt = $db->prepare("SELECT id, title, content FROM documents WHERE content LIKE :q");
    $stmt->execute([':q' => "%$query%"]);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return only snippets
    $results = array_map(fn($r) => [
        'id' => $r['id'],
        'title' => $r['title'],
        'snippet' => substr($r['content'], 0, 200)
    ], $results);

    $response->getBody()->write(json_encode(['results' => $results]));
    return $response->withHeader('Content-Type', 'application/json');
});
