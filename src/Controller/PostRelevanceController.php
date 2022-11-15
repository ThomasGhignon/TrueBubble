<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRelevance;
use App\Entity\User;
use App\Repository\PostRelevanceRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\Request;
use JetBrains\PhpStorm\NoReturn;
use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PostRelevanceController extends AbstractController
{
//    #[Route('/post/relevance', name: 'app_post_relevance')]
//    public function index(): Response
//    {
//        return $this->render('post_relevance/index.html.twig', [
//            'controller_name' => 'PostRelevanceController',
//        ]);
//    }

    #[NoReturn] public function new(Request $request, PostRelevanceRepository $postRelevanceRepository, PostRepository $postRepository): Void
    {
        $data = json_decode($request->getContent());
        $post = $postRepository->find($data->post_id);
        if (!$this->getUser()) throw $this->createAccessDeniedException();
        if ($post->getAuthor() === $this->getUser()) return;
        if ($post->getPostRelevances()->count() > 0) {
            //find PostRelevance where post_id = $post->getId() and user_id = $this->getUser()->getId()
            $postRelevance = $postRelevanceRepository->find(['post_id' => $post, 'user_id' => $this->getUser()]);
            dd($postRelevance);
        }else{
            $relevance = new PostRelevance();
            $relevance->setPost($post);
            $relevance->setUser($this->getUser());
            $relevance->setRelevance($data->relevance);
            $relevance->setCreateAt(new \DateTime());
            $postRelevanceRepository->save($relevance, true);
        }
    }
}
