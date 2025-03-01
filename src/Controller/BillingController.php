<?php

namespace App\Controller;

use App\Entity\Billing;
use App\Repository\BillingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/billings", name="billing_")
 */
class BillingController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private BillingRepository $billingRepository;

    public function __construct(EntityManagerInterface $entityManager, BillingRepository $billingRepository)
    {
        $this->entityManager = $entityManager;
        $this->billingRepository = $billingRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $billings = $this->billingRepository->findAll();
        $data = [];

        foreach ($billings as $billing) {
            $data[] = [
                'id' => $billing->getId(),
                'appointmentId' => $billing->getAppointmentId(),
                'amount' => $billing->getAmount(),
                'status' => $billing->getStatus(),
                'createdAt' => $billing->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        $billing = $this->billingRepository->find($id);

        if (!$billing) {
            return $this->json(['message' => 'Billing record not found'], 404);
        }

        return $this->json([
            'id' => $billing->getId(),
            'appointmentId' => $billing->getAppointmentId(),
            'amount' => $billing->getAmount(),
            'status' => $billing->getStatus(),
            'createdAt' => $billing->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $billing = new Billing();
        $billing->setAppointmentId($data['appointmentId']);
        $billing->setAmount($data['amount']);
        $billing->setStatus($data['status'] ?? 'pending');

        $this->entityManager->persist($billing);
        $this->entityManager->flush();

        return $this->json(['message' => 'Billing record created successfully', 'id' => $billing->getId()], 201);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"PUT"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $billing = $this->billingRepository->find($id);

        if (!$billing) {
            return $this->json(['message' => 'Billing record not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['amount'])) {
            $billing->setAmount($data['amount']);
        }
        if (isset($data['status'])) {
            $billing->setStatus($data['status']);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Billing record updated successfully']);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $billing = $this->billingRepository->find($id);

        if (!$billing) {
            return $this->json(['message' => 'Billing record not found'], 404);
        }

        $this->entityManager->remove($billing);
        $this->entityManager->flush();

        return $this->json(['message' => 'Billing record deleted successfully']);
    }
}