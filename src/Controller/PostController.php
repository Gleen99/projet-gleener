<?php

namespace App\Controller;

use App\Entity\Comments;
use App\Entity\Posts;
use App\Form\PostType;
use App\Repository\PostsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\CommentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PostController extends AbstractController
{
    #[Route('/post/{id}', name: 'app_post')]
    public function index( EntityManagerInterface $entityManager, int $id): Response
    {
        $posts = $entityManager->getRepository(Posts::class)->findBy(['id' => $id], []);

        //On met le $post[0] à null, comme ça le twig peut handle l'erreur
        if (!$posts[0]) {
            $posts = [null];
        }

        $commentForm = $this->createForm(CommentType::class);

        return $this->render('post/index.html.twig', [
            'posts' => $posts[0],
            'user' => $this->getUser(),
            'commentForm' => $commentForm->createView()
        ]);
    }
    
    #[Route('/posts', name: 'app_posts')]
    public function indexArticle(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Posts::class)->findAll();

        return $this->render('post/indexAll.html.twig', [
            'posts' => $posts,
            'user' => $this->getUser()
        ]);
    }
/**Create Comments */

    
    #[Route("/post/{id}/comment/add", name: "add_comment")]
    public function addComment(Request $request, EntityManagerInterface $entityManager, Posts $post): Response
    {
        $comments = new Comments();
        $comments->setCreator($this->getUser());
        $comments->setPost($post);

        $commentForm = $this->createForm(CommentType::class, $comments);
        $commentForm->handleRequest($request);

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $entityManager->persist($comments);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_post', [
            'id' => $post->getId()
        ]);
    }


    #[Route("/comment/{id}/like", name:"like_comment")]
    public function likeComment(Comments $comment, EntityManagerInterface $entityManager, SessionInterface $session)
    {
        // Vérifier si l'utilisateur a déjà aimé ou disliké ce commentaire dans cette session
        $likedComments = $session->get('liked_comments', []);
        $dislikedComments = $session->get('disliked_comments', []);
        if (in_array($comment->getId(), $likedComments)) {
            $this->addFlash('warning', 'Vous avez déjà aimé ce commentaire');
            return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
        }
        if (in_array($comment->getId(), $dislikedComments)) {
            $comment->setDislikes($comment->getDislikes() - 1);
            $entityManager->flush();
            $dislikedComments = array_diff($dislikedComments, [$comment->getId()]);
            $session->set('disliked_comments', $dislikedComments);
        }
    
        // Ajouter le like et sauvegarder en base de données
        $comment->setLikes($comment->getLikes() + 1);
        $entityManager->flush();
    
        // Enregistrer l'ID du commentaire dans la session pour indiquer que l'utilisateur l'a aimé
        $likedComments[] = $comment->getId();
        $session->set('liked_comments', $likedComments);
    
        // Rediriger vers la page du post
        return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
    }
    
    #[Route("/comment/{id}/dislike", name:"dislike_comment")]
    public function dislikeComment(Comments $comment, EntityManagerInterface $entityManager, SessionInterface $session)
    {
        // Vérifier si l'utilisateur a déjà aimé ou disliké ce commentaire dans cette session
        $likedComments = $session->get('liked_comments', []);
        $dislikedComments = $session->get('disliked_comments', []);
        if (in_array($comment->getId(), $dislikedComments)) {
            $this->addFlash('warning', 'Vous avez déjà disliké ce commentaire');
            return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
        }
        if (in_array($comment->getId(), $likedComments)) {
            $comment->setLikes($comment->getLikes() - 1);
            $entityManager->flush();
            $likedComments = array_diff($likedComments, [$comment->getId()]);
            $session->set('liked_comments', $likedComments);
        }
    
        // Ajouter le dislike et sauvegarder en base de données
        $comment->setDislikes($comment->getDislikes() + 1);
        $entityManager->flush();
    
        // Enregistrer l'ID du commentaire dans la session pour indiquer que l'utilisateur l'a disliké
        $dislikedComments[] = $comment->getId();
        $session->set('disliked_comments', $dislikedComments);
    
        // Rediriger vers la page du post
        return $this->redirectToRoute('app_post', ['id' => $comment->getPost()->getId()]);
    }


/**Create Posts */

#[Route('/new/post', name: 'app_new_post')]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $post = new Posts();
    $form = $this->createForm(PostType::class, $post);

    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        // Ajouter le créateur du post
        $post->setCreator($this->getUser());

        // Enregistrer le post dans la base de données
        $entityManager->persist($post);
        $entityManager->flush();

        $this->addFlash('success', 'Le post a été créé avec succès !');
        return $this->redirectToRoute('app_posts');
    }

    return $this->render('post/new.html.twig', [
        'user' => $this->getUser(),
                    'form' => $form->createView(),
    ]);
}
/**
 * Supprimer Posts
 */
#[Route("/posts/remove/{id}", name:"app_delete_post")]
    
public function deletePost(int $id,Posts $post, PersistenceManagerRegistry $doctrine): Response
    {
        $entityManager = $doctrine->getManager();
        $postsRepository = $entityManager->getRepository(Posts::class);
        
        if (!$post) {
            throw $this->createNotFoundException(sprintf('The post with id "%s" could not be found', $request->get('id')));
        }

    // Supprimer les commentaires du post
    $comments = $post->getComments();
    foreach ($comments as $comment) {
    // Supprimer les reports associés au commentaire
    $reports = $comment->getReports();
    foreach ($reports as $report) {
        $entityManager->remove($report);
    }

    // Supprimer le commentaire lui-même
    $entityManager->remove($comment);
    }

    // Supprimer le post lui-même
    $entityManager->remove($post);
    $entityManager->flush();

        // Redirection vers la liste des posts
        return $this->redirectToRoute('app_posts');
    }
}