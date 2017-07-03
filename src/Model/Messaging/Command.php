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

final class Command 
{
    const CREATE_SHOP = 'App.CreateShop';
    const CHANGE_SHOP_DATA = 'App.ChangeShopData';

    private function __construct() { }
}
