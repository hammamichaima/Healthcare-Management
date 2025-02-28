<?php

namespace App\Entity;

use App\Repository\PrescriptionRepository;
use Doctrine\ORM\Mapping as ORM;
use DateTime;

#[ORM\Entity(repositoryClass: PrescriptionRepository::class)]
class Prescription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Patient::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(targetEntity: Doctor::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;

    #[ORM\Column(type: "string")]
    private ?string $medicationDetails = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }
// Temporary change to force GitHub to recognize differences

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getMedicationDetails(): ?string
    {
        return $this->medicationDetails;
    }

    public function setMedicationDetails(string $medicationDetails): self
    {
        $this->medicationDetails = $medicationDetails;

        return $this;
    }
    // not working

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}
