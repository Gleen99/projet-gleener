<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Posts;
use App\Entity\Comments;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index(PersistenceManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $posts = $doctrine->getRepository(Posts::class)->findBy(['creator' => $user]);
        $comments = $doctrine->getRepository(Comments::class)->findBy(['creator' => $user]);

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'posts' => $posts,
            'comments' => $comments,
            'title' => 'Profile'
        ]);
    }
}
