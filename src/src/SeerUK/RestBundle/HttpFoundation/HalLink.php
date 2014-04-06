<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\HttpFoundation;

/**
 * HAL Link Resource
 */
class HalLink
{
    private $href;

    public function __construct($href, $attributes = array())
    {
        $this->setHref($href);
        // $this->setAttributes($attributes);
    }

    public function getHref()
    {
        return $this->href;
    }

    public function setHref($href)
    {
        // Validate URL here?

        $this->href = $href;

        return $this;
    }

    public function toArray()
    {
        return array(
            'href' => $this->href,
        );
    }
}
