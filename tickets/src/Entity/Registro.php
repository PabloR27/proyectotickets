<?php

namespace App\Entity;

use App\Repository\RegistroRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RegistroRepository::class)
 */
class Registro
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $texto;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $fecha;

    /**
     * @ORM\ManyToOne(targetEntity=Incidencia::class, inversedBy="registros")
     */
    private $incidencia;

    /**
     * @ORM\ManyToOne(targetEntity=Usuarios::class, inversedBy="registros")
     */
    private $usuario;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $gravedad;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $cambioestado;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTexto(): ?string
    {
        return $this->texto;
    }

    public function setTexto(?string $texto): self
    {
        $this->texto = $texto;

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

    public function getIncidencia(): ?Incidencia
    {
        return $this->incidencia;
    }

    public function setIncidencia(?Incidencia $incidencia): self
    {
        $this->incidencia = $incidencia;

        return $this;
    }

    public function getUsuario(): ?Usuarios
    {
        return $this->usuario;
    }

    public function setUsuario(?Usuarios $usuario): self
    {
        $this->usuario = $usuario;

        return $this;
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

    public function getCambioestado(): ?string
    {
        return $this->cambioestado;
    }

    public function setCambioestado(?string $cambioestado): self
    {
        $this->cambioestado = $cambioestado;

        return $this;
    }
    public function __toString() {
        return $this->texto;
    }
}
