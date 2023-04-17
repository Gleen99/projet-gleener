<?php

namespace App\Controller;

use App\Entity\Blogs;
use App\Repository\BlogsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;


class BlogsController extends AbstractController
{
    #[Route('/blogs', name: 'app_blogs')]
    public function indexArticle(EntityManagerInterface $entityManager): Response
    {
        $blogs = $entityManager->getRepository(Blogs::class)->findAll();
        // dump($blogs);


        return $this->render('blogs/indexAll.html.twig', [
            'blogs' => $blogs,
            'user' => $this->getUser()
        ]);
    }
    #[Route('/blog/{id}', name: 'app_blog')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $blogs = $entityManager->getRepository(Blogs::class)->findOneBy(['id' => $id]);
        


        return $this->render('blogs/index.html.twig', [
            'blogs' => $blogs,
            'user' => $this->getUser(),
            'title' => 'Article'
        ]);
    }
}
