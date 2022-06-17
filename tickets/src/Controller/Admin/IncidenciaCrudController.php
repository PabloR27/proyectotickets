<?php

namespace App\Controller\Admin;

use App\Entity\Incidencia;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;

class IncidenciaCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Incidencia::class;
    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            //IdField::new('id'),
            TextField::new('titulo'),
            TextareaField::new('descripcion'),
            TextField::new('gravedad'),
            TextField::new('tipo'),
            TextField::new('localizacion'),
            TextField::new('estado'),
            AssociationField::new('activo'),
            AssociationField::new('usuarioCliente'),
            AssociationField::new('usuarioTecnico'),
             
            DateTimeField::new('fecha'),
        ];
    }
    
}
