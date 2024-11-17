<?php

namespace App\Entity\CRM;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRM\ProgramaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProgramaRepository::class)]
#[ApiResource]
class Programa
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private ?string $pg_nombre = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $pg_imagen = null;

    #[ORM\Column(length: 64)]
    private ?string $pg_organizacion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pg_fecha_creacion = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pg_fecha_inicio = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $pg_fecha_fin = null;

    #[ORM\Column]
    private ?bool $pg_estado = null;

    /**
     * @var Collection<int, Temporada>
     */
    #[ORM\OneToMany(targetEntity: Temporada::class, mappedBy: 'programa', orphanRemoval: true)]
    private Collection $temporadas;

    public function __construct()
    {
        $this->temporadas = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPgNombre(): ?string
    {
        return $this->pg_nombre;
    }

    public function setPgNombre(string $pg_nombre): static
    {
        $this->pg_nombre = $pg_nombre;

        return $this;
    }

    public function getPgImagen(): ?string
    {
        return $this->pg_imagen;
    }

    public function setPgImagen(?string $pg_imagen): static
    {
        $this->pg_imagen = $pg_imagen;

        return $this;
    }

    public function getPgOrganizacion(): ?string
    {
        return $this->pg_organizacion;
    }

    public function setPgOrganizacion(string $pg_organizacion): static
    {
        $this->pg_organizacion = $pg_organizacion;

        return $this;
    }

    public function getPgFechaCreacion(): ?\DateTimeInterface
    {
        return $this->pg_fecha_creacion;
    }

    public function setPgFechaCreacion(\DateTimeInterface $pg_fecha_creacion): static
    {
        $this->pg_fecha_creacion = $pg_fecha_creacion;

        return $this;
    }

    public function getPgFechaInicio(): ?\DateTimeInterface
    {
        return $this->pg_fecha_inicio;
    }

    public function setPgFechaInicio(\DateTimeInterface $pg_fecha_inicio): static
    {
        $this->pg_fecha_inicio = $pg_fecha_inicio;

        return $this;
    }

    public function getPgFechaFin(): ?\DateTimeInterface
    {
        return $this->pg_fecha_fin;
    }

    public function setPgFechaFin(\DateTimeInterface $pg_fecha_fin): static
    {
        $this->pg_fecha_fin = $pg_fecha_fin;

        return $this;
    }

    public function isPgEstado(): ?bool
    {
        return $this->pg_estado;
    }

    public function setPgEstado(bool $pg_estado): static
    {
        $this->pg_estado = $pg_estado;

        return $this;
    }

    /**
     * @return Collection<int, Temporada>
     */
    public function getTemporadas(): Collection
    {
        return $this->temporadas;
    }

    public function addTemporada(Temporada $temporada): static
    {
        if (!$this->temporadas->contains($temporada)) {
            $this->temporadas->add($temporada);
            $temporada->setPrograma($this);
        }

        return $this;
    }

    public function removeTemporada(Temporada $temporada): static
    {
        if ($this->temporadas->removeElement($temporada)) {
            // set the owning side to null (unless already changed)
            if ($temporada->getPrograma() === $this) {
                $temporada->setPrograma(null);
            }
        }

        return $this;
    }
}
