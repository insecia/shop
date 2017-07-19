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

final class ImageManager implements FileManagerInterface  
{
    private $adapter = null;

    public function __construct(AdapterInterface $adapter) 
    {
        $this->adapter = $adapter;
    }

    public function requestUpload(array $metadata): string 
    {
        return $this->adapter->saveMetadata($metadata);
    }

    public function uploadContent(string $uuid, string $content): void 
    {
        $img = null;

        if($this->adapter->contentExists($uuid)) {
            throw new \InvalidArgumentException(
                'Content already exists and updating it is not supported. Request a new upload'
            );
        }

        try {
            $img = imagecreatefromstring($content);
        } catch(\ErrorException $e) {
            throw new \InvalidArgumentException('File type not supported');
        }

        $imgData = getimagesizefromstring($content);

        $this->adapter->saveContent($uuid, $content);
        $this->adapter->appendToMetadata($uuid, [
            'width' => $imgData[0],
            'height' => $imgData[1],
            'mime' => $imgData['mime']
        ]);
    }
}
