<?php

namespace App\Controller;

use App\Entity\Profile;
use Doctrine\ORM\Mapping\Id;
use App\Repository\ProfileRepository;
use App\Repository\CategoryRepository;
use App\Repository\OccasionRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('main/home.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #MON COMPTE
    #[Route('/account',name: 'account')]
    public function show( ProfileRepository $profileRepository): Response
    {
        return $this->render('main/userInfo.html.twig', [
             'profile' => $profileRepository->findOneByUserId($this->getUser()),
       // dd($profileRepository->findOneByUserId($this->getUser()))
        ]);
        
        
    }

    #CALENDAR
    #[Route('/calendar', name:'calendar', methods:['GET'])]
    public function calendarShow(CategoryRepository $categoryRepository) :Response
    {
        return $this->render('main/calendar.html.twig',[
            'categories'=>$categoryRepository->findAll()
        ]);
    }



    
}
