<?php

namespace App\Entity\CRM;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRM\ColaboradorTallerRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColaboradorTallerRepository::class)]
#[ApiResource]
class ColaboradorTaller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'colaboradorTalleres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colaborador $colaboradores = null;

    #[ORM\ManyToOne(inversedBy: 'colaboradoresTaller')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Taller $talleres = null;

    #[ORM\Column(nullable: true)]
    private ?int $ct_tipo_colaborador = null;

    #[ORM\Column(nullable: true)]
    private ?bool $ct_asistencia = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $ct_justificacion = null;

    #[ORM\Column]
    private ?bool $ct_estado = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColaboradores(): ?Colaborador
    {
        return $this->colaboradores;
    }

    public function setColaboradores(?Colaborador $colaboradores): static
    {
        $this->colaboradores = $colaboradores;

        return $this;
    }

    public function getTalleres(): ?Taller
    {
        return $this->talleres;
    }

    public function setTalleres(?Taller $talleres): static
    {
        $this->talleres = $talleres;

        return $this;
    }

    public function getCtTipoColaborador(): ?int
    {
        return $this->ct_tipo_colaborador;
    }

    public function setCtTipoColaborador(int $ct_tipo_colaborador): static
    {
        $this->ct_tipo_colaborador = $ct_tipo_colaborador;

        return $this;
    }

    public function isCtAsistencia(): ?bool
    {
        return $this->ct_asistencia;
    }

    public function setCtAsistencia(?bool $ct_asistencia): static
    {
        $this->ct_asistencia = $ct_asistencia;

        return $this;
    }

    public function getCtJustificacion(): ?string
    {
        return $this->ct_justificacion;
    }

    public function setCtJustificacion(?string $ct_justificacion): static
    {
        $this->ct_justificacion = $ct_justificacion;

        return $this;
    }

    public function isCtEstado(): ?bool
    {
        return $this->ct_estado;
    }

    public function setCtEstado(bool $ct_estado): static
    {
        $this->ct_estado = $ct_estado;

        return $this;
    }
}
