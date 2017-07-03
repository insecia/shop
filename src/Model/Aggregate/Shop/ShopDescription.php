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

namespace Insecia\Shop\Model\Aggregate\Shop;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Insecia\Shop\Model\Messaging\Command;
use Insecia\Shop\Model\Messaging\Event;
use Insecia\Shop\Model\Aggregate\Aggregate;

final class ShopDescription implements EventMachineDescription
{
    const IDENTIFIER = 'shopId';

    public static function describe(EventMachine $eventMachine) :void
    {
        self::describeCreateShop($eventMachine);
        self::describeChangeShopData($eventMachine);
    }

    private static function describeCreateShop(EventMachine $eventMachine) :void
    {
        $eventMachine->process(Command::CREATE_SHOP)
            ->withNew(Aggregate::SHOP)
            ->identifiedBy(self::IDENTIFIER)
            ->handle([ShopFunction::class, 'createShop'])
            ->recordThat(Event::SHOP_WAS_CREATED)
            ->apply([ShopFunction::class, 'whenShopWasCreated']);
    }

    private static function describeChangeShopData(EventMachine $eventMachine) :void
    {
        $eventMachine->process(Command::CHANGE_SHOP_DATA)
            ->withExisting(Aggregate::SHOP)
            ->handle([ShopFunction::class, 'changeShopData'])
            ->recordThat(Event::SHOP_DATA_WAS_CHANGED)
            ->apply([ShopFunction::class, 'whenShopDataWasChanged']);
    } 
}
