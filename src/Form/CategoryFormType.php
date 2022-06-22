<?php

namespace App\Form;

use App\Entity\Category;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title',null,
                [
                    'label' => 'Başlık'
                ])
            //->add('slug')
            ->add('photo', FileType::class,['required' => false, 'mapped' => false])
            ->add('slider')
            ->add('content', CKEditorType::class, [
                'label' => 'İçerik',
                'config' => [
                    'uiColor' => '#e2e2ee2',
                    'toolbar' => 'full',
                    'required' => true
                ]
            ])
            ->add('button', SubmitType::class, [
                'label' => 'Kaydet',
                'row_attr' => ['class' => 'd-grid gap-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Category::class,
        ]);
    }
}
