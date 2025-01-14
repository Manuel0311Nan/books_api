<?php

namespace App\Controller\Admin;

use App\Entity\Page;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;

class PageCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Page::class;
    }
    public function configureFields(string $pageName): iterable
    {
        return [
            IntegerField::new('pageNumber', 'Número de página'),
            TextareaField::new('content', 'Contenido'),
            AssociationField::new('book', 'Libro'),
            ArrayField::new('nextOptions', 'Opciones páginas'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setEntityLabelInSingular('Página')
                    ->setEntityLabelInPlural('Páginas')
                    ->setDefaultSort(['pageNumber' => 'ASC']);
    }
}
