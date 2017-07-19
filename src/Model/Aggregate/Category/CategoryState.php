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

use Insecia\Shop\Infrastructure\ValueObject\ValueObject;

class CategoryState implements ValueObject 
{
    public $categoryId;
    public $parentId;
    public $shopId;
    public $name;
    public $description;
    public $published;
    public $imageId;

    public static function fromArray(array $data) :ValueObject 
    {
        $instance = new self();
        $instance->categoryId = $data['categoryId'];
        $instance->parentId = $data['parentId'];
        $instance->shopId = $data['shopId'];
        $instance->name = $data['name'];
        $instance->description = $data['description'];
        $instance->published = $data['published'] ?? false;
        $instance->imageId = null;
        return $instance;
    }

    public function toArray() :array 
    {
        return (array)$this;
    }
}
