<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Internship;
use App\Entity\Task;
use App\Entity\UserInternship;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Créer un admin
        $admin = new User();
        $admin->setUsername('admin');
        $admin->setFirstName('Admin');
        $admin->setLastName('User');
        $admin->setEmail('admin@example.com');
        $admin->setPassword(
            $this->passwordHasher->hashPassword($admin, 'admin123')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        // Créer un utilisateur normal
        $user = new User();
        $user->setUsername('user');
        $user->setFirstName('John');
        $user->setLastName('Doe');
        $user->setEmail('user@example.com');
        $user->setPassword(
            $this->passwordHasher->hashPassword($user, 'user123')
        );
        $manager->persist($user);

        // Créer des stages
        $categories = ['Web Development', 'Data Science', 'Marketing', 'Design'];
        $levels = ['Beginner', 'Intermediate', 'Advanced'];

        for ($i = 1; $i <= 10; $i++) {
            $internship = new Internship();
            $internship->setTitle('Internship ' . $i);
            $internship->setDescription('Description for internship ' . $i . '. This is a detailed description of what the internship entails.');
            $internship->setCategory($categories[array_rand($categories)]);
            $internship->setLevel($levels[array_rand($levels)]);
            $internship->setPubDate(new \DateTime());
            $manager->persist($internship);

            // Créer des tâches pour certains stages
            if ($i % 2 === 0) {
                $task = new Task();
                $task->setDescription('Complete project for internship ' . $i);
                $task->setStartDate(new \DateTime());
                $task->setDeadline((new \DateTime())->modify('+'.($i+1).' weeks'));
                $task->setIsDone(false);
                $task->setInternship($internship);
                $task->setUser($user);
                $manager->persist($task);
            }

            // Inscrire l'utilisateur à certains stages
            if ($i % 3 === 0) {
                $userInternship = new UserInternship();
                $userInternship->setUser($user);
                $userInternship->setInternship($internship);
                $userInternship->setCreatedAt(new \DateTime());
                $manager->persist($userInternship);
            }
        }

        $manager->flush();
    }
}