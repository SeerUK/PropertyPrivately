<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Entity\Builder;

use PropertyPrivately\CoreBundle\Entity\Builder\EntityBuilderInterface;
use PropertyPrivately\SecurityBundle\Entity\Token;

/**
 * Token Builder
 */
class TokenBuilder implements EntityBuilderInterface
{
    /**
     * @see EntityBuilderInterface::build()
     */
    public function build($elements)
    {
        if ( ! $this->isValidElements($elements)) {
            throw new something();
        }

        $token = new Token();
        $token->setToken($elements['token']);

        return $token;
    }

    /**
     * Check if elements given to build entity are valid
     *
     * @param  array   $elements
     * @return boolean
     */
    public function isValidElements($elements)
    {
        if (empty($elements['token'])) {
            return false;
        }

        return true;
    }
}
