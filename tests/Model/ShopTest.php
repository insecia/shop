<?php
declare(strict_types = 1);

namespace Insecia\ShopTest;

require_once 'vendor/autoload.php';
require_once 'vendor/proophsoftware/event-machine/tests/BasicTestCase.php';

use Insecia\Shop\Model\Messaging\Command;
use Insecia\Shop\Model\Messaging\Event;
use Insecia\Shop\Model\Messaging\MessageDescription;
use Insecia\Shop\Model\Aggregate\Shop\ShopDescription;
use Prooph\EventMachine\Container\EventMachineContainer;
use Prooph\EventMachine\EventMachine;
use Ramsey\Uuid\Uuid;

class ShopTest extends \Prooph\EventMachineTest\BasicTestCase {

    private $eventMachine;

    protected function setUp() {
        $this->eventMachine = new EventMachine();
        $this->eventMachine->load(MessageDescription::class);
        $this->eventMachine->load(ShopDescription::class);
        $this->eventMachine->initialize(new EventMachineContainer($this->eventMachine));
    }

    protected function tearDown()
    {
        $this->eventMachine = null;
    }

    /**
     * @test
     */
    public function it_creates_shop()
    {
        $shopId = Uuid::uuid4()->toString();

        $history = [];
        $this->eventMachine->bootstrapInTestMode($history);

        $createShop = $this->eventMachine->messageFactory()->createMessageFromArray(
            Command::CREATE_SHOP,
            [
                'payload' => [
                    ShopDescription::IDENTIFIER => $shopId,
                    'name' => 'Test shop',
                    'description' => 'Test description',
                    'company' => 'Insecia',
                    'street' => 'Geiselbergstraße 4a',
                    'zipcode' => '14476',
                    'city' => 'Potsdam',
                    'country' => 'DE',
                    'email' => 'martin.schilling@insecia.com'
                ]
            ]
        );

        $this->eventMachine->dispatch($createShop);
        $recordedEvents = $this->eventMachine->popRecordedEventsOfTestSession();
        self::assertCount(1, $recordedEvents);
    }

    /**
     * @test
     */
    public function it_changes_shop_data() {
        $shopId = Uuid::uuid4()->toString();

        $history = [$this->eventMachine->messageFactory()->createMessageFromArray(
            Event::SHOP_WAS_CREATED,
            [
                'payload' => [
                    ShopDescription::IDENTIFIER => $shopId,
                    'name' => 'Test shop',
                    'description' => 'Test description',
                    'company' => 'Insecia',
                    'street' => 'Geiselbergstraße 4a',
                    'zipcode' => '14476',
                    'city' => 'Potsdam',
                    'country' => 'DE',
                    'email' => 'martin.schilling@insecia.com'
                ]
            ]
        )];

        $this->eventMachine->bootstrapInTestMode($history);

        $createShop = $this->eventMachine->messageFactory()->createMessageFromArray(
            Command::CHANGE_SHOP_DATA,
            [
                'payload' => [
                    ShopDescription::IDENTIFIER => $shopId,
                    'newDescription' => 'new test description'
                ]
            ]
        );

        $this->eventMachine->dispatch($createShop);
        $recordedEvents = $this->eventMachine->popRecordedEventsOfTestSession();
        self::assertCount(1, $recordedEvents);
    }
}
