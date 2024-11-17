<?php

namespace App\Entity\CRM;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRM\ColectivaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColectivaRepository::class)]
#[ApiResource]
class Colectiva
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'colectivas')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Temporada $temporada = null;

    #[ORM\Column(length: 64)]
    private ?string $c_nombre = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $c_miembros = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $c_fecha_creacion = null;

    #[ORM\Column]
    private ?bool $c_estado = null;

    /**
     * @var Collection<int, Taller>
     */
    #[ORM\OneToMany(targetEntity: Taller::class, mappedBy: 'colectiva', orphanRemoval: true)]
    private Collection $talleres;

    public function __construct()
    {
        $this->talleres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTemporada(): ?Temporada
    {
        return $this->temporada;
    }

    public function setTemporada(?Temporada $temporada): static
    {
        $this->temporada = $temporada;

        return $this;
    }

    public function getCNombre(): ?string
    {
        return $this->c_nombre;
    }

    public function setCNombre(string $c_nombre): static
    {
        $this->c_nombre = $c_nombre;

        return $this;
    }

    public function getCMiembros(): ?string
    {
        return $this->c_miembros;
    }

    public function setCMiembros(?string $c_miembros): static
    {
        $this->c_miembros = $c_miembros;

        return $this;
    }

    public function getCFechaCreacion(): ?\DateTimeInterface
    {
        return $this->c_fecha_creacion;
    }

    public function setCFechaCreacion(\DateTimeInterface $c_fecha_creacion): static
    {
        $this->c_fecha_creacion = $c_fecha_creacion;

        return $this;
    }

    public function isCEstado(): ?bool
    {
        return $this->c_estado;
    }

    public function setCEstado(bool $c_estado): static
    {
        $this->c_estado = $c_estado;

        return $this;
    }

    /**
     * @return Collection<int, Taller>
     */
    public function getTalleres(): Collection
    {
        return $this->talleres;
    }

    public function addTallere(Taller $tallere): static
    {
        if (!$this->talleres->contains($tallere)) {
            $this->talleres->add($tallere);
            $tallere->setColectiva($this);
        }

        return $this;
    }

    public function removeTallere(Taller $tallere): static
    {
        if ($this->talleres->removeElement($tallere)) {
            // set the owning side to null (unless already changed)
            if ($tallere->getColectiva() === $this) {
                $tallere->setColectiva(null);
            }
        }

        return $this;
    }
}
