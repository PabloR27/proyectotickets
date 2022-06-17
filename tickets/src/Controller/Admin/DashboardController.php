<?php

namespace App\Controller\Admin;

use App\Entity\Activo;
use App\Entity\Incidencia;
use App\Entity\Usuarios;
use App\Entity\Registro;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractDashboardController {

    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response {
        //return parent::index();


        $routeBuilder = $this->container->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(ActivoCrudController::class)->generateUrl());

        // you can also redirect to different pages depending on the current user
        //if ('jane' === $this->getUser()->getUsername()) {
        //    return $this->redirect('...');
        //}
        // you can also render some template to display a proper Dashboard
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //return $this->render('some/path/my-dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard {
        return Dashboard::new()
                        ->setTitle('Tickets')
                        ->setFaviconPath('img/ticket.png');
    }

    public function configureMenuItems(): iterable {
        return[
            MenuItem::linkToRoute('Inicio', 'fas fa-home', 'principal'),
            //MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard'),
            MenuItem::linkToCrud('Activo', 'fas fa-book', Activo::class),
            MenuItem::linkToCrud('Incidencia', 'fas fa-desktop', Incidencia::class),
            MenuItem::linkToCrud('Usuarios', 'fa fa-users', Usuarios::class),
            MenuItem::linkToCrud('Registros', 'fas fa-comments', Registro::class),
        ];
    }

}
