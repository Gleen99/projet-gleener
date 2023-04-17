<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Entity\Reports;
use App\Form\CommentType;
use App\Form\ReportType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    #[Route('/report/{id}', name: 'app_report')]
    public function index(EntityManagerInterface $entityManager, int $id): Response
    {
        $comments = $entityManager->getRepository(Comments::class)->findBy(['id'=>$id], []);

        //Si pas de comments trouvé, on set à null comme ça le twig gère l'erreur
        if (!$comments[0]) {
            $comments = [null];
        }

        $reportForm = $this->createForm(ReportType::class);

        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
            'user' => $this->getUser(),
            'reportForm' => $reportForm->createView(),
            'comments' => $comments[0],
            'title' => 'Signal du commentaire'
        ]);
    }

    #[Route("/report/{id}/add", name: "add_report")]
    public function addReport(Request $request, EntityManagerInterface $entityManager, int $id): Response
    {
        $comments = $entityManager->getRepository(Comments::class)->findBy(['id'=>$id], []);

        //Si pas de comments trouvé, on set à null comme ça le twig gère l'erreur
        if (!$comments[0]) {
            $comments = [null];
        }

        $report = new Reports();
        $report->setUser($this->getUser());
        $report->setComment($comments[0]);

        $reportForm = $this->createForm(ReportType::class, $report);
        $reportForm->handleRequest($request);

        if ($reportForm->isSubmitted() && $reportForm->isValid()) {
            $entityManager->persist($report);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post', [
            'id' => $comments[0]->getPost()->getId()
        ]);
    }
}