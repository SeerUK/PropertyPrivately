<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Exception\Utils;

/**
 * Error Messages
 */
final class ErrorMessages
{
    const IMAGE_NOT_FOUND       = 'Image not found.';
    const OFFER_NOT_FOUND       = 'Offer not found.';
    const PROPERTY_NOT_FOUND    = 'Property not found.';
    const SALE_NOT_FOUND        = 'Sale not found.';
    const SALE_CREATE_CONFLICT  = 'You may only have one [potentially] active sale at a time. If you wish to create another, you must first delete your [potentially] active sale, or instead edit it.';
    const SALE_UPDATE_INACTIVE  = 'You may only edit active sales.';
    const OFFER_CREATE_CONFLICT = 'You can not make an offer on your own sale.';
}
