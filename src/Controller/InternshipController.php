<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Entity\UserInternship;
use App\Repository\InternshipRepository;
use App\Repository\UserInternshipRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class InternshipController extends AbstractController
{
    /**
     * @Route("/internships/register/{id}", name="app_register_internship", methods={"POST"})
     */
    public function register(Internship $internship, EntityManagerInterface $entityManager): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Vérifier si l'utilisateur est déjà inscrit
        $existingRegistration = $entityManager->getRepository(UserInternship::class)
            ->findOneBy([
                'user' => $this->getUser(),
                'internship' => $internship
            ]);

        if ($existingRegistration) {
            $this->addFlash('warning', 'You are already registered for this internship.');
            return $this->redirectToRoute('app_my_internships');
        }

        $userInternship = new UserInternship();
        $userInternship->setUser($this->getUser());
        $userInternship->setInternship($internship);
        $userInternship->setCreatedAt(new \DateTime());

        $entityManager->persist($userInternship);
        $entityManager->flush();

        $this->addFlash('success', 'You have successfully registered for this internship.');

        return $this->redirectToRoute('app_my_internships');
    }

   
     #@Route("/my-internships", name="app_my_internships", methods={"GET"})
    #[Route('/my-internships', name: 'app_my_internships')]
     
    public function myInternships(UserInternshipRepository $userInternshipRepo): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $userInternships = $userInternshipRepo->findBy(['user' => $this->getUser()]);

        return $this->render('internship/my_internships.html.twig', [
            'my_internships' => $userInternships,
        ]);
    }
}