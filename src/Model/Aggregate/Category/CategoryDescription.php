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

namespace Insecia\Shop\Model\Aggregate\Category;

use Prooph\EventMachine\EventMachine;
use Prooph\EventMachine\EventMachineDescription;
use Insecia\Shop\Model\Messaging\Command;
use Insecia\Shop\Model\Messaging\Event;
use Insecia\Shop\Model\Aggregate\Aggregate;

final class CategoryDescription implements EventMachineDescription
{
    const IDENTIFIER = 'categoryId';

    public static function describe(EventMachine $eventMachine) :void 
    {
        self::describeCreateCategory($eventMachine);
        self::describeChangeCategoryData($eventMachine);
        self::describePublishCategory($eventMachine);
        self::describeConceilCategory($eventMachine);
        self::describeAcknowledgeCategoryImageUpload($eventMachine);
        self::describeSetCategoryImage($eventMachine);
    }

    public static function describeCreateCategory(EventMachine $eventMachine) :void 
    {
        $eventMachine->process(Command::CREATE_CATEGORY)
            ->withNew(Aggregate::CATEGORY)
            ->identifiedBy(self::IDENTIFIER)
            ->handle([CategoryFunction::class, 'createCategory'])
            ->recordThat(Event::CATEGORY_WAS_CREATED)
            ->apply([CategoryFunction::class, 'whenCategoryWasCreated']);
    }

    public static function describeChangeCategoryData(EventMachine $eventMachine) :void 
    {
        $eventMachine->process(Command::CHANGE_CATEGORY_DATA)
            ->withExisting(Aggregate::CATEGORY)
            ->handle([CategoryFunction::class, 'changeCategoryData'])
            ->recordThat(Event::CATEGORY_DATA_WAS_CHANGED)
            ->apply([CategoryFunction::class, 'whenCategoryDataWasChanged']);
    }

    public static function describePublishCategory(EventMachine $eventMachine) :void 
    {
        $eventMachine->process(Command::PUBLISH_CATEGORY)
            ->withExisting(Aggregate::CATEGORY)
            ->handle([CategoryFunction::class, 'publishCategory'])
            ->recordThat(Event::CATEGORY_WAS_PUBLISHED)
            ->apply([CategoryFunction::class, 'whenCategoryWasPublished']);
    }

    public static function describeConceilCategory(EventMachine $eventMachine) :void 
    {
        $eventMachine->process(Command::CONCEIL_CATEGORY)
            ->withExisting(Aggregate::CATEGORY)
            ->handle([CategoryFunction::class, 'conceilCategory'])
            ->recordThat(Event::CATEGORY_WAS_CONCEILED)
            ->apply([CategoryFunction::class, 'whenCategoryWasConceiled']);
    }

    public static function describeAcknowledgeCategoryImageUpload(EventMachine $eventMachine) :void
    {
        $eventMachine->process(Command::ACKNOWLEDGE_CATEGORY_IMAGE_UPLOAD)
            ->withExisting(Aggregate::CATEGORY)
            ->handle([CategoryFunction::class, 'acknowledgeCategoryImageUpload'])
            ->recordThat(Event::CATEGORY_IMAGE_UPLOAD_WAS_ACKNOWLEDGED)
            ->apply([CategoryFunction::class, 'whenCategoryImageUploadWasAcknowledged']);
    }

    public static function describeSetCategoryImage(EventMachine $eventMachine) :void 
    {
        $eventMachine->process(Command::SET_CATEGORY_IMAGE)
            ->withExisting(Aggregate::CATEGORY)
            ->handle([CategoryFunction::class, 'setCategoryImage'])
            ->recordThat(Event::CATEGORY_IMAGE_WAS_SET)
            ->apply([CategoryFunction::class, 'whenCategoryImageWasSet']);
    }
}
