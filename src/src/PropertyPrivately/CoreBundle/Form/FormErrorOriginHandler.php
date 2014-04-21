<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\CoreBundle\Form;

use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormErrorIterator;

/**
 * Form Error Origin Handler
 */
class FormErrorOriginHandler
{
    /**
     * Decorates form errors with appropriate origins
     *
     * @param FormInterface $form
     */
    public function decorate(FormInterface $form)
    {
        foreach ($form->getErrors(true, false) as $child) {
            if ($child instanceof FormErrorIterator) {
                $this->decorate($child->getForm());
            } else {
                $child->setOrigin($form);
            }
        }
    }
}
