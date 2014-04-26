<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\PropertyBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Sale Type
 */
class SaleType extends AbstractType
{
    /**
     * @var boolean
     */
    private $isUpdate;

    /**
     * Constructor
     *
     * @param boolean $isUpdate
     */
    public function __construct($isUpdate = false)
    {
        $this->isUpdate = (bool) $isUpdate;
    }

    /**
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('price')
            ->add('enabled');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $validationGroups = ['Default'];

        if ( ! $this->isUpdate) {
            $validationGroups[] = 'POST';
        } else {
            $validationGroups[] = 'PATCH';
        }

        $resolver->setDefaults(array(
            'cascade_validation' => true,
            'csrf_protection'    => false,
            'data_class'         => 'PropertyPrivately\PropertyBundle\Entity\Sale',
            'validation_groups'  => $validationGroups
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pp_property_entity_type_sale';
    }
}
