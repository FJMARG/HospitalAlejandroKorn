<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Localidad
 *
 * @ORM\Table(name="localidad", indexes={@ORM\Index(name="FK_partido_id", columns={"partido_id"})})
 * @ORM\Entity
 */
class Localidad
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
     * @var \Partido
     *
     * @ORM\ManyToOne(targetEntity="Partido")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="partido_id", referencedColumnName="id")
     * })
     */
    private $partido;



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
     * @return Localidad
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
     * Set partido.
     *
     * @param \Partido|null $partido
     *
     * @return Localidad
     */
    public function setPartido(\Partido $partido = null)
    {
        $this->partido = $partido;

        return $this;
    }

    /**
     * Get partido.
     *
     * @return \Partido|null
     */
    public function getPartido()
    {
        return $this->partido;
    }
}
