<?php

namespace App\Controller\Admin;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ClientCrudController extends AbstractCrudController
{
    // 1. On récupère l'outil de cryptage (Hasher)
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public static function getEntityFqcn(): string
    {
        return Client::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            
            EmailField::new('email'),

            // 2. Le champ Mot de passe (Seulement à la création)
            TextField::new('password', 'Mot de passe')
                ->setFormType(PasswordType::class) // Pour afficher des étoiles *****
                ->onlyOnForms()
                ->onlyWhenCreating() // On le cache en modification pour éviter les erreurs
                ->setRequired(true),

            TextField::new('nom'),
            TextField::new('prenom'),

            ChoiceField::new('roles', 'Rôles')
                ->setChoices([
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                ])
                ->allowMultipleChoices()
                ->renderAsBadges(),
        ];
    }

    // 3. Cette fonction se déclenche juste AVANT de sauvegarder en base
    public function persistEntity(EntityManagerInterface $entityManager, $entityInstance): void
    {
        if (!$entityInstance instanceof Client) return;

        // On crypte le mot de passe manuellement
        $password = $entityInstance->getPassword();
        if ($password) {
            $hashedPassword = $this->hasher->hashPassword($entityInstance, $password);
            $entityInstance->setPassword($hashedPassword);
        }

        parent::persistEntity($entityManager, $entityInstance);
    }
}