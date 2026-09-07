<?php

namespace App\Controller\Admin;

use App\Entity\Reservation;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReservationCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reservation::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),

            // 1. Pour afficher le TITRE du spectacle (On va chercher dans seance -> spectacle -> titre)
            TextField::new('seance.spectacle.titre', 'Spectacle')
                ->setSortable(false) // On ne peut pas trier dessus car c'est une donnée éloignée
                ->hideOnForm(),
            // 2. Les relations normales
            AssociationField::new('client', 'Client'),
            AssociationField::new('seance', 'Séance'),

            // 3. Nombre de places (S'affichera si la donnée existe en BDD)
            IntegerField::new('nbPlaces', 'Nb Places'),
            
            // 4. CORRECTION DU PRIX (On désactive les centimes)
            MoneyField::new('prixTotale', 'Prix Total')
                ->setCurrency('EUR')
                ->setStoredAsCents(false), // 👈 C'est cette ligne qui va transformer 0.40€ en 40.00€
            
            DateTimeField::new('dateReservation', 'Date de commande')
                ->hideOnForm(),
        ];
    }
}