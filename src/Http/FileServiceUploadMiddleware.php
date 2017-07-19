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

namespace Insecia\Shop\Http;

use Insecia\Shop\Infrastructure\FileManager\ImageManager;
use Insecia\Shop\Infrastructure\FileManager\FileManagerInterface;
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Prooph\EventMachine\EventMachine;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\TextResponse;

final class FileServiceUploadMiddleware implements MiddlewareInterface
{
    private $eventMachine;

    public function __construct(EventMachine $eventMachine)
    {
        $this->eventMachine = $eventMachine;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $container = include 'config/container.php';
        $type = $request->getAttribute('type');

        switch($type) {
            case 'image': 
                $manager = $container->get('imageManager');
                $identifier = 'imageId';
                $id = $request->getAttribute($identifier);
                break;
            default:
                return new \Zend\Diactoros\Response\EmptyResponse(415, [
                    'Accept' => 'image'
                ]);
        }

        $handle = fopen('php://input', 'r');
        $rawContent = '';
        while($chunk = fread($handle, 1024)) {
            $rawContent .= $chunk;
        }

        if(!$manager instanceof FileManagerInterface) {
            return new \Zend\Diactoros\Response\EmptyResponse(500);
        }

        $manager->uploadContent($id, $rawContent);

        return new \Zend\Diactoros\Response\JsonResponse([
            $identifier => $id
        ], 202);
    }
}
