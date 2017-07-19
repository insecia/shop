<?php
/**
 * This file is part of the insecia/shop.
 * (c) 2017-2017 Insecia GmbH <info@insecia.com>
 * (c) 2017-2017 Martin Schilling <martin.schilling@insecia.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code
 */

declare(strict_types = 1);

namespace Insecia\Shop\Infrastructure\FileManager;

use Ramsey\Uuid\Uuid;

final class HardDriveAdapter implements AdapterInterface 
{
    private $basePath = null;

    public function __construct(string $basePath) 
    {
        if(is_file($basePath)) {
            throw new \InvalidArgumentException('Expected path to directory, file given');
        }

        if(!is_dir($basePath)) { 
            mkdir($basePath);
        }

        $this->basePath = rtrim($basePath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
    }

    public function saveMetadata(array $metadata): string 
    {
        if(!$metaJson = json_encode($metadata)) {
            throw new \InvalidArgumentException('Malformed array found');
        }

        $uuid = Uuid::uuid4()->toString();
        $result = file_put_contents($this->basePath . $uuid . '_meta', $metaJson);

        if($result === false) {
            throw new \RuntimeException('Could not save metadata');
        }

        return $uuid;
    }

    public function saveContent(string $uuid, string $content): void 
    {
        $result = file_put_contents($this->basePath . $uuid, $content);

        if($result === false) {
            throw new \RuntimeException('Could not save file content');
        }
    }

    public function appendToMetadata(string $uuid, array $metadata): void 
    {
        $newMetadata = json_encode(array_merge($this->getMetadata($uuid), $metadata));
        $result = file_put_contents($this->basePath . $uuid . '_meta', $newMetadata);

        if($result === false) {
            throw new \RuntimeException('Could not save metadata');
        }
    }

    public function getMetadata(string $uuid): array 
    {
        $data = file_get_contents($this->basePath . $uuid . '_meta');
        
        if($data === false) {
            throw new \RuntimeException('Could not read metadata');
        }

        $metadata = json_decode($data, true);

        if($metadata === null) {
            throw new \RuntimeException('Malformed json found while reading metadata');
        }

        return $metadata;
    }

    public function getContent(string $uuid): string 
    {
        $data = file_get_contents($this->basePath . $uuid);

        if($data === false) {
            throw new \RuntimeException('Could not read file content');
        }

        return $string;
    }

    public function metadataExists(string $uuid): bool 
    {
        return is_file($this->basePath . $uuid . '_meta');
    }

    public function contentExists(string $uuid): bool 
    {
        return is_file($this->basePath . $uuid);
    }

}
