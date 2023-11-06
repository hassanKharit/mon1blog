<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Entity\Article;
use App\Entity\Comment;
use App\Form\Comment1Type;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article/{id}/comment')]
class CommentController extends AbstractController
{

    #[Route('/new', name: 'app_comment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        $comment = new Comment();
        $comment->setCreatedAt(new DateTimeImmutable());
        $comment->setUser ($this->getUser());
        $comment->setArticle ($article);
        $form = $this->createForm(Comment1Type::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('app_comment_show', [
                'id' =>$article->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('comment/new.html.twig', [
            'comment' => $comment,
            'form' => $form,
        ]);
    }

    
}
