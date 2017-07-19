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

interface AdapterInterface 
{
    public function saveMetadata(array $metadata): string;

    public function saveContent(string $uuid, string $content): void;

    public function appendToMetadata(string $uuid, array $metadata): void;

    public function getMetadata(string $uuid): array;

    public function getContent(string $uuid): string;

    public function metadataExists(string $uuid): bool;

    public function contentExists(string $uuid): bool;
}
