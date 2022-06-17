<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Entity\Activo;
use App\Entity\Incidencia;
use App\Entity\Usuarios;
use App\Entity\Registro;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Vich\UploaderBundle\VichUploaderBundle;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Form\DatosUsuarioType;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Algolia\SearchBundle\SearchService;
use Algolia\SearchBundle\IndexManagerInterface;

/**
 * @IsGranted("ROLE_USER")
 */
class PrincipalController extends AbstractController {

    //protected $searchService;

    public function search(Request $request, IndexManagerInterface $indexManager) {
        $em = $this->getDoctrine()->getManagerForClass(Activo::class);
        $posts = $indexManager->search($request->get('s'), Project::class, $em);
        //$posts = $this->$indexManager->rawSearch('query',Post::class);
    }

    /**
     * @Route("/principal", name="principal")
     */
    public function index(ManagerRegistry $doctrine): Response {
        $numIncidenciasConfirmar = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['usuarioCliente' => $this->getUser(), 'estado' => 'Finalizada sin confirmar']);

        $numIncidenciasConfirmar = count($numIncidenciasConfirmar);
        return $this->render('principal/index.html.twig', [
                    'incidenciasPorConfirmar' => $numIncidenciasConfirmar,
                    'controller_name' => 'PrincipalController',
        ]);
    }

    /**
     * @Route("/altaincidencia", name="altaincidencia")
     */
    public function altaincidencia(Request $request, ManagerRegistry $doctrine): Response {

        $repository = $doctrine->getRepository(\App\Entity\Activo::class);
        $activos = $repository->findAll();

        return $this->render('altaincidencia/index.html.twig', [
                    'controller_name' => 'FamiliaController',
                    'activos' => $activos
        ]);
    }

    /**
     * @Route("/pedido", name="pedido")
     */
    public function pedido(Request $request, MailerInterface $mailer, ManagerRegistry $doctrine, ValidatorInterface $validator): Response {


        $gravedad = $request->request->get('gravedad');
        $descripcion = $request->request->get('descripcion');
        $titulo = $request->request->get('titulo');
        $activo = $request->request->get('activo');
        $tipo = $request->request->get('tipo');
        $localizacion = $request->request->get('localizacion');
        $estado = $request->request->get('estado');
        //$fecha=date("Y-m-d");


        $correcto = true;
        try {

            $entityManager = $doctrine->getManager();
            $incidencia = new Incidencia();
            $incidencia->setGravedad($gravedad);
            $incidencia->setDescripcion($descripcion);
            $incidencia->setTitulo($titulo);
            $incidencia->setTipo($tipo);
            $incidencia->setLocalizacion($localizacion);
            $incidencia->setFecha(\DateTime::createFromFormat('Y-m-d H:i:s', date("Y-m-d H:i:s")));
            $incidencia->setActivo($doctrine->getRepository(\App\Entity\Activo::class)->find($activo));
            $incidencia->setUsuarioCliente($this->getUser());
            $incidencia->setEstado($estado);
            /*
              $errors=$validator->validate($incidencia);

              if(count($errors)>0){
              $errorsString=(string) $errors;
              return new Response($errorsString);
              } */
            $entityManager->persist($incidencia);
            $entityManager->flush();

            $emailUsuario = $this->getUser()->getEmail();

            $email = (new TemplatedEmail())
                    ->from('promisa449@iesmartinezm.es')
                    ->to("$emailUsuario")
                    ->subject('Incidencia dada de alta')
                    ->htmlTemplate('correo/index.html.twig')
                    ->context(['numPedido' => $incidencia->getId(), 'usuario' => $this->getUser()->getUsername(), 'incidencia' => $incidencia]);
            $mailer->send($email);

            if ($gravedad == "critica") {


                $email2 = (new TemplatedEmail())
                        ->from('promisa449@iesmartinezm.es')
                        ->to('promisa449@iesmartinezm.es')
                        ->subject('Incidencia critica')
                        ->htmlTemplate('correo/incidenciaCritica.html.twig')
                        ->context(['numPedido' => $incidencia->getId(), 'usuario' => $this->getUser()->getUsername(), 'incidencia' => $incidencia]);
                $mailer->send($email2);
            }
        } catch (Exception $exc) {
            $correcto = false;
        }
        $id = $incidencia->getId();
        /*
        $usuarioVisualizando = $this->getUser();
        
        $registrosAMostrar = $doctrine->getRepository(\App\Entity\Registro::class)->findBy(['incidencia' => $id]);
        $incidenciaAMostrar = $doctrine->getRepository(\App\Entity\Incidencia::class)->find($id);
        $activoAMostrar = $incidencia->getActivo();
        */
         return $this->redirectToRoute("detalleincidencia", array(
         
            'id'=>$id,
            ));
        /*return $this->render(detalleincidencia/index.html.twig', [
                    'incidencia' => $incidenciaAMostrar,
                    'activo' => $activoAMostrar,
                    'registros' => $registrosAMostrar,
                    'usuarioVisualizando' => $usuarioVisualizando
        ]);*/

        /*
          return $this->render('pedido/index.html.twig', array('codPedido' => $incidencia->getId(),
          'confirmado' => $correcto));

         */
    }

    /**
     * @Route("/misincidencias", name="misincidencias")
     */
    public function misincidencias(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['usuarioCliente' => $this->getUser()], ['fecha' => 'DESC']);

        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/detalleincidencia/{id}", name="detalleincidencia")
     */
    public function detalleincidencia(Request $request, ManagerRegistry $doctrine, int $id): Response {
        $registros = $doctrine->getRepository(\App\Entity\Registro::class)->findBy(['incidencia' => $id]);
        $incidencia = $doctrine->getRepository(\App\Entity\Incidencia::class)->find($id);
        $activo = $incidencia->getActivo();
        $usuarioVisualizando = $this->getUser();
        return $this->render('detalleincidencia/index.html.twig', [
                    'incidencia' => $incidencia,
                    'activo' => $activo,
                    'registros' => $registros,
                    'usuarioVisualizando' => $usuarioVisualizando,
        ]);
    }

    /**
     * @Route("/detalleactivo/{id}", name="detalleactivo")
     */
    public function detalleactivo(Request $request, ManagerRegistry $doctrine, int $id): Response {

        $activo = $doctrine->getRepository(\App\Entity\Activo::class)->find($id);
        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['activo' => $id], ['fecha' => 'DESC']);

        return $this->render('detalleactivo/index.html.twig', [
                    'activo' => $activo,
                    'incidencias' => $incidencias
        ]);
    }

    /**
     * @Route("/incidenciassinasignar", name="incidenciassinasignar")
     * @IsGranted("ROLE_TECNICO")
     */
    public function incidenciassinasignar(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['estado' => 'Sin asignar']);

        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/incidenciasenproceso", name="incidenciasenproceso")
     * @IsGranted("ROLE_TECNICO")
     */
    public function incidenciasenproceso(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['estado' => 'En proceso']);
        /* if (!$incidencias) {
          throw $this->createNotFoundException('No hay incidencias en proceso');
          } else { */
        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
        /* } */
    }

    /**
     * @Route("/incidenciasfinalizadassinconfirmar", name="incidenciasfinalizadassinconfirmar")
     * @IsGranted("ROLE_TECNICO")
     */
    public function incidenciasfinalizadassinconfirmar(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['estado' => 'Finalizada sin confirmar']);

        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/incidenciasfinalizadas", name="incidenciasfinalizadas")
     * @IsGranted("ROLE_TECNICO")
     */
    public function incidenciasfinalizadas(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['estado' => 'Finalizada']);

        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/incidenciasasignadasami", name="incidenciasasignadasami")
     * @IsGranted("ROLE_TECNICO")
     */
    public function incidenciasasignadasami(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['usuarioTecnico' => $this->getUser()]);

        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/anadirregistro/{id}", name="anadirregistro")
     */
    public function anadirregistro(Request $request, ManagerRegistry $doctrine, int $id): Response {

        $incidencia = $doctrine->getRepository(\App\Entity\Incidencia::class)->find($id);
        $entityManager = $doctrine->getManager();
        $texto = $request->request->get('texto');
        $gravedad = $request->request->get('gravedad');
        $registro = new Registro();
        $registro->setTexto($texto);
        $registro->setFecha(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $registro->setIncidencia($incidencia);
        $registro->setUsuario($this->getUser());
        $registro->setGravedad($gravedad);
        $entityManager->persist($registro);
        echo $gravedad;
        if ($gravedad != "") {
            $entityManager->persist($incidencia);
            $incidencia->setGravedad($gravedad);
        }
        $entityManager->flush();
        $ruta = $request->headers->get('referer');
        return $this->redirect($ruta);
    }

    /**
     * @Route("/modificarestadoincidencia/{id}", name="modificarestadoincidencia")
     */
    public function modificarestadoincidencia(Request $request, ManagerRegistry $doctrine, int $id, MailerInterface $mailer): Response {

        $entityManager = $doctrine->getManager();
        $incidencia = $doctrine->getRepository(\App\Entity\Incidencia::class)->find($id);

        $estado = $incidencia->getEstado();
        $registro = new Registro();
        if ($estado === 'Sin asignar') {
            $incidencia->setUsuarioTecnico($this->getUser());
            $incidencia->setEstado('En proceso');
            $registro->setCambioestado('En proceso');
        } else if ($estado === 'En proceso') {
            $incidencia->setEstado('Finalizada sin confirmar');
            $registro->setCambioestado('Finalizada sin confirmar');

            $usuario = $incidencia->getUsuarioCliente();
            $emailUsuario = $usuario->getEmail();

            //$emailUsuario = $incidencia->getUsuarioCliente()->getEmail();
            //$emailUsuario = $emailUsuario->getEmail();
            $email = (new TemplatedEmail())
                    ->from('promisa449@iesmartinezm.es')
                    ->to("$emailUsuario")
                    ->subject('Incidencia resuelta')
                    ->htmlTemplate('correo/incidenciaFinalizada.html.twig')
                    ->context(['numPedido' => $incidencia->getId(), 'incidencia' => $incidencia]);
            $mailer->send($email);
        } else if ($estado === 'Finalizada sin confirmar') {
            $incidencia->setEstado('Finalizada');
            $registro->setCambioestado('Finalizada');
        }



        $registro->setFecha(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $registro->setIncidencia($incidencia);
        $registro->setUsuario($this->getUser());
        $entityManager->persist($registro);

        $entityManager->persist($incidencia);
        $entityManager->flush();

        $registros = $doctrine->getRepository(\App\Entity\Registro::class)->findBy(['incidencia' => $id]);
        $activo = $incidencia->getActivo();
        $usuarioVisualizando = $this->getUser();
        return $this->render('detalleincidencia/index.html.twig', [$id,
                    'incidencia' => $incidencia,
                    'activo' => $activo,
                    'registros' => $registros,
                    'usuarioVisualizando' => $usuarioVisualizando,
        ]);
    }

    /**
     * @Route("/confirmarfinalizacion", name="confirmarfinalizacion")
     */
    public function confirmarfinalizacion(ManagerRegistry $doctrine): Response {

        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['usuarioCliente' => $this->getUser(), 'estado' => 'Finalizada sin confirmar']);

        return $this->render('confirmarfinalizacion/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/confirmarrechazar/{id}", name="confirmarrechazar")
     */
    public function confirmarrechazar(Request $request, ManagerRegistry $doctrine, int $id): Response {
        $incidencia = $doctrine->getRepository(\App\Entity\Incidencia::class)->find($id);
        $entityManager = $doctrine->getManager();
        $finalizacion = $request->request->get('finalizacion');
        $registro = new Registro();
        if ($finalizacion === "Si") {
            $incidencia->setEstado('Finalizada');

            $registro->setCambioestado('Finalizada');
        } else if ($finalizacion === "No") {
            $incidencia->setEstado('En proceso');
            $registro->setCambioestado('En proceso');
        }
        $registro->setFecha(\DateTime::createFromFormat('Y-m-d', date("Y-m-d")));
        $registro->setIncidencia($incidencia);
        $registro->setUsuario($this->getUser());
        $entityManager->persist($registro);

        $entityManager->persist($incidencia);
        $entityManager->flush();
        $numIncidenciasConfirmar = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['usuarioCliente' => $this->getUser(), 'estado' => 'Finalizada sin confirmar']);

        $numIncidenciasConfirmar = count($numIncidenciasConfirmar);
        return $this->render('principal/index.html.twig', array(
                    'incidenciasPorConfirmar' => $numIncidenciasConfirmar,
                    'incidenciasPorConfirmar' => $numIncidenciasConfirmar,
        ));
    }

    /**
     * @Route("/detallesusuario", name="detallesusuario")
     */
    public function detallesusuario(Request $request, ManagerRegistry $doctrine): Response {
        /*
          $comment->setUsuario($comment2['usuario']);
          $comment->setRoles($comment2['roles']);
          $comment->setPassword($comment2['password']);
         */

        $entityManager = $doctrine->getManager();

        //$comment = $doctrine->getRepository(\App\Entity\Usuarios::class)->findBy(['usuario' => $this->getUser()]);

        $comment = new Usuarios();

        $form = $this->createForm(DatosUsuarioType:: class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('detallesusuario');
        }
        $usuario = $this->getUser();
        return $this->render('detallesusuario/index.html.twig', [
                    'comment_form' => $form->createView(),
                    'usuario' => $usuario
        ]);
        /*
          $usuario = $this->getUser();
          return $this->render('detallesusuario/index.html.twig', [
          'usuario' => $usuario
          ]);

         */
    }

    /**
     * @Route("/cambiarpassword", name="cambiarpassword")
     */
    public function cambiarpassword(): Response {
        return $this->render('reset_password/reset.html.twig', [
        ]);
    }

    /**
     * @Route("/cambiardatosusuario", name="cambiardatosusuario")
     */
    public function cambiardatosusuario(Request $request, ManagerRegistry $doctrine): Response {


        $entityManager = $doctrine->getManager();

        /*
          $ext=$file->guessExtension();
          $file_name=time().".".$ext;
          $file->move("uploads",$file_name);
          $entry->setImage(null);
         */

        $usuario = $this->getUser();
        //$imagen = $request->request->get('imagen');
        //$imagen = $doctrine->getRepository(VichImageType::class)->get('imagen');
        //$usuario->setImageFile($imagen);


        $nombre = $request->request->get('username');
        $usuario->setUsuario($nombre);
        $email = $request->request->get('email');
        $usuario->setEmail($email);
        $entityManager->persist($usuario);
        $entityManager->flush();
        return $this->render('detallesusuario/index.html.twig', [
                    'usuario' => $usuario
        ]);
    }

    /**
     * @Route("/ultimasincidencias", name="ultimasincidencias")
     */
    public function ultimasincidencias(ManagerRegistry $doctrine): Response {
        /* Muestra las ultimas 5 incidencias */
        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findAll(array('fecha' => 'DESC'), 5);

        return $this->render('misincidencias/index.html.twig', array(
                    'incidencias' => $incidencias
        ));
    }

    /**
     * @Route("/tablaactivos", name="tablaactivos")
     * @IsGranted("ROLE_TECNICO")
     */
    public function activos(ManagerRegistry $doctrine): Response {
        /* Muestra las ultimas 5 incidencias */
        //$activos = $doctrine->getRepository(\App\Entity\Activo::class);
        $activos = $doctrine->getRepository(\App\Entity\Activo::class)->findAll();
        //$activos = $repository
        //        $incidencias = $doctrine->getRepository(\App\Entity\Incidencia::class)->findBy(['usuarioCliente' => $this->getUser()], ['fecha' => 'DESC']);


        return $this->render('tablaactivos/index.html.twig', array(
                    'activos' => $activos
        ));
    }

    /**
     * @Route("/abririncidencia/{id}", name="abririncidencia")
     */
    public function abririncidencia(Request $request, ManagerRegistry $doctrine, int $id): Response {
        $incidencia = $doctrine->getRepository(\App\Entity\Incidencia::class)->find($id);
        $repository = $doctrine->getRepository(\App\Entity\Activo::class);
        $activos = $repository->findAll();

        return $this->render('abririncidencia/index.html.twig', [
                    'controller_name' => 'FamiliaController',
                    'activos' => $activos,
                    'incidencia' => $incidencia
        ]);
    }

}
