<?php

namespace App\Form\Addressing;

use App\Entity\Addressing\UserAddress;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserAddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('phoneNumber')
            ->add('emailAddress')
            ->add('company')
            ->add('countryCode')
            ->add('provinceCode')
            ->add('provinceName')
            ->add('city')
            ->add('street')
            ->add('postcode')
            ->add('updatedAt')
            ->add('createdAt')
            ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserAddress::class,
        ]);
    }
}
