<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InternshipRepository;

class HomeController extends AbstractController
{
    
     #Route[("/", name="home")]
    #[Route('/', name: 'app_home')]
    
    public function index(Request $request, InternshipRepository $internshipRepository): Response
    {
        $searchTerm = $request->query->get('q');
        
        $internships = $searchTerm 
            ? $internshipRepository->search($searchTerm)
            : $internshipRepository->findAll();

        return $this->render('home/index.html.twig', [
            'internships' => $internships,
            'searchTerm' => $searchTerm
        ]);
    }
}