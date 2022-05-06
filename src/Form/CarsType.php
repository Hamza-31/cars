<?php

namespace App\Form;

use App\Entity\Cars;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CarsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('model')
            ->add('brand')
            ->add('price')
            ->add('releaseYear')
            ->add('mileage')
            ->add('fuel')
            ->add('drivingLicense')
            ->add('picture', FileType::class,['label'=>'Image','required'=>false,
                'constraints'=>[new File([
                    'maxSize'=>'1024k',
                    'maxSizeMessage'=>"Max File size is 1024ko",
                    'mimeTypes'=>['image/jpeg', 'image/png'],'mimeTypesMessage'=>'File Type JPEG or PNG'])]]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cars::class,
        ]);
    }
}
