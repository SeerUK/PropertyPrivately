<?php

/**
 * Property Privately API
 *
 * (c) Elliot Wright, 2014 <wright.elliot@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PropertyPrivately\SecurityBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use PropertyPrivately\SecurityBundle\Form\Type\UserPersonalType;

/**
 * Person Type
 */
class PersonType extends AbstractType
{
    /**
     * @see AbstractType::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('location');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class'      => 'PropertyPrivately\SecurityBundle\Entity\Person',
            'csrf_protection' => false
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'pp_security_entity_type_person';
    }
}
