<?php

namespace App\Controller;

use App\Entity\PostRelevance;
use App\Repository\PostRelevanceRepository;
use App\Repository\PostRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use JetBrains\PhpStorm\NoReturn;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PostRelevanceController extends AbstractController
{

    #[NoReturn] public function new(Request $request,ManagerRegistry $doctrine, PostRelevanceRepository $postRelevanceRepository, PostRepository $postRepository): JsonResponse
    {
        $data = json_decode($request->getContent());
        $post = $postRepository->find($data->post_id);
        $total = 0;

        if (!$this->getUser()) throw $this->createAccessDeniedException();

        if ($post->getAuthor() === $this->getUser()) {
            return $this->json([
                'message' => 'You\'re the author of this post',
                'code' => 400,
            ], 400);
        }


        $postRelevance = $post->getPostRelevances()->filter(function (PostRelevance $postRelevance) {
            return $postRelevance->getUser() === $this->getUser();
        });


        if ($postRelevance->count() > 0) {
            if ($postRelevance !== $data->relevance) {
                $postRelevance->setRelevance($data->relevance);
                $postRelevanceRepository->save($postRelevance, true);
                $data->relevance === 1 ? $post->setLikes($post->getLikes() + 1) : $post->setLikes($post->getLikes() + (-1));
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
            $data->relevance === 1 ? $post->setLikes($post->getLikes() + 1) : $post->setLikes($post->getLikes() + (-1));
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
