<?php

declare(strict_types=1);

namespace Framework\Storage;

final readonly class LocalStorage implements StorageInterface
{
    public function __construct(
        private string $storageDir,
        private string $baseUrl,
    ) {
    }

    public function isFileExists(string $filePath): bool
    {
        return file_exists("$this->storageDir/".trim($filePath, '/'));
    }

    public function getLink(string $filePath, ?string $defaultFilePath = null): ?string
    {
        $filePath = $this->isFileExists($filePath) ? $filePath : $defaultFilePath;

        return isset($filePath)
            ? $this->baseUrl.'storage/'.trim($filePath, '/')
            : null;
    }
}
