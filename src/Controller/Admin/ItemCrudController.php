<?php

namespace App\Controller\Admin;

use App\Entity\Item;
use App\Enum\ItemType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextAreaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ItemCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Item::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            ChoiceField::new('type')
            ->setChoices([
                'Runa' => ItemType::RUNA,
                'Objeto Mágico' => ItemType::OBJETOMAGICO,
                'Libro de Poder' => ItemType::LIBROPODERES,
            ]),
            TextField::new('name'),
            TextField::new('power'),
            TextareaField::new('image'),
            DateTimeField::new('createdAt')->onlyOnIndex(),
        ];
    }
}
