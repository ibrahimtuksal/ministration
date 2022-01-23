<?php

namespace App\Form;

use App\Entity\BrandContent;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrandContentFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('content', CKEditorType::class, [
                'label' => 'İçerik',
                'config' => [
                    'uiColor' => '#e2e2ee2',
                    'toolbar' => 'full',
                    'required' => true
                ]
            ])
            //->add('category')
            ->add('button', SubmitType::class, [
                'label' => 'Kaydet',
                'row_attr' => ['class' => 'd-grid gap-2']
            ])
//            ->add('slug')
//            ->add('brand')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => BrandContent::class,
        ]);
    }
}
