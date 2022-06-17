<?php

namespace App\Controller\Admin;

use App\Entity\Activo;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ActivoCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Activo::class;
    }

    /*
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            TextField::new('title'),
            TextEditorField::new('description'),
        ];
    }
    */
}
