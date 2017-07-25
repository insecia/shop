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

use Prooph\Common\Messaging\Message;

final class CategoryFunction 
{
    public static function createCategory(Message $createCategory) 
    {
        yield $createCategory->payload();
    }

    public static function whenCategoryWasCreated(Message $categoryWasCreated): CategoryState 
    {
        return CategoryState::fromArray($categoryWasCreated->payload());
    }

    public static function changeCategoryData(CategoryState $category, Message $changeCategoryData) 
    {
        yield $changeCategoryData->payload();
    }

    public static function whenCategoryDataWasChanged(CategoryState $category, Message $categoryDataWasChanged): CategoryState 
    {
        $category->name = $categoryDataWasChanged->payload()['newName'] ?? $category->name;
        $category->description = $categoryDataWasChanged->payload()['newDescription'] ?? $category->description;
        return $category;
    }

    public static function publishCategory(CategoryState $category, Message $publishCategory) 
    {
        yield $publishCategory->payload();
    }

    public static function whenCategoryWasPublished(CategoryState $category, Message $categoryWasPublished): CategoryState 
    {
        $category->published = true;
        return $category;
    }

    public static function conceilCategory(CategoryState $category, Message $conceilCategory) 
    {
        yield $conceilCategory->payload();
    }

    public static function whenCategoryWasConceiled(CategoryState $category, Message $categoryWasConceiled): CategoryState 
    {
        $category->published = false;
        return $category;
    }

    public static function deleteCategory(CategoryState $category, Message $deleteCategory) 
    {
        yield $deleteCategory->payload();
    }

    public static function whenCategoryWasDeleted(CategoryState $category, Message $categoryWasDeleted): CategoryState 
    {
        return $category;
    }

    public static function acknowledgeCategoryImageUpload(
        CategoryState $category, 
        Message $acknowledgeCategoryImageUpload
    ) {
        yield $acknowledgeCategoryImageUpload->payload();
    }

    public static function whenCategoryImageUploadWasAcknowledged(
        CategoryState $category, 
        Message $categoryUploadWasAcknowledged
    ): CategoryState {
        return $category;
    }

    public static function setCategoryImage(CategoryState $category, Message $setCategoryImage) 
    {
        yield $setCategoryImage->payload();
    }

    public static function whenCategoryImageWasSet(CategoryState $category, Message $categoryImageWasSet): CategoryState 
    {
        $category->imageId = $categoryImageWasSet->payload()['imageId'];
        return $category;
    }

    private function __construct() { }
}
