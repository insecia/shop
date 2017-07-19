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
use Interop\Http\ServerMiddleware\DelegateInterface;
use Interop\Http\ServerMiddleware\MiddlewareInterface;
use Prooph\EventMachine\EventMachine;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Diactoros\Response\TextResponse;

final class FileServiceMiddleware implements MiddlewareInterface
{
    private $eventMachine;

    public function __construct(EventMachine $eventMachine)
    {
        $this->eventMachine = $eventMachine;
    }

    public function process(ServerRequestInterface $request, DelegateInterface $delegate)
    {
        $container = include 'config/container.php';
        $imageManager = $container->get('imageManager');

        $payload = json_decode($request->getAttributes()['rawBody'], true)['payload'];
        $uuid = $imageManager->requestUpload($payload['metadata']);

        return $this->eventMachine->httpMessageBox()->process($request, $delegate)->withAddedHeader(
            'Content-Location',
            rtrim(getenv('API_BASE_URL'), '/') . '/file-manager/upload/image/' . $uuid
        );
    }
}
