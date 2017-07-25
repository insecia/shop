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

final class CategoryMessageDescription implements EventMachineDescription {
    const PROPERTY_CONFIG = [
        'categoryId' => [
            'type' => 'string',
            'pattern' => '^[A-Za-z0-9-]{36}$'
        ],
        'parentId' => [
            'type' => [
                'string', 
                'null'
            ],
            'pattern' => '^[A-Za-z0-9-]{36}$'
        ],
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
        ]
    ];

    public static function describe(EventMachine $eventMachine) :void 
    {
        $createCategoryConfig = JsonSchema::object(self::PROPERTY_CONFIG, [
            'published' => [
                'type' => 'boolean'
            ]
        ]);

        $changeCategoryDataConfig = JsonSchema::object(            
            [
                'categoryId' => self::PROPERTY_CONFIG['categoryId']
            ], [
                'newName' => self::PROPERTY_CONFIG['name'],
                'newDescription' => self::PROPERTY_CONFIG['description']
            ]
        );

        $categoryIdConfig = JsonSchema::object([
            'categoryId' => self::PROPERTY_CONFIG['categoryId']
        ]);

        $categoryAckImageUploadConfig = JsonSchema::object(
            ['categoryId' => self::PROPERTY_CONFIG['categoryId']], 
            [], 
            true
        );

        $setCategoryImageConfig = JsonSchema::object([
            'categoryId' => self::PROPERTY_CONFIG['categoryId'],
            'imageId' => [
                'type' => 'string',
                'pattern' => '^[A-Za-z0-9-]{36}$'
            ]
        ]);

        $eventMachine->registerCommand(Command::CREATE_CATEGORY, $createCategoryConfig);
        $eventMachine->registerEvent(Event::CATEGORY_WAS_CREATED, $createCategoryConfig);

        $eventMachine->registerCommand(Command::CHANGE_CATEGORY_DATA, $changeCategoryDataConfig);
        $eventMachine->registerEvent(Event::CATEGORY_DATA_WAS_CHANGED, $changeCategoryDataConfig);

        $eventMachine->registerCommand(Command::PUBLISH_CATEGORY, $categoryIdConfig);
        $eventMachine->registerEvent(Event::CATEGORY_WAS_PUBLISHED, $categoryIdConfig);

        $eventMachine->registerCommand(Command::CONCEIL_CATEGORY, $categoryIdConfig);
        $eventMachine->registerEvent(Event::CATEGORY_WAS_CONCEILED, $categoryIdConfig);

        $eventMachine->registerCommand(Command::DELETE_CATEGORY, $categoryIdConfig);
        $eventMachine->registerEvent(Event::CATEGORY_WAS_DELETED, $categoryIdConfig);

        $eventMachine->registerCommand(Command::ACKNOWLEDGE_CATEGORY_IMAGE_UPLOAD, $categoryAckImageUploadConfig);
        $eventMachine->registerEvent(Event::CATEGORY_IMAGE_UPLOAD_WAS_ACKNOWLEDGED, $categoryAckImageUploadConfig);

        $eventMachine->registerCommand(Command::SET_CATEGORY_IMAGE, $setCategoryImageConfig);
        $eventMachine->registerEvent(Event::CATEGORY_IMAGE_WAS_SET, $setCategoryImageConfig);
    }
}
