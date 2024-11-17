<?php

namespace App\DataFixtures;

use App\Entity\CRM\Usuario;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $userPasswordHasher)
    {
        
    }

    public function load(ObjectManager $manager): void
    {
        $usuario = new Usuario();
        $usuario->setEmail('admin@bazarperu.com');
        $hashedPassword = $this->userPasswordHasher->hashPassword($usuario, 'admin');
        $usuario->setPassword($hashedPassword);
        $usuario->setRoles(['ROLE_ADMIN']);
        $usuario->setEstado(true);
        $manager->persist($usuario);
        $manager->flush();
    }
}
