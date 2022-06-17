<?php

namespace App\Form;

use App\Entity\Usuarios;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Vich\UploaderBundle\Form\Type\VichImageType;

class UsuariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('usuario', null,[
                'label' => 'Usuario: ',
                'data' => 'Usuario',
                //'data_class'   =>  'Usuarios'
            ])
            //->add('roles')
            //->add('password')
            //->add('isVerified')
            ->add('email', EmailType::class,[
                'label' => 'Email: ',
                'required' => false,
            ])
            //->add('imagen')
            //->add('imageName')
            //->add('imageSize')
            //->add('updatedAt')
            ->add('imageFile', VichImageType::class,[
                'label' => 'Imagen: ',
            ])
            //->add('Enviar', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
