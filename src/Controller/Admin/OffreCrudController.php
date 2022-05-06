<?php

namespace App\Controller\Admin;

use App\Entity\Offre;
use App\Form\OffreType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use Vich\UploaderBundle\Form\Type\VichImageType;


class OffreCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Offre::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('titre'),
            TextareaField ::new('description'),
            TextField::new('prix'),
            // ImageField::new('ImageName')->setBasePath('/uploads')->OnlyOnIndex(),
            // TextareaField::new('imageFile')->setFormType(VichImageType::class)
            ImageField::new('ImageName')
                        ->setBasePath('/uploads')
                        ->setLabel('L\'image du plat')
                        ->setUploadDir('/public/uploads')
                        ->setUploadedFilenamePattern('[randomhash].[extension]')
                        ->setRequired(false),
            
        ];
    }
}