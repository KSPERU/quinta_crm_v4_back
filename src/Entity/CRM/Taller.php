<?php

namespace App\Entity\CRM;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRM\TallerRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TallerRepository::class)]
#[ApiResource]
class Taller
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'talleres')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Colectiva $colectiva = null;

    #[ORM\Column(length: 64)]
    private ?string $t_nombre = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $t_fecha_hora = null;

    #[ORM\Column]
    private ?int $t_modalidad = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $t_tipo_taller = null;

    #[ORM\Column]
    private ?bool $t_estado = null;

    /**
     * @var Collection<int, ColaboradorTaller>
     */
    #[ORM\OneToMany(targetEntity: ColaboradorTaller::class, mappedBy: 'talleres', orphanRemoval: true)]
    private Collection $colaboradoresTaller;

    public function __construct()
    {
        $this->colaboradoresTaller = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getColectiva(): ?Colectiva
    {
        return $this->colectiva;
    }

    public function setColectiva(?Colectiva $colectiva): static
    {
        $this->colectiva = $colectiva;

        return $this;
    }

    public function getTNombre(): ?string
    {
        return $this->t_nombre;
    }

    public function setTNombre(string $t_nombre): static
    {
        $this->t_nombre = $t_nombre;

        return $this;
    }

    public function getTFechaHora(): ?\DateTimeInterface
    {
        return $this->t_fecha_hora;
    }

    public function setTFechaHora(\DateTimeInterface $t_fecha_hora): static
    {
        $this->t_fecha_hora = $t_fecha_hora;

        return $this;
    }

    public function getTModalidad(): ?int
    {
        return $this->t_modalidad;
    }

    public function setTModalidad(int $t_modalidad): static
    {
        $this->t_modalidad = $t_modalidad;

        return $this;
    }

    public function getTTipoTaller(): ?string
    {
        return $this->t_tipo_taller;
    }

    public function setTTipoTaller(?string $t_tipo_taller): static
    {
        $this->t_tipo_taller = $t_tipo_taller;

        return $this;
    }

    public function isTEstado(): ?bool
    {
        return $this->t_estado;
    }

    public function setTEstado(bool $t_estado): static
    {
        $this->t_estado = $t_estado;

        return $this;
    }

    /**
     * @return Collection<int, ColaboradorTaller>
     */
    public function getColaboradoresTaller(): Collection
    {
        return $this->colaboradoresTaller;
    }

    public function addColaboradoresTaller(ColaboradorTaller $colaboradoresTaller): static
    {
        if (!$this->colaboradoresTaller->contains($colaboradoresTaller)) {
            $this->colaboradoresTaller->add($colaboradoresTaller);
            $colaboradoresTaller->setTalleres($this);
        }

        return $this;
    }

    public function removeColaboradoresTaller(ColaboradorTaller $colaboradoresTaller): static
    {
        if ($this->colaboradoresTaller->removeElement($colaboradoresTaller)) {
            // set the owning side to null (unless already changed)
            if ($colaboradoresTaller->getTalleres() === $this) {
                $colaboradoresTaller->setTalleres(null);
            }
        }

        return $this;
    }
}
