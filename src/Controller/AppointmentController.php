<?php
  namespace App\Controller;


  use Symfony\Component\HttpFoundation\Response;
  use Symfony\Component\HttpFoundation\Request;
  use Symfony\Component\Routing\Annotation\Route;
 
  use App\Entity\Appointment;
  use App\Repository\AppointmentRepository;
  use Doctrine\ORM\EntityManagerInterface;
  use Symfony\Component\HttpFoundation\JsonResponse;
  
  use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
  /**
 * @Route("/appointments", name="appointment_")
 */
class AppointmentController extends AbstractController
{
    private EntityManagerInterface $entityManager;
    private AppointmentRepository $appointmentRepository;

    public function __construct(EntityManagerInterface $entityManager, AppointmentRepository $appointmentRepository)
    {
        $this->entityManager = $entityManager;
        $this->appointmentRepository = $appointmentRepository;
    }

    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(): JsonResponse
    {
        $appointments = $this->appointmentRepository->findAll();
        $data = [];

        foreach ($appointments as $appointment) {
            $data[] = [
                'id' => $appointment->getId(),
                'patientName' => $appointment->getPatientName(),
                'doctorName' => $appointment->getDoctorName(),
                'appointmentDate' => $appointment->getAppointmentDate()->format('Y-m-d H:i:s'),
                'status' => $appointment->getStatus(),
                'createdAt' => $appointment->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(int $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return $this->json(['message' => 'Appointment not found'], 404);
        }

        return $this->json([
            'id' => $appointment->getId(),
            'patientName' => $appointment->getPatientName(),
            'doctorName' => $appointment->getDoctorName(),
            'appointmentDate' => $appointment->getAppointmentDate()->format('Y-m-d H:i:s'),
            'status' => $appointment->getStatus(),
            'createdAt' => $appointment->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     */
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $appointment = new Appointment();
        $appointment->setPatientName($data['patientName']);
        $appointment->setDoctorName($data['doctorName']);
        $appointment->setAppointmentDate(new \DateTime($data['appointmentDate']));
        $appointment->setStatus($data['status'] ?? 'scheduled');

        $this->entityManager->persist($appointment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Appointment created successfully', 'id' => $appointment->getId()], 201);
    }

    /**
     * @Route("/update/{id}", name="update", methods={"PUT"})
     */
    public function update(int $id, Request $request): JsonResponse
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return $this->json(['message' => 'Appointment not found'], 404);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['patientName'])) {
            $appointment->setPatientName($data['patientName']);
        }
        if (isset($data['doctorName'])) {
            $appointment->setDoctorName($data['doctorName']);
        }
        if (isset($data['appointmentDate'])) {
            $appointment->setAppointmentDate(new \DateTime($data['appointmentDate']));
        }
        if (isset($data['status'])) {
            $appointment->setStatus($data['status']);
        }

        $this->entityManager->flush();

        return $this->json(['message' => 'Appointment updated successfully']);
    }

    /**
     * @Route("/delete/{id}", name="delete", methods={"DELETE"})
     */
    public function delete(int $id): JsonResponse
    {
        $appointment = $this->appointmentRepository->find($id);

        if (!$appointment) {
            return $this->json(['message' => 'Appointment not found'], 404);
        }

        $this->entityManager->remove($appointment);
        $this->entityManager->flush();

        return $this->json(['message' => 'Appointment deleted successfully']);
    }
}