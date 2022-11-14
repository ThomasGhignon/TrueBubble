<?php

namespace App\Controller;

use App\Entity\Post;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends AbstractController
{
    public function index(ManagerRegistry $doctrine): Response
    {
        $post = $doctrine->getRepository(Post::class)->findBy([], ['create_at' => 'DESC'], 10);


        return $this->render('pages/home/view.html.twig', [
            'posts' => $post,
        ]);
    }
}
