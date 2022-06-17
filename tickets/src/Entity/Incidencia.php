<?php

namespace App\Entity;

use App\Repository\IncidenciaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=IncidenciaRepository::class)
 */
class Incidencia
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, nullable=true)
     */
    private $gravedad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Assert\NotBlank
     */
    private $titulo;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $tipo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $localizacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Activo::class, inversedBy="incidencias")
     */
    private $activo;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="incidencias")
     */
    private $usuarioCliente;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class)
     */
    private $usuarioTecnico;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $estado;

    /**
     * @ORM\OneToMany(targetEntity=Registro::class, mappedBy="incidencia")
     */
    private $registros;

    public function __construct()
    {
        $this->registros = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGravedad(): ?string
    {
        return $this->gravedad;
    }

    public function setGravedad(?string $gravedad): self
    {
        $this->gravedad = $gravedad;

        return $this;
    }

    public function getDescripcion(): ?string
    {
        return $this->descripcion;
    }

    public function setDescripcion(?string $descripcion): self
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    public function getTitulo(): ?string
    {
        return $this->titulo;
    }

    public function setTitulo(?string $titulo): self
    {
        $this->titulo = $titulo;

        return $this;
    }

    public function getTipo(): ?string
    {
        return $this->tipo;
    }

    public function setTipo(?string $tipo): self
    {
        $this->tipo = $tipo;

        return $this;
    }

    public function getLocalizacion(): ?string
    {
        return $this->localizacion;
    }

    public function setLocalizacion(?string $localizacion): self
    {
        $this->localizacion = $localizacion;

        return $this;
    }

    public function getFecha(): ?\DateTimeInterface
    {
        return $this->fecha;
    }

    public function setFecha(?\DateTimeInterface $fecha): self
    {
        $this->fecha = $fecha;

        return $this;
    }

    public function getActivo(): ?Activo
    {
        return $this->activo;
    }

    public function setActivo(?Activo $activo): self
    {
        $this->activo = $activo;

        return $this;
    }

    public function getUsuarioCliente(): ?Usuarios
    {
        return $this->usuarioCliente;
    }

    public function setUsuarioCliente(?Usuarios $usuarioCliente): self
    {
        $this->usuarioCliente = $usuarioCliente;

        return $this;
    }

    public function getUsuarioTecnico(): ?Usuarios
    {
        return $this->usuarioTecnico;
    }

    public function setUsuarioTecnico(?Usuarios $usuarioTecnico): self
    {
        $this->usuarioTecnico = $usuarioTecnico;

        return $this;
    }

    public function getEstado(): ?string
    {
        return $this->estado;
    }

    public function setEstado(?string $estado): self
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * @return Collection<int, Registro>
     */
    public function getRegistros(): Collection
    {
        return $this->registros;
    }

    public function addRegistro(Registro $registro): self
    {
        if (!$this->registros->contains($registro)) {
            $this->registros[] = $registro;
            $registro->setIncidencia($this);
        }

        return $this;
    }

    public function removeRegistro(Registro $registro): self
    {
        if ($this->registros->removeElement($registro)) {
            // set the owning side to null (unless already changed)
            if ($registro->getIncidencia() === $this) {
                $registro->setIncidencia(null);
            }
        }

        return $this;
    }
    public function __toString() {
        return $this->titulo;
    }
}
