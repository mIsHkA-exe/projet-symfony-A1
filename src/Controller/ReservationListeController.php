<?php
namespace App\Controller;

use App\Repository\ReservationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ReservationListeController extends AbstractController
{
    #[Route('/ma-liste', name: 'app_ma_liste')]
    #[IsGranted('ROLE_USER')] // Seuls les clients connectés y ont accès
    public function index(ReservationRepository $resRepo): Response
    {
        // On récupère l'objet de l'utilisateur actuel
        $user = $this->getUser();

        // On cherche les réservations liées à cet utilisateur
        // Note: 'client' doit correspondre au nom de la propriété dans votre entité Reservation
        $reservations = $resRepo->findBy(['client' => $user]);

        return $this->render('reservation_liste/index.html.twig', [
            'reservations' => $reservations,
        ]);
    }
}

?>