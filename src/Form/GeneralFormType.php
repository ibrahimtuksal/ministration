<?php

namespace App\Form;

use App\Entity\General;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GeneralFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, ['label' => 'Site İsmi'])
            ->add('facebook')
            ->add('twitter')
            ->add('instagram')
            ->add('youtube')
            //->add('categoryName')
            //->add('is_slider')
            ->add('siteUrl')
            ->add('serviceName', null, ['label' => 'Sitenin ne yaptığını kısa ve öz şekilde yazınız'])
            ->add('headCss', TextareaType::class, [
                'required' => false,
                'label' => 'Css kodları buraya eklenmeli',
                'attr' => ['style' => 'height:500px;']
            ])
            ->add('headCode', TextareaType::class, [
                'required' => false,
                'label' => 'Head Etiketi Eklemek istediğiniz JavaScript kodları ( Google Tag Manager Kodları buraya eklenmeli)',
                'attr' => ['style' => 'height:500px;']

            ])
            ->add('is_googletag', null, [
                'required' => false,
                'label' => 'Geri dönüşüm sistemi Aktif/Pasif'])
            ->add('isReturnPhoneForAds', null, [
                'required' => false,
                'label' => 'Reklamdan Girenleri Telefona Gönder Aktif/Pasif'])
            ->add('button', SubmitType::class, [
                'label' => 'Kaydet',
                'row_attr' => ['class' => 'd-grid gap-2']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => General::class,
        ]);
    }
}
