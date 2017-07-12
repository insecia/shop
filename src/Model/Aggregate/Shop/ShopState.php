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

use Insecia\Shop\Infrastructure\ValueObject\ValueObject;

class ShopState implements ValueObject 
{
    public $shopId;
    public $name;
    public $description;
    public $company;
    public $street;
    public $zipcode;
    public $city;
    public $country;
    public $email;

    public static function fromArray(array $data) :ValueObject
    {
        $instance = new self();
        $instance->shopId = $data['shopId'];
        $instance->name = $data['name'];
        $instance->description = $data['description'];
        $instance->company = $data['company'];
        $instance->street = $data['street'];
        $instance->zipcode = $data['zipcode'];
        $instance->city = $data['city'];
        $instance->country = $data['country'];
        $instance->email = $data['email'];
        return $instance;
    }

    public function toArray() :array 
    {
        return (array)$this;
    }
}
