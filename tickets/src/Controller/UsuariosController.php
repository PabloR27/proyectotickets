<?php

namespace App\Controller;

use App\Entity\Usuarios;
use App\Form\UsuariosType;
use App\Repository\UsuariosRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;


/**
 * @Route("/usuarios")
 */
class UsuariosController extends AbstractController {

    /**
     * @Route("/", name="app_usuarios_index", methods={"GET"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function index(UsuariosRepository $usuariosRepository): Response {
        return $this->render('usuarios/index.html.twig', [
                    'usuarios' => $usuariosRepository->findAll(),
        ]);
    }

    /**
     * @Route("/miUsuario", name="miUsuario", methods={"GET"})
     */
    public function miUsuario(UsuariosRepository $usuariosRepository, ManagerRegistry $doctrine): Response {
        $usuario = $this->getUser();
        //$usuarioId= $usuario->getId();
        //$activos = $doctrine->getRepository(\App\Entity\Activo::class)->findAll();

        return $this->render('detallesusuario/index.html.twig', [
                    //'usuarios' => $usuariosRepository->find($usuario),
                    'usuario' => $usuario,
        ]);
    }

    /**
     * @Route("/new", name="app_usuarios_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UsuariosRepository $usuariosRepository): Response {
        $usuario = new Usuarios();
        $form = $this->createForm(UsuariosType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuariosRepository->add($usuario);
            return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usuarios/new.html.twig', [
                    'usuario' => $usuario,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_usuarios_show", methods={"GET"})
     */
    public function show(Usuarios $usuario): Response {
        return $this->render('usuarios/show.html.twig', [
                    'usuario' => $usuario,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_usuarios_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Usuarios $usuario, UsuariosRepository $usuariosRepository): Response {
        $form = $this->createForm(UsuariosType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuariosRepository->add($usuario);
            return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('usuarios/edit.html.twig', [
                    'usuario' => $usuario,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_usuarios_delete", methods={"POST"})
     */
    public function delete(Request $request, Usuarios $usuario, UsuariosRepository $usuariosRepository): Response {
        if ($this->isCsrfTokenValid('delete' . $usuario->getId(), $request->request->get('_token'))) {
            $usuariosRepository->remove($usuario);
        }

        return $this->redirectToRoute('app_usuarios_index', [], Response::HTTP_SEE_OTHER);
    }

}
