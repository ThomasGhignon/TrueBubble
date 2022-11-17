<?php

namespace App\DataFixtures;

use App\Entity\Post;
use App\Entity\PostRelevance;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://randomuser.me/api/?results=30",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ]);

        $randomUsers = json_decode(curl_exec($curl));
        $err = curl_error($curl);

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://random-word-api.herokuapp.com/all",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ]);

        $randomWords = json_decode(curl_exec($curl));
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        }

        foreach ($randomUsers->results as $data) {
            $user = new User();
            $user->setPseudo($data->login->username);
            $user->setEmail($data->email);
            $user->setPassword($this->hasher->hashPassword($user, $data->login->password));
            $user->setRoles(['ROLE_USER']);
            $user->setCreateAt(new \DateTime());
            $user->setImage($data->picture->medium);
            $manager->persist($user);

            for ( $i = 0; $i < rand(3, 15); $i++ ) {
                $title = '';
                for ($j = 0; $j < rand(1, 3); $j++) {
                    $title .= $randomWords[array_rand($randomWords, 1)] . ' ';
                }

                $post = new post();
                $post->setTitle(ucfirst($title));
                $post->setContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum. Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
                $post->setAuthor($user);
                $post->setCreateAt(new \DateTime());
                $post->setImage('https://picsum.photos/1080/540');
                $manager->persist($post);
            }
        }

        $manager->flush();

        $users = $manager->getRepository(User::class)->findAll();
        $posts = $manager->getRepository(Post::class)->findAll();
        foreach ($users as $user) {
            foreach ($posts as $post) {
                if ($user->getId() !== $post->getAuthor()->getId()) {
                    $postRelevance = new PostRelevance();
                    $postRelevance->setUser($user);
                    $postRelevance->setPost($post);
                    $postRelevance->setRelevance(rand(0, 1));
                    $postRelevance->setCreateAt(new \DateTime());
                    $manager->persist($postRelevance);
                }
            }
        }

        $user = new User();
        $user->setPseudo('admin');
        $user->setEmail('admin@gmail.com');
        $user->setPassword($this->hasher->hashPassword($user, 'azerty'));
        $user->setRoles(['ROLE_USER', 'ROLE_ADMIN', 'ROLE_EDITOR']);
        $user->setCreateAt(new \DateTime());
        $user->setImage('https://picsum.photos/200');
        $manager->persist($user);

        for ( $i = 0; $i < 2; $i++ ) {
            $post = new post();
            $post->setTitle('C# is the best language ?');
            $post->setContent("Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.");
            $post->setAuthor($user);
            $post->setCreateAt(new \DateTime());
            $post->setImage('https://picsum.photos/1080/540');
            $manager->persist($post);
        }

        $manager->flush();
    }
}
