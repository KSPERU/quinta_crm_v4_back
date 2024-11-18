<?php

namespace App\Entity\CRM;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRM\TemporadaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TemporadaRepository::class)]
#[ApiResource]
class Temporada
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'temporadas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Programa $programa = null;

    #[ORM\Column(length: 64)]
    private ?string $tem_nombre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $tem_fecha_creacion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $tem_fecha_inicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $tem_fecha_fin = null;

    #[ORM\Column]
    private ?bool $tem_estado = null;

    /**
     * @var Collection<int, Colectiva>
     */
    #[ORM\OneToMany(targetEntity: Colectiva::class, mappedBy: 'temporada', orphanRemoval: true)]
    private Collection $colectivas;

    #[ORM\Column(nullable: true)]
    private ?int $tem_estado_sys = null;

    public function __construct()
    {
        $this->colectivas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrograma(): ?Programa
    {
        return $this->programa;
    }

    public function setPrograma(?Programa $programa): static
    {
        $this->programa = $programa;

        return $this;
    }

    public function getTemNombre(): ?string
    {
        return $this->tem_nombre;
    }

    public function setTemNombre(string $tem_nombre): static
    {
        $this->tem_nombre = $tem_nombre;

        return $this;
    }

    public function getTemFechaCreacion(): ?\DateTimeInterface
    {
        return $this->tem_fecha_creacion;
    }

    public function setTemFechaCreacion(\DateTimeInterface $tem_fecha_creacion): static
    {
        $this->tem_fecha_creacion = $tem_fecha_creacion;

        return $this;
    }

    public function getTemFechaInicio(): ?\DateTimeInterface
    {
        return $this->tem_fecha_inicio;
    }

    public function setTemFechaInicio(\DateTimeInterface $tem_fecha_inicio): static
    {
        $this->tem_fecha_inicio = $tem_fecha_inicio;

        return $this;
    }

    public function getTemFechaFin(): ?\DateTimeInterface
    {
        return $this->tem_fecha_fin;
    }

    public function setTemFechaFin(\DateTimeInterface $tem_fecha_fin): static
    {
        $this->tem_fecha_fin = $tem_fecha_fin;

        return $this;
    }

    public function isTemEstado(): ?bool
    {
        return $this->tem_estado;
    }

    public function setTemEstado(bool $tem_estado): static
    {
        $this->tem_estado = $tem_estado;

        return $this;
    }

    /**
     * @return Collection<int, Colectiva>
     */
    public function getColectivas(): Collection
    {
        return $this->colectivas;
    }

    public function addColectiva(Colectiva $colectiva): static
    {
        if (!$this->colectivas->contains($colectiva)) {
            $this->colectivas->add($colectiva);
            $colectiva->setTemporada($this);
        }

        return $this;
    }

    public function removeColectiva(Colectiva $colectiva): static
    {
        if ($this->colectivas->removeElement($colectiva)) {
            // set the owning side to null (unless already changed)
            if ($colectiva->getTemporada() === $this) {
                $colectiva->setTemporada(null);
            }
        }

        return $this;
    }

    public function getTemEstadoSys(): ?int
    {
        return $this->tem_estado_sys;
    }

    public function setTemEstadoSys(?int $tem_estado_sys): static
    {
        $this->tem_estado_sys = $tem_estado_sys;

        return $this;
    }
}
