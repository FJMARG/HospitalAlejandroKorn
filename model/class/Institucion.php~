<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Institucion
 *
 * @ORM\Table(name="institucion", indexes={@ORM\Index(name="FK_institucion_region_sanitaria_id", columns={"region_sanitaria_id"}), @ORM\Index(name="FK_tipo_institucion_id", columns={"tipo_institucion_id"})})
 * @ORM\Entity
 */
class Institucion
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
     * @var string
     *
     * @ORM\Column(name="director", type="string", length=255, nullable=false)
     */
    private $director;

    /**
     * @var string
     *
     * @ORM\Column(name="telefono", type="string", length=255, nullable=false)
     */
    private $telefono;

    /**
     * @var \RegionSanitaria
     *
     * @ORM\ManyToOne(targetEntity="RegionSanitaria")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="region_sanitaria_id", referencedColumnName="id")
     * })
     */
    private $regionSanitaria;

    /**
     * @var \TipoInstitucion
     *
     * @ORM\ManyToOne(targetEntity="TipoInstitucion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_institucion_id", referencedColumnName="id")
     * })
     */
    private $tipoInstitucion;



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
     * @return Institucion
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
     * Set director.
     *
     * @param string $director
     *
     * @return Institucion
     */
    public function setDirector($director)
    {
        $this->director = $director;

        return $this;
    }

    /**
     * Get director.
     *
     * @return string
     */
    public function getDirector()
    {
        return $this->director;
    }

    /**
     * Set telefono.
     *
     * @param string $telefono
     *
     * @return Institucion
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono.
     *
     * @return string
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set regionSanitaria.
     *
     * @param \RegionSanitaria|null $regionSanitaria
     *
     * @return Institucion
     */
    public function setRegionSanitaria(\RegionSanitaria $regionSanitaria = null)
    {
        $this->regionSanitaria = $regionSanitaria;

        return $this;
    }

    /**
     * Get regionSanitaria.
     *
     * @return \RegionSanitaria|null
     */
    public function getRegionSanitaria()
    {
        return $this->regionSanitaria;
    }

    /**
     * Set tipoInstitucion.
     *
     * @param \TipoInstitucion|null $tipoInstitucion
     *
     * @return Institucion
     */
    public function setTipoInstitucion(\TipoInstitucion $tipoInstitucion = null)
    {
        $this->tipoInstitucion = $tipoInstitucion;

        return $this;
    }

    /**
     * Get tipoInstitucion.
     *
     * @return \TipoInstitucion|null
     */
    public function getTipoInstitucion()
    {
        return $this->tipoInstitucion;
    }
}
