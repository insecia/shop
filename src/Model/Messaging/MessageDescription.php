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

namespace Insecia\Shop\Model\Messaging;

use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\EventMachine;
use Insecia\Shop\Model\Messaging\Description\ShopMessageDescription;
use Insecia\Shop\Model\Messaging\Description\CategoryMessageDescription;

final class MessageDescription implements EventMachineDescription 
{
    public static function describe(EventMachine $eventMachine) :void 
    {
        ShopMessageDescription::describe($eventMachine);
        CategoryMessageDescription::describe($eventMachine);
    }
}
