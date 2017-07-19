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

final class Event 
{
    const SHOP_WAS_CREATED = 'App.ShopWasCreated';
    const SHOP_DATA_WAS_CHANGED = 'App.ShopDataWasChanged';

    const CATEGORY_WAS_CREATED = 'App.CategoryWasCreated';
    const CATEGORY_DATA_WAS_CHANGED = 'App.CategoryDataWasChanged';
    const CATEGORY_WAS_PUBLISHED = 'App.CategoryWasPublished';
    const CATEGORY_WAS_CONCEILED = 'App.CategoryWasConceiled';

    const CATEGORY_IMAGE_UPLOAD_WAS_ACKNOWLEDGED = 'App.CategoryImageUploadWasAcknowledged';
    const CATEGORY_IMAGE_WAS_SET = 'App.CategoryImageWasSet';

    private function __construct() { }
}
