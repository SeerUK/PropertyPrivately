<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Entity\Builder;

/**
 * Entity Builder Interface
 */
interface EntityBuilderInterface
{
    /**
     * Build an entity
     *
     * @param  array|object $elements
     * @return mixed
     */
    public function build($elements);
}
