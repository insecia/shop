<?php
declare(strict_types = 1);

return \FastRoute\simpleDispatcher(function(\FastRoute\RouteCollector $r) {
    $r->addRoute(
        ['POST'],
        '/messagebox',
        'eventMachineHttpMessageBox'
    );

    $r->addRoute(
        ['POST'],
        '/messagebox/{message_name:App.AcknowledgeCategoryImageUpload}',
        'fileService'
    );

    $r->addRoute(
        ['POST'],
        '/messagebox/{message_name:[A-Za-z0-9_.-\/]+}',
        'eventMachineHttpMessageBox'
    );

    $r->addRoute(
        ['GET'],
        '/messagebox-schema',
        'eventMachineHttpMessageSchema'
    );

    $r->addRoute(
        ['PUT'],
        '/file-manager/upload/{type:[a-z-]+}/{imageId:[A-Za-z0-9-]{36}}',
        'fileUploadService'
    );
});
