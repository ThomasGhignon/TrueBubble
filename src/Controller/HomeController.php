<?php

namespace App\Controller;

use App\Entity\Post;
use App\Entity\PostRelevance;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        $total_posts = $doctrine->getRepository(Post::class)->findBy([], ['create_at' => 'DESC'], 10);

        $trendy_posts = $doctrine->getRepository(Post::class)->createQueryBuilder('p')
            ->orderBy('p.likes', 'DESC')
            ->where('p.create_at > :date')
            ->setParameter('date', new \DateTime('-1 day'))
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        foreach ($trendy_posts as $trendy_post) {
            foreach ($total_posts as $post) {
                if ($post->getId() === $trendy_post->getId()) {
                    continue 2;
                }
            }
            $total_posts[] = $trendy_post;
        }

        $scale = 0;
        foreach ($total_posts as $post) {
            $scale += $post->getLikes();
        }
        $scale = $scale / count($total_posts);

        return $this->render('pages/home/view.html.twig', [
            'posts' => $total_posts,
            'scale' => $scale,
        ]);
    }
}
