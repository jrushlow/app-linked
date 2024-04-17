<?php

namespace App\Controller;

use App\Entity\Me;
use App\Form\MeType;
use App\Repository\MeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/me')]
class MeController extends AbstractController
{
    #[Route('/', name: 'app_me_index', methods: ['GET'])]
    public function index(MeRepository $meRepository): Response
    {
        return $this->render('me/index.html.twig', [
            'mes' => $meRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_me_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $me = new Me();
        $form = $this->createForm(MeType::class, $me);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($me);
            $entityManager->flush();

            return $this->redirectToRoute('app_me_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('me/new.html.twig', [
            'me' => $me,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_me_show', methods: ['GET'])]
    public function show(Me $me): Response
    {
        return $this->render('me/show.html.twig', [
            'me' => $me,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_me_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Me $me, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeType::class, $me);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_me_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('me/edit.html.twig', [
            'me' => $me,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_me_delete', methods: ['POST'])]
    public function delete(Request $request, Me $me, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$me->getId(), $request->getPayload()->get('_token'))) {
            $entityManager->remove($me);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_me_index', [], Response::HTTP_SEE_OTHER);
    }
}
