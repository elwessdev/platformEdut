<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    
     #@Route("/profile", name="app_profile")
    #[Route('/profile', name: 'app_profile')]
     
    public function index(): Response
    {
        // Vérifie que l'utilisateur est connecté
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        
        $user = $this->getUser();
        
        // Initialisation des variables
        $completedTasks = 0;
        $totalTasks = 0;
        $activeInternships = 0;
        
        // Vérifie si la méthode getUserInternships existe
        if (method_exists($user, 'getUserInternships')) {
            $activeInternships = count($user->getUserInternships());
            
            foreach ($user->getUserInternships() as $userInternship) {
                $internship = $userInternship->getInternship();
                if ($internship && method_exists($internship, 'getTasks')) {
                    foreach ($internship->getTasks() as $task) {
                        $totalTasks++;
                        if ($task->getIsDone()) {
                            $completedTasks++;
                        }
                    }
                }
            }
        }
        
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'active_internships' => $activeInternships,
            'completed_tasks' => $completedTasks,
            'completion_rate' => $completionRate,
        ]);
    }
}