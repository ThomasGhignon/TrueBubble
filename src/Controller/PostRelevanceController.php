<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRelevance;
use App\Entity\User;
use App\Repository\PostRelevanceRepository;
use App\Repository\PostRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
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

    #[NoReturn] public function new(Request $request, PostRelevanceRepository $postRelevanceRepository, PostRepository $postRepository): JsonResponse
    {
        $data = json_decode($request->getContent());
        $post = $postRepository->find($data->post_id);
        $total = 0;

        if (!$this->getUser()) throw $this->createAccessDeniedException();
        if ($post->getAuthor() === $this->getUser())
            return $this->json([
                'message' => 'You\'re the author of this post',
                'code' => 400,
            ], 400);
        if ($post->getPostRelevances()->count() > 0) {

            $postRelevance = $post->getPostRelevances()->filter(function (PostRelevance $postRelevance) {
                return $postRelevance->getUser() === $this->getUser();
            })->first();

            if ($postRelevance && $postRelevance->isRelevance() !== $data->relevance) {
                $postRelevance->setRelevance($data->relevance);
                $postRelevanceRepository->save($postRelevance, true);
                foreach ($post->getPostRelevances() as $relevance) {
                    $total += $relevance->isRelevance() ? 1 : -1;
                }
                return $this->json([
                    'relevance' => $total,
                    'message' => 'Relevance updated',
                    'code' => 200,
                ], 200);
            } else {
                foreach ($post->getPostRelevances() as $relevance) {
                    $total += $relevance->isRelevance() ? 1 : -1;
                }
                return $this->json([
                    'relevance' => $total,
                    'message' => 'You already voted for this post',
                    'code' => 400,
                ], 400);
            }

        }else{
            $relevance = new PostRelevance();
            $relevance->setPost($post);
            $relevance->setUser($this->getUser());
            $relevance->setRelevance($data->relevance);
            $relevance->setCreateAt(new \DateTime());
            $postRelevanceRepository->save($relevance, true);
            foreach ($post->getPostRelevances() as $relevance) {
                $total += $relevance->isRelevance() ? 1 : -1;
            }
            return $this->json([
                'relevance' => $total,
                'message' => 'Relevance created',
                'code' => 201,
            ], 201);
        }
    }
}
