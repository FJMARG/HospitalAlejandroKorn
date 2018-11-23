<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Paciente
 *
 * @ORM\Table(name="paciente", indexes={@ORM\Index(name="FK_region_sanitaria_id", columns={"region_sanitaria_id"}), @ORM\Index(name="FK_obra_social_id", columns={"obra_social_id"}), @ORM\Index(name="FK_tipo_doc_id", columns={"tipo_doc_id"}), @ORM\Index(name="FK_localidad_id", columns={"localidad_id"}), @ORM\Index(name="FK_genero_id", columns={"genero_id"})})
 * @ORM\Entity
 */
class Paciente
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
     * @ORM\Column(name="apellido", type="string", length=255, nullable=false)
     */
    private $apellido;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255, nullable=false)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_nac", type="date", nullable=false)
     */
    private $fechaNac;

    /**
     * @var string|null
     *
     * @ORM\Column(name="lugar_nac", type="string", length=250, nullable=true)
     */
    private $lugarNac;

    /**
     * @var string
     *
     * @ORM\Column(name="domicilio", type="string", length=255, nullable=false)
     */
    private $domicilio;

    /**
     * @var bool
     *
     * @ORM\Column(name="tiene_documento", type="boolean", nullable=false, options={"default"="1"})
     */
    private $tieneDocumento = '1';

    /**
     * @var int
     *
     * @ORM\Column(name="numero", type="integer", nullable=false)
     */
    private $numero;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=false)
     */
    private $tel;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nro_historia_clinica", type="integer", nullable=true)
     */
    private $nroHistoriaClinica;

    /**
     * @var int|null
     *
     * @ORM\Column(name="nro_carpeta", type="integer", nullable=true)
     */
    private $nroCarpeta;

    /**
     * @var \Genero
     *
     * @ORM\ManyToOne(targetEntity="Genero")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="genero_id", referencedColumnName="id")
     * })
     */
    private $genero;

    /**
     * @var \Localidad
     *
     * @ORM\ManyToOne(targetEntity="Localidad")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="localidad_id", referencedColumnName="id")
     * })
     */
    private $localidad;

    /**
     * @var \ObraSocial
     *
     * @ORM\ManyToOne(targetEntity="ObraSocial")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="obra_social_id", referencedColumnName="id")
     * })
     */
    private $obraSocial;

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
     * @var \TipoDocumento
     *
     * @ORM\ManyToOne(targetEntity="TipoDocumento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tipo_doc_id", referencedColumnName="id")
     * })
     */
    private $tipoDoc;



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
     * Set apellido.
     *
     * @param string $apellido
     *
     * @return Paciente
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido.
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Paciente
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
     * Set fechaNac.
     *
     * @param \DateTime $fechaNac
     *
     * @return Paciente
     */
    public function setFechaNac($fechaNac)
    {
        $this->fechaNac = $fechaNac;

        return $this;
    }

    /**
     * Get fechaNac.
     *
     * @return \DateTime
     */
    public function getFechaNac()
    {
        return $this->fechaNac;
    }

    /**
     * Set lugarNac.
     *
     * @param string|null $lugarNac
     *
     * @return Paciente
     */
    public function setLugarNac($lugarNac = null)
    {
        $this->lugarNac = $lugarNac;

        return $this;
    }

    /**
     * Get lugarNac.
     *
     * @return string|null
     */
    public function getLugarNac()
    {
        return $this->lugarNac;
    }

    /**
     * Set domicilio.
     *
     * @param string $domicilio
     *
     * @return Paciente
     */
    public function setDomicilio($domicilio)
    {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio.
     *
     * @return string
     */
    public function getDomicilio()
    {
        return $this->domicilio;
    }

    /**
     * Set tieneDocumento.
     *
     * @param bool $tieneDocumento
     *
     * @return Paciente
     */
    public function setTieneDocumento($tieneDocumento)
    {
        $this->tieneDocumento = $tieneDocumento;

        return $this;
    }

    /**
     * Get tieneDocumento.
     *
     * @return bool
     */
    public function getTieneDocumento()
    {
        return $this->tieneDocumento;
    }

    /**
     * Set numero.
     *
     * @param int $numero
     *
     * @return Paciente
     */
    public function setNumero($numero)
    {
        $this->numero = $numero;

        return $this;
    }

    /**
     * Get numero.
     *
     * @return int
     */
    public function getNumero()
    {
        return $this->numero;
    }

    /**
     * Set tel.
     *
     * @param string $tel
     *
     * @return Paciente
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel.
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set nroHistoriaClinica.
     *
     * @param int|null $nroHistoriaClinica
     *
     * @return Paciente
     */
    public function setNroHistoriaClinica($nroHistoriaClinica = null)
    {
        $this->nroHistoriaClinica = $nroHistoriaClinica;

        return $this;
    }

    /**
     * Get nroHistoriaClinica.
     *
     * @return int|null
     */
    public function getNroHistoriaClinica()
    {
        return $this->nroHistoriaClinica;
    }

    /**
     * Set nroCarpeta.
     *
     * @param int|null $nroCarpeta
     *
     * @return Paciente
     */
    public function setNroCarpeta($nroCarpeta = null)
    {
        $this->nroCarpeta = $nroCarpeta;

        return $this;
    }

    /**
     * Get nroCarpeta.
     *
     * @return int|null
     */
    public function getNroCarpeta()
    {
        return $this->nroCarpeta;
    }

    /**
     * Set genero.
     *
     * @param \Genero|null $genero
     *
     * @return Paciente
     */
    public function setGenero(\Genero $genero = null)
    {
        $this->genero = $genero;

        return $this;
    }

    /**
     * Get genero.
     *
     * @return \Genero|null
     */
    public function getGenero()
    {
        return $this->genero;
    }

    /**
     * Set localidad.
     *
     * @param \Localidad|null $localidad
     *
     * @return Paciente
     */
    public function setLocalidad(\Localidad $localidad = null)
    {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad.
     *
     * @return \Localidad|null
     */
    public function getLocalidad()
    {
        return $this->localidad;
    }

    /**
     * Set obraSocial.
     *
     * @param \ObraSocial|null $obraSocial
     *
     * @return Paciente
     */
    public function setObraSocial(\ObraSocial $obraSocial = null)
    {
        $this->obraSocial = $obraSocial;

        return $this;
    }

    /**
     * Get obraSocial.
     *
     * @return \ObraSocial|null
     */
    public function getObraSocial()
    {
        return $this->obraSocial;
    }

    /**
     * Set regionSanitaria.
     *
     * @param \RegionSanitaria|null $regionSanitaria
     *
     * @return Paciente
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
     * Set tipoDoc.
     *
     * @param \TipoDocumento|null $tipoDoc
     *
     * @return Paciente
     */
    public function setTipoDoc(\TipoDocumento $tipoDoc = null)
    {
        $this->tipoDoc = $tipoDoc;

        return $this;
    }

    /**
     * Get tipoDoc.
     *
     * @return \TipoDocumento|null
     */
    public function getTipoDoc()
    {
        return $this->tipoDoc;
    }
}
