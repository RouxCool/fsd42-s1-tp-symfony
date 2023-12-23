<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Form\RegisterFormType;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $u = ['test1@gmail.com', 'test2@gmail.com'];
        foreach ($u as $userEmail) {
            $user = new User();
            $user->setEmail($userEmail);
            $user->setPassword('$2y$13$PE/GblPa/qUDkvybL7w9sOo.6hNXsRuYNxI5QtBaFIsyYi7togzOe'); // 12345678
            $user->setName('Test');
            $user->setRoles([]);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
