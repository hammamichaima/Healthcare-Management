<?php

namespace App\Controller;

use App\Entity\Staff;
use App\Form\StaffType;
use App\Repository\StaffRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/staff')]
class StaffController extends AbstractController
{
    #[Route('/', name: 'staff_index', methods: ['GET'])]
    public function index(StaffRepository $staffRepository): Response
    {
        return $this->render('staff/index.html.twig', [
            'staff' => $staffRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'staff_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $staff = new Staff();
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($staff);
            $entityManager->flush();

            return $this->redirectToRoute('staff_index');
        }

        return $this->render('staff/new.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'staff_show', methods: ['GET'])]
    public function show(Staff $staff): Response
    {
        return $this->render('staff/show.html.twig', [
            'staff' => $staff,
        ]);
    }

    #[Route('/{id}/edit', name: 'staff_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(StaffType::class, $staff);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('staff_index');
        }

        return $this->render('staff/edit.html.twig', [
            'staff' => $staff,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'staff_delete', methods: ['POST'])]
    public function delete(Request $request, Staff $staff, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$staff->getId(), $request->request->get('_token'))) {
            $entityManager->remove($staff);
            $entityManager->flush();
        }

        return $this->redirectToRoute('staff_index');
    }
}
