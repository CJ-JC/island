<?php

namespace App\Form;

use App\Entity\Offre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OffreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void 
    {
        $builder
            ->add('titre', TextType::class, [
                'label' => 'Titre du plat',
            ])
            ->add('description', TextType::class, [
                'label' => 'Description du plat',
            ])
            ->add('prix', TextType::class, [
                'label' => 'Prix du plat',
            ])
            ->add('imageFile', VichFileType::class,[
                'label' => 'Image'
            ])

            ->add('save', SubmitType::class, [
                'label' => 'Créer l\'offre'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offre::class,
        ]);
    }
}