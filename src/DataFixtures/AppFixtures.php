<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Posts;
use App\Entity\Comments;
use Faker\Factory;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    public function load(ObjectManager $manager)
    {  
        $user1 = new User();
        $user1->setUsername('fatima');
        $user1->setPassword($this->passwordHasher->hashPassword($user1, 'password123'));
        $user1->setEmail('john_doe@example.com');
        $user1->setFirstname('fatima');
        $user1->setLastname('Doe');
        $user1->setRoles(['ROLE_USER']);
        // Vérifier si le nom d'utilisateur existe déjà dans la base de données
        $existingUser = $manager->getRepository(User::class)->findOneBy(['username' => $user1->getUsername()]);
        if (!$existingUser) {
            $manager->persist($user1);
            $this->addReference('user_1', $user1);
        }


        $admin = new User();
        $admin->setUsername('admin');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setEmail('admin@example.com');
        $admin->setFirstname('Admin');
        $admin->setLastname('User');
        $admin->setRoles(['ROLE_ADMIN']);
        // Vérifier si le nom d'utilisateur existe déjà dans la base de données
        $existingUser = $manager->getRepository(User::class)->findOneBy(['username' => $admin->getUsername()]);
        if (!$existingUser) {
            $manager->persist($admin);
            $this->addReference('user_2', $admin);
        }


        $user2 = new User();
        $user2->setUsername('gleen');
        $user2->setPassword($this->passwordHasher->hashPassword($user2, 'password123'));
        $user2->setEmail('jane_doe@example.com');
        $user2->setFirstname('gleen');
        $user2->setLastname('Doe');
        $user2->setRoles(['ROLE_USER']);
        // Vérifier si le nom d'utilisateur existe déjà dans la base de données
        $existingUser = $manager->getRepository(User::class)->findOneBy(['username' => $user2->getUsername()]);
        if (!$existingUser) {
            $manager->persist($user2);
            $this->addReference('user_3', $user2);
        }

        
        //creation des postes et des commentaires
        $faker = Factory::create('fr_FR');

        // create 10 posts
        for ($i = 0; $i < 10; $i++) {
            $post = new Posts();
            $post->setTitle($faker->sentence);
            $post->setDescription($faker->paragraph);
            $post->setContent($faker->text);
            $post->setLocalisation($faker->streetAddress);
            $post->setVille($faker->city);
            $post->setOther($faker->sentence);
            $post->setDate($faker->dateTimeBetween('-1 year', 'now'));

            // set a random user as the creator of the post
            $user = $this->getReference('user_' . rand(1, 3));
            $post->setCreator($user);

            $manager->persist($post);

            // create 1 to 5 comments for each post
            for ($j = 0; $j < rand(1, 5); $j++) {
                $comment = new Comments();
                $comment->setText($faker->paragraph);
                $comment->setCreator($this->getReference('user_' . rand(1, 3)));
                $comment->setPost($post);
                $manager->persist($comment);
            }
        }

        $manager->flush();
    }
}
