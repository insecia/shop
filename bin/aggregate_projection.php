<?php
declare(strict_types = 1);

chdir(dirname(__DIR__));

require 'vendor/autoload.php';

/** @var \Psr\Container\ContainerInterface $container */
$container = require 'config/container.php';

/** @var \Prooph\EventMachine\EventMachine $eventMachine */
$eventMachine = $container->get('eventMachine');

$eventMachine->bootstrap();

//Set default handler for all events and overwrite the ones that require different behaviour
$events = Insecia\Shop\Model\Messaging\Event::getEvents();
$handlers = array_fill_keys($events, function ($state, \Prooph\Common\Messaging\Message $event) {
    /** @var \App\Infrastructure\MongoDb\AggregateReadModel $readModel */
    $readModel = $this->readModel();
    $readModel->stack('upsert', $event);
});

$handlers['App.CategoryWasDeleted'] = function ($state, \Prooph\Common\Messaging\Message $event) {
    $readModel = $this->readModel();
    $readModel->stack('deleteAggregate', $event);
};

/** @var \Prooph\EventStore\Projection\ProjectionManager $projectionManager */
$projectionManager = $container->get('projectionManager');

$projection = $projectionManager->createReadModelProjection(
    'aggregate_projection',
    $container->get('aggregateReadModel'),
    [
        \Prooph\EventStore\Projection\ReadModelProjector::OPTION_PERSIST_BLOCK_SIZE => 1
    ]
);

$projection->fromStream('event_stream')
    ->when($handlers)
    ->run();
