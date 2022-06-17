<?php

namespace App\Controller\Admin;

use App\Entity\Usuarios;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
//use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UsuariosCrudController extends AbstractCrudController {

    public static function getEntityFqcn(): string {
        return Usuarios::class;
    }

    public function configureFields(string $pageName): iterable {
        //return [
            //yield TextField::new('password')->setFormType(PasswordType::class);
            //IdField::new('id'),
            yield TextField::new('usuario');
            yield ArrayField::new('roles');
            yield TextField::new('password');
            yield EmailField::new('email');
            //TextField::new('imageName'),
            yield Field::new('imageName')->hideOnForm()->setLabel('imagen');
            yield Field::new('imageFile')->setFormType(VichImageType::class)->setLabel('Imagen')->onlyOnForms();
        //];
    }

}
