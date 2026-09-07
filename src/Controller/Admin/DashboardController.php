<?php

namespace App\Controller\Admin;

// 👇 Les imports indispensables pour que ça marche
use App\Entity\Client;
use App\Entity\Reservation;
use App\Entity\Spectacle;
use App\Controller\Admin\ClientCrudController; 
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
// ---------------------------------------------------------

use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // 1. SÉCURITÉ : Si ce n'est pas un admin, on le vire
        if (!$this->isGranted('ROLE_ADMIN')) {
            $this->addFlash('danger', 'Accès interdit !');
            return $this->redirectToRoute('app_home'); // Assure-toi que ta route d'accueil s'appelle bien 'app_home'
        }

        // 2. CORRECTION DE L'ERREUR : On redirige vers la liste des Clients
        // Au lieu d'essayer d'afficher un fichier "dashboard.html.twig" qui n'existe pas.
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        return $this->redirect($adminUrlGenerator->setController(ClientCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Administration Spectacles');
    }

    public function configureMenuItems(): iterable
    {
        // Ce lien ramènera maintenant vers la liste des clients (grâce à la redirection ci-dessus)
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

        yield MenuItem::linkToCrud('Clients', 'fas fa-users', Client::class);
        yield MenuItem::linkToCrud('Spectacles', 'fas fa-theater-masks', Spectacle::class);
        yield MenuItem::linkToCrud('Réservations', 'fas fa-ticket-alt', Reservation::class);
    }
}