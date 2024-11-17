<?php

namespace App\Entity\CRM;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CRM\ColaboradorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ColaboradorRepository::class)]
#[ApiResource]
class Colaborador
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'colaboradores')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Usuario $usuario = null;

    #[ORM\Column(length: 64)]
    private ?string $c_nombres = null;

    #[ORM\Column(length: 64)]
    private ?string $c_apellido_paterno = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $c_apellido_materno = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $c_fecha_nacimiento = null;

    #[ORM\Column(length: 9, nullable: true)]
    private ?string $c_telefono = null;

    #[ORM\Column(length: 64, nullable: true)]
    private ?string $c_correo = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $c_foto = null;

    #[ORM\Column(length: 32, nullable: true)]
    private ?string $c_genero = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $c_fecha_creacion = null;

    #[ORM\Column]
    private ?bool $c_estado = null;

    /**
     * @var Collection<int, ColaboradorTaller>
     */
    #[ORM\OneToMany(targetEntity: ColaboradorTaller::class, mappedBy: 'colaboradores', orphanRemoval: true)]
    private Collection $colaboradorTalleres;

    public function __construct()
    {
        $this->colaboradorTalleres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsuario(): ?Usuario
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuario $usuario): static
    {
        $this->usuario = $usuario;

        return $this;
    }

    public function getCNombres(): ?string
    {
        return $this->c_nombres;
    }

    public function setCNombres(string $c_nombres): static
    {
        $this->c_nombres = $c_nombres;

        return $this;
    }

    public function getCApellidoPaterno(): ?string
    {
        return $this->c_apellido_paterno;
    }

    public function setCApellidoPaterno(string $c_apellido_paterno): static
    {
        $this->c_apellido_paterno = $c_apellido_paterno;

        return $this;
    }

    public function getCApellidoMaterno(): ?string
    {
        return $this->c_apellido_materno;
    }

    public function setCApellidoMaterno(?string $c_apellido_materno): static
    {
        $this->c_apellido_materno = $c_apellido_materno;

        return $this;
    }

    public function getCFechaNacimiento(): ?\DateTimeInterface
    {
        return $this->c_fecha_nacimiento;
    }

    public function setCFechaNacimiento(?\DateTimeInterface $c_fecha_nacimiento): static
    {
        $this->c_fecha_nacimiento = $c_fecha_nacimiento;

        return $this;
    }

    public function getCTelefono(): ?string
    {
        return $this->c_telefono;
    }

    public function setCTelefono(?string $c_telefono): static
    {
        $this->c_telefono = $c_telefono;

        return $this;
    }

    public function getCCorreo(): ?string
    {
        return $this->c_correo;
    }

    public function setCCorreo(?string $c_correo): static
    {
        $this->c_correo = $c_correo;

        return $this;
    }

    public function getCFoto(): ?string
    {
        return $this->c_foto;
    }

    public function setCFoto(?string $c_foto): static
    {
        $this->c_foto = $c_foto;

        return $this;
    }

    public function getCGenero(): ?string
    {
        return $this->c_genero;
    }

    public function setCGenero(?string $c_genero): static
    {
        $this->c_genero = $c_genero;

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
     * @return Collection<int, ColaboradorTaller>
     */
    public function getColaboradorTalleres(): Collection
    {
        return $this->colaboradorTalleres;
    }

    public function addColaboradorTallere(ColaboradorTaller $colaboradorTallere): static
    {
        if (!$this->colaboradorTalleres->contains($colaboradorTallere)) {
            $this->colaboradorTalleres->add($colaboradorTallere);
            $colaboradorTallere->setColaboradores($this);
        }

        return $this;
    }

    public function removeColaboradorTallere(ColaboradorTaller $colaboradorTallere): static
    {
        if ($this->colaboradorTalleres->removeElement($colaboradorTallere)) {
            // set the owning side to null (unless already changed)
            if ($colaboradorTallere->getColaboradores() === $this) {
                $colaboradorTallere->setColaboradores(null);
            }
        }

        return $this;
    }
}
