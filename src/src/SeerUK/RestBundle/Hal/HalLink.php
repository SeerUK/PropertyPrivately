<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal;

/**
 * HAL Link Resource
 */
class HalLink implements \JsonSerializable
{
    /**
     * @var string
     */
    private $href;

    /**
     * Constructor
     *
     * @param string $href
     * @param array  $attributes
     */
    public function __construct($href, $attributes = array())
    {
        $this->setHref($href);
        // $this->setAttributes($attributes);
    }

    /**
     * Get href
     *
     * @return string
     */
    public function getHref()
    {
        return $this->href;
    }

    /**
     * Set href
     *
     * @param  string $href
     * @return HalLink
     */
    public function setHref($href)
    {
        // Validate URL here?

        $this->href = $href;

        return $this;
    }

    /**
     * Select data to serialise
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        return array(
            'href' => $this->getHref(),
        );
    }
}
