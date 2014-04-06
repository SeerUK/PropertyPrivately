<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace SeerUK\RestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\ConstraintViolation;
use SeerUK\RestBundle\HttpFoundation\HalJsonResponse;
use SeerUK\RestBundle\Validator\Exception\ConstraintViolationException;

/**
 * Test Controller
 */
class TestController extends Controller
{
    public function testAction()
    {
        try {
            throw new \Exception('This is a test, this exception was thrown and caught before the constraint exception!');
        } catch (\Exception $e) {
            // Catch previous exception, but maintain the fact that it was there, and throw new one:
            throw new ConstraintViolationException(
                new ConstraintViolationList(array(
                    new ConstraintViolation('That was not found!', 'Not sure what this means', array('or', 'this'), 'destination', 'a path', '23', 1, 404),
                    new ConstraintViolation('Bad data.', 'Not sure what this means', array('or', 'this'), 'date', 'a path', '23', 1, 400),
                    new ConstraintViolation('Bad data.', 'Not sure what this means', array('or', 'this'), 'modified', 'a path', '23', 1, 400),
                )),
                $e
            );
        }

        return new HalJsonResponse(array('oops' => 'heh'));
    }
}
