<?php

declare(strict_types=1);

namespace Framework\Storage;

interface StorageInterface
{
    public function isFileExists(string $filePath): bool;

    public function getLink(string $filePath, ?string $defaultFilePath = null): ?string;
}
