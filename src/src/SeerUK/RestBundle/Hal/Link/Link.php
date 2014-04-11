<?php

/**
 * Seer UK REST Bundle
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Hal\Link;

use SeerUK\RestBundle\Hal\Resource\ResourceInterface;

/**
 * HAL Link Resource
 */
class Link implements \JsonSerializable, ResourceInterface
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var boolean
     */
    private $templated;

    /**
     * @var string
     */
    private $type;

    /**
     * @var string
     */
    private $deprecation;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $profile;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $hreflang;

    /**
     * Constructor
     *
     * @param string $href
     * @param array  $attributes
     */
    public function __construct($href)
    {
        $this->setHref($href);
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
     * @return Link
     */
    public function setHref($href)
    {
        $this->href = $href;

        return $this;
    }

    /**
     * Get templated
     *
     * @return boolean
     */
    public function getTemplated()
    {
        return (bool) $this->templated;
    }

    /**
     * Set templated
     *
     * @param  boolean $templated
     * @return Link
     */
    public function setTemplated($templated)
    {
        $this->templated = (bool) $templated;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type
     *
     * @param  string $type
     * @return Link
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get deprecation
     *
     * @return string
     */
    public function getDeprecation()
    {
        return $this->deprecation;
    }

    /**
     * Set deprecation
     *
     * @param  string $deprecation
     * @return Link
     */
    public function setDeprecation($deprecation)
    {
        $this->deprecation = $deprecation;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name
     *
     * @param  string $name
     * @return Link
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get profile
     *
     * @return string
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set profile
     *
     * @param  string $profile
     * @return Link
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param  string $title
     * @return Link
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get hreflang
     *
     * @return string
     */
    public function getHreflang()
    {
        return $this->hreflang;
    }

    /**
     * Set hreflang
     *
     * @param  string $hreflang
     * @return Link
     */
    public function setHreflang($hreflang)
    {
        $this->hreflang = $hreflang;

        return $this;
    }

    /**
     * Select data to serialise
     *
     * @return mixed
     */
    public function jsonSerialize()
    {
        $link = array();

        if ($href = $this->getHref()) {
            $link['href'] = $href;
        }

        if ($templated = $this->getTemplated()) {
            $link['templated'] = $templated;
        }

        if ($type = $this->getType()) {
            $link['type'] = $type;
        }

        if ($deprecation = $this->getDeprecation()) {
            $link['deprecation'] = $deprecation;
        }

        if ($name = $this->getName()) {
            $link['name'] = $name;
        }

        if ($profile = $this->getProfile()) {
            $link['profile'] = $profile;
        }

        if ($title = $this->getTitle()) {
            $link['title'] = $title;
        }

        if ($hreflang = $this->getHreflang()) {
            $link['hreflang'] = $hreflang;
        }

        return $link;
    }
}
