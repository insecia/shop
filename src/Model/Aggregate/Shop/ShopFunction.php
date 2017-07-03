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

use Prooph\Common\Messaging\Message;

final class ShopFunction 
{
    public static function createShop(Message $createShop)
    {
        yield $createShop->payload();
    }

    public static function whenShopWasCreated(Message $shopWasCreated) :ShopState 
    {
        return ShopState::fromArray($shopWasCreated->payload());
    }

    public static function changeShopData(ShopState $shop, Message $changeShopData)
    {
        yield array_merge($shop->toArray(), $changeShopData->payload());
    }

    public static function whenShopDataWasChanged(ShopState $shop, Message $shopDataWasChanged) :ShopState 
    {
        $shop->name = $shopDataWasChanged->payload()['newName'] ?? $shop->name;
        $shop->description = $shopDataWasChanged->payload()['newDescription'] ?? $shop->description;
        $shop->company = $shopDataWasChanged->payload()['newCompany'] ?? $shop->company;
        $shop->street = $shopDataWasChanged->payload()['newStreet'] ?? $shop->street;
        $shop->zipcode = $shopDataWasChanged->payload()['newZipcode'] ?? $shop->zipcode;
        $shop->city = $shopDataWasChanged->payload()['newCity'] ?? $shop->city;
        $shop->country = $shopDataWasChanged->payload()['newCountry'] ?? $shop->country;
        $shop->email = $shopDataWasChanged->payload()['newEmail'] ?? $shop->email;
        return $shop;
    }

    private function __construct() { }
}
