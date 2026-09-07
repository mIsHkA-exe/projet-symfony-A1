<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Client;
use App\Entity\Reservation;
use App\Repository\ClientRepository;
use App\Repository\SpectacleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ReservationController extends AbstractController
{
    // On garde juste les tarifs car ils ne sont pas en base de données
    private $tarifs = [
        ["label" => "Adulte", "prix" => 25],
        ["label" => "Enfant", "prix" => 15],
    ];


    #[Route('/reservation', name: 'app_reservation')]
    public function index(Request $request, SpectacleRepository $spectacleRepo): Response
    {
        $session = $request->getSession();

        // 1. GESTION DU FORMULAIRE
        if ($request->isMethod('POST')) {
            if ($request->request->get('email')) {
                $session->set('email', trim($request->request->get('email')));
            }
            // Ici on sauvegarde le TYPE
            if ($request->request->get('type')) {
                $session->set('type', $request->request->get('type'));
                $session->remove('spectacle'); 
                $session->set('panier', []);
            }
            if ($request->request->get('spectacle')) {
                $session->set('spectacle', $request->request->get('spectacle'));
                $session->set('panier', []);
            }
            if ($request->request->get('tarif')) {
                $panier = $session->get('panier', []);
                $panier[] = [
                    'tarif' => $request->request->get('tarif'),
                    'prix' => (float)$request->request->get('prix'),
                    'quantite' => (int)$request->request->get('quantite')
                ];
                $session->set('panier', $panier);
            }
             if ($request->request->has('vider')) {
                $session->set('panier', []);
            }
        }

        // 2. PRÉPARATION DES DONNÉES
        $email = $session->get('email', '');
        $typeChoisi = $session->get('type', '');
        $spectacleId = $session->get('spectacle');
        $panier = $session->get('panier', []);

        // --- CORRECTION ICI : ON UTILISE LE CHAMP 'type' DE LA BDD ---
        $tousLesSpectacles = $spectacleRepo->findAll();
        $types = [];
        foreach($tousLesSpectacles as $s) {
            // On utilise getType() au lieu de getGenre()
            if ($s->gettype()) {
                $types[] = $s->gettype();
            }
        }
        $types = array_unique($types);

        // --- CORRECTION ICI AUSSI ---
        $spectaclesFiltres = [];
        if ($typeChoisi) {
            // On cherche dans la colonne 'type'
            $spectaclesFiltres = $spectacleRepo->findBy(['type' => $typeChoisi]);
        }

        $spectacleChoisiObj = null;
        if ($spectacleId) {
            $spectacleChoisiObj = $spectacleRepo->find($spectacleId);
        }

        return $this->render('reservation/index.html.twig', [
            'types' => $types,
            'spectacles' => $spectaclesFiltres,
            'tarifs' => $this->tarifs,
            'email' => $email,
            'typeChoisi' => $typeChoisi,
            'spectacleChoisi' => $spectacleChoisiObj,
            'panier' => $panier,
            'total' => $this->calculerTotal($panier),
        ]);
    }

  #[Route('/reservation/paiement', name: 'app_paiement')]
    public function paiement(
        Request $request, 
        EntityManagerInterface $em, 
        SpectacleRepository $spectacleRepo
        // On n'a plus besoin de ClientRepository ni de PasswordHasher ici !
    ): Response
    {
        // 1. SÉCURITÉ : Si l'utilisateur n'est pas connecté, ouste !
        $user = $this->getUser();
        if (!$user) {
            $this->addFlash('warning', 'Veuillez vous connecter pour procéder au paiement.');
            // Assure-toi que ta route de connexion s'appelle bien 'app_login'
            return $this->redirectToRoute('app_login');
        }

        $session = $request->getSession();
        $panier = $session->get('panier', []);

        if (empty($panier)) {
            return $this->redirectToRoute('app_reservation');
        }

        $spectacle = $spectacleRepo->find($session->get('spectacle'));
        $total = $this->calculerTotal($panier);

        // --- TRAITEMENT DU PAIEMENT ---
        if ($request->isMethod('POST')) {
            
            // On a déjà l'utilisateur ($user) grâce à la vérification du début.
            // On ne crée plus de client ici, on utilise celui qui est connecté.

            // 2. GESTION DE LA SÉANCE (Simplifié : 1ère séance dispo)
            $seance = $spectacle->getSeances()->first();
            if (!$seance) {
                $this->addFlash('error', 'Aucune séance disponible.');
                return $this->redirectToRoute('app_reservation');
            }

            // 3. CRÉATION DE LA RÉSERVATION
            $reservation = new Reservation();
            $reservation->setClient($user); // On lie au client connecté
            $reservation->setSeance($seance);
            $reservation->setPrixTotale($total);
            $reservation->setDateReservation(new \DateTime());

            $nbPlaces = 0;
            foreach($panier as $item) {
                $nbPlaces += $item['quantite'];
            }
            $reservation->setNbPlaces($nbPlaces);

            $em->persist($reservation);
            $em->flush();

            return $this->redirectToRoute('app_confirmation');
        }

        return $this->render('reservation/paiement.html.twig', [
            'panier' => $panier,
            'total' => $total,
            'email' => $user->getEmail(), // On prend l'email du compte connecté
            'spectacle' => $spectacle, 
        ]);
    }
    
  #[Route('/reservation/confirmation', name: 'app_confirmation')]
    public function confirmation(Request $request, SpectacleRepository $spectacleRepo): Response
    {
        $session = $request->getSession();
        $user = $this->getUser(); // On récupère l'utilisateur connecté

        // 1. On récupère les infos nécessaires
        $type = $session->get('type');
        $spectacleId = $session->get('spectacle');
        
        // On va chercher le VRAI spectacle en BDD pour avoir son titre (et pas juste "42")
        $spectacle = $spectacleRepo->find($spectacleId);

        // 2. Maintenant qu'on a récupéré les infos, on peut VRAIMENT nettoyer la session
        $session->remove('panier');
        $session->remove('spectacle');
        $session->remove('type');

        return $this->render('reservation/confirmation.html.twig', [
            'user' => $user,        // On passe l'utilisateur à la vue
            'spectacle' => $spectacle, // On passe l'objet Spectacle entier
            'type' => $type,
        ]);
    }

    private function calculerTotal(array $panier): int
    {
        $total = 0;
        foreach ($panier as $item) {
            $total += $item['prix'] * $item['quantite'];
        }
        return $total;
    }
}