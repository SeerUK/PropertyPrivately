<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Gateway;

use PropertyPrivately\CoreBundle\Gateway\AbstractGateway;

/**
 * Token Gateway
 */
class TokenGateway extends AbstractGateway
{
    /**
     * Remove ALL tokens for the given user id
     *
     * @param  interger $id
     * @return boolean
     */
    public function removeAllByUserId($id)
    {
        $sql = "
            DELETE
                t
            FROM
                Token t
            INNER JOIN
                User u ON u.id = t.userId
            WHERE
                u.id = :id;
        ";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue('id', $id);

        return $stmt->execute();
    }
}
