<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Permiso
 *
 * @ORM\Table(name="permiso")
 * @ORM\Entity
 */
class Permiso
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Rol", mappedBy="permiso")
     */
    private $rol;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->rol = new \Doctrine\Common\Collections\ArrayCollection();
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Permiso
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add rol.
     *
     * @param \Rol $rol
     *
     * @return Permiso
     */
    public function addRol(\Rol $rol)
    {
        $this->rol[] = $rol;

        return $this;
    }

    /**
     * Remove rol.
     *
     * @param \Rol $rol
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRol(\Rol $rol)
    {
        return $this->rol->removeElement($rol);
    }

    /**
     * Get rol.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRol()
    {
        return $this->rol;
    }
}
