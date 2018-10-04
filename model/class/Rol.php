<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Rol
 *
 * @ORM\Table(name="rol")
 * @ORM\Entity
 */
class Rol
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
     * @ORM\ManyToMany(targetEntity="Permiso", inversedBy="rol")
     * @ORM\JoinTable(name="rol_tiene_permiso",
     *   joinColumns={
     *     @ORM\JoinColumn(name="rol_id", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="permiso_id", referencedColumnName="id")
     *   }
     * )
     */
    private $permiso;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="Usuario", mappedBy="rol")
     */
    private $usuario;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->permiso = new \Doctrine\Common\Collections\ArrayCollection();
        $this->usuario = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @return Rol
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
     * Add permiso.
     *
     * @param \Permiso $permiso
     *
     * @return Rol
     */
    public function addPermiso(\Permiso $permiso)
    {
        $this->permiso[] = $permiso;

        return $this;
    }

    /**
     * Remove permiso.
     *
     * @param \Permiso $permiso
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePermiso(\Permiso $permiso)
    {
        return $this->permiso->removeElement($permiso);
    }

    /**
     * Get permiso.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * Add usuario.
     *
     * @param \Usuario $usuario
     *
     * @return Rol
     */
    public function addUsuario(\Usuario $usuario)
    {
        $this->usuario[] = $usuario;

        return $this;
    }

    /**
     * Remove usuario.
     *
     * @param \Usuario $usuario
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeUsuario(\Usuario $usuario)
    {
        return $this->usuario->removeElement($usuario);
    }

    /**
     * Get usuario.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsuario()
    {
        return $this->usuario;
    }
}
