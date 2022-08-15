<?php

namespace App\Controller;

use App\Entity\Occasion;
use App\Form\OccasionType;
use App\Repository\OccasionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/occasion')]
class OccasionController extends AbstractController
{
    #[Route('/', name: 'app_occasion_index', methods: ['GET'])]
    public function index(OccasionRepository $occasionRepository): Response
    {
        return $this->render('occasion/index.html.twig', [
            'occasions' => $occasionRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_occasion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OccasionRepository $occasionRepository): Response
    {
        $occasion = new Occasion();
        $form = $this->createForm(OccasionType::class, $occasion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $occasionRepository->add($occasion, true);

            return $this->redirectToRoute('app_occasion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('occasion/new.html.twig', [
            'occasion' => $occasion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_occasion_show', methods: ['GET'])]
    public function show(Occasion $occasion): Response
    {
        return $this->render('occasion/show.html.twig', [
            'occasion' => $occasion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_occasion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Occasion $occasion, OccasionRepository $occasionRepository): Response
    {
        $form = $this->createForm(OccasionType::class, $occasion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $occasionRepository->add($occasion, true);

            return $this->redirectToRoute('app_occasion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('occasion/edit.html.twig', [
            'occasion' => $occasion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_occasion_delete', methods: ['POST'])]
    public function delete(Request $request, Occasion $occasion, OccasionRepository $occasionRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$occasion->getId(), $request->request->get('_token'))) {
            $occasionRepository->remove($occasion, true);
        }

        return $this->redirectToRoute('app_occasion_index', [], Response::HTTP_SEE_OTHER);
    }
}
