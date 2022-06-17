<?php

namespace App\Entity;

use App\Repository\ActivoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ActivoRepository::class)
 */
class Activo
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
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity=Incidencia::class, mappedBy="activo")
     */
    private $incidencias;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descripcion;

    public function __construct()
    {
        $this->incidencias = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    /**
     * @Groups({"searchable"})
     */
    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(?string $nombre): self
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * @return Collection<int, Incidencia>
     */
    public function getIncidencias(): Collection
    {
        return $this->incidencias;
    }

    public function addIncidencia(Incidencia $incidencia): self
    {
        if (!$this->incidencias->contains($incidencia)) {
            $this->incidencias[] = $incidencia;
            $incidencia->setActivo($this);
        }

        return $this;
    }

    public function removeIncidencia(Incidencia $incidencia): self
    {
        if ($this->incidencias->removeElement($incidencia)) {
            // set the owning side to null (unless already changed)
            if ($incidencia->getActivo() === $this) {
                $incidencia->setActivo(null);
            }
        }

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
    public function __toString() {
        return $this->nombre;
    }
}
