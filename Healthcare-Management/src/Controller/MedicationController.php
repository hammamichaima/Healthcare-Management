<?php

namespace App\Controller;

use App\Entity\Medication;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicationController extends AbstractController
{
    #[Route('/add-medication', name: 'add_medication')]
    public function addMedication(EntityManagerInterface $entityManager): Response
    {
        // Create a Room entity
        $room = new Room();
        $room->setName('Room 101');
        $room->setCapacity(20);
        $room->setCreatedAt(new \DateTime());
        $room->setUpdatedAt(new \DateTime());

        // Persist the Room entity
        $entityManager->persist($room);

        $medication = new Medication();
        $medication->setName('Aspirin');
        $medication->setDose('500mg');
        $medication->addRoom($room);

        $entityManager->persist($medication);
        $entityManager->flush();

        return new Response('Medication and Room successfully added!');
    }
}
