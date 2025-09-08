<?php
namespace App\Controller;

use App\Service\DocService;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DocController {
    private DocService $service;

    public function __construct(DocService $service) {
        $this->service = $service;
    }

    public function upload(Request $req, Response $res): Response {
        $files = $req->getUploadedFiles();
        if (!isset($files['file'])) {
            $res->getBody()->write(json_encode(['error' => 'No file uploaded']));
            return $res->withStatus(400)->withHeader('Content-Type', 'application/json');
        }
        $title = $req->getParsedBody()['title'] ?? '';
        $doc = $this->service->saveFile($files['file'], $title);
        $res->getBody()->write(json_encode($doc));
        return $res->withHeader('Content-Type', 'application/json');
    }

    public function list(Request $req, Response $res): Response {
        $docs = $this->service->listAll();
        $res->getBody()->write(json_encode($docs));
        return $res->withHeader('Content-Type', 'application/json');
    }

    public function search(Request $req, Response $res): Response {
        $q = $req->getQueryParams()['q'] ?? '';
        $docs = $this->service->search($q);
        $res->getBody()->write(json_encode($docs));
        return $res->withHeader('Content-Type', 'application/json');
    }
}
