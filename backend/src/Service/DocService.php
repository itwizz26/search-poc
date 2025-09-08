<?php
namespace App\Service;

use App\Repository\DocRepository;
use Psr\Http\Message\UploadedFileInterface;

class DocService {
    private DocRepository $repo;
    private string $uploadDir;

    public function __construct(DocRepository $repo, string $uploadDir) {
        $this->repo = $repo;
        $this->uploadDir = $uploadDir;
    }

    public function saveFile(UploadedFileInterface $file, string $title): array {
        $filename = uniqid() . '-' . $file->getClientFilename();
        $path = $this->uploadDir . $filename;
        $file->moveTo($path);
        $content = file_get_contents($path);
        return $this->repo->insert($title, $filename, $content);
    }

    public function listAll(): array {
        return $this->repo->all();
    }

    public function search(string $q): array {
        return $this->repo->search($q);
    }
}
