<?php

namespace App\Controller\Admin;

use App\Entity\Registro;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;

class RegistroCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Registro::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        
            yield IdField::new('id');
            yield TextField::new('texto');
            yield AssociationField::new('incidencia');
            yield AssociationField::new('usuario');
            yield TextField::new('gravedad');
            yield TextField::new('cambioestado');
            yield DateTimeField::new('fecha');
        
    }
    
}
