<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRelevance;
use App\Form\PostType;
use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class PostController extends AbstractController
{

    public function index(PostRepository $postRepository): Response
    {
        return $this->render('post/index.html.twig', [
            'posts' => $postRepository->findAll(),
        ]);
    }

    public function new(Request $request, PostRepository $postRepository): Response
    {
        if (!$this->getUser()) throw $this->createAccessDeniedException();

        $post = new Post();
        $post->setAuthor($this->getUser());
        $post->setCreateAt(new \DateTime());
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/new.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    public function show(Post $post): Response
    {
        $total = 0;
        foreach ($post->getPostRelevances() as $relevance) {
            $total += $relevance->isRelevance() ? 1 : -1;
        }

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'author' => $post->getAuthor(),
            'relevance' => $total,
            'create_at' => $post->getCreateAt()->format('M. d, Y'),
        ]);
    }

//    #[Route('/{id}/edit', name: 'app_post_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Post $post, PostRepository $postRepository): Response
    {
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $postRepository->save($post, true);

            return $this->redirectToRoute('app_user_show', ['id' => $this->getUser()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('post/edit.html.twig', [
            'post' => $post,
            'form' => $form,
        ]);
    }

    public function delete(Request $request, Post $post, PostRepository $postRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$post->getId(), $request->request->get('_token'))) {
            $postRepository->remove($post, true);
        }

        return $this->redirectToRoute('app_home', [], Response::HTTP_SEE_OTHER);
    }
}
