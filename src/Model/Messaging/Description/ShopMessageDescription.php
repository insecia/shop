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

namespace Insecia\Shop\Model\Messaging\Description;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Prooph\EventMachine\JsonSchema\JsonSchema;
use Insecia\Shop\Model\Aggregate\Shop\ShopDescription;
use Insecia\Shop\Model\Messaging\Command;
use Insecia\Shop\Model\Messaging\Event;

final class ShopMessageDescription implements EventMachineDescription
{
    const PROPERTY_CONFIG = [
        'shopId' => [
            'type' => 'string',
            'pattern' => '^[A-Za-z0-9-]{36}$'
        ],
        'name' => [
            'type' => 'string',
            'minLength' => 1
        ],
        'description' => [
            'type' => 'string',
            'minLength' => 1
        ],
        'company' => [
            'type' => 'string',
            'minLength' => 1
        ],
        'street' => [
            'type' => 'string',
            'minLength' => 1
        ],
        'zipcode' => [
            'type' => 'string',
            'minLength' => 1
        ],
        'city' => [
            'type' => 'string',
            'minLength' => 1
        ],
        'country' => [
            'type' => 'string',
            'pattern' => '^[A-Z]{2}$'
        ],
        'email' => [
            'type' => 'string',
            'format' => 'email'
        ]
    ];

    public static function describe(EventMachine $eventMachine) :void
    {
        $createShopConfig = JsonSchema::object(self::PROPERTY_CONFIG);
        $eventMachine->registerCommand(Command::CREATE_SHOP, $createShopConfig);
        $eventMachine->registerEvent(Event::SHOP_WAS_CREATED, $createShopConfig);

        $changeShopDataConfig = JsonSchema::object(
            [
                'shopId' => self::PROPERTY_CONFIG['shopId']
            ], [
                'newName' => self::PROPERTY_CONFIG['name'],
                'newDescription' => self::PROPERTY_CONFIG['description'],
                'newCompany' => self::PROPERTY_CONFIG['company'],
                'newStreet' => self::PROPERTY_CONFIG['street'],
                'newZipcode' => self::PROPERTY_CONFIG['zipcode'],
                'newCity' => self::PROPERTY_CONFIG['city'],
                'newCountry' => self::PROPERTY_CONFIG['country'],
                'newEmail' => self::PROPERTY_CONFIG['email']
            ]
        );
        $eventMachine->registerCommand(Command::CHANGE_SHOP_DATA, $changeShopDataConfig);
        $eventMachine->registerEvent(Event::SHOP_DATA_WAS_CHANGED, $changeShopDataConfig);
    }
}
