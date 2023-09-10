<?php

namespace App\Form\Shipment;

use App\Entity\Shipment\Shipment;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShipmentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('owner')
            ->add('billingAddress')
            ->add('originAddress')
            ->add('destinationAddress')
            ->add('items')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Shipment::class,
        ]);
    }
}
