<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Gateway;

use Doctrine\Common\Cache\CacheProvider;
use Doctrine\DBAL\Connection;

/**
 * Abstract Gateway
 */
abstract class AbstractGateway
{
    /**
     * @var Connection
     */
    protected $conn;


    /**
     * Constructor
     *
     * @param Connection    $conn
     * @param CacheProvider $cache
     */
    public function __construct(Connection $conn)
    {
        $this->conn  = $conn;
    }
}
