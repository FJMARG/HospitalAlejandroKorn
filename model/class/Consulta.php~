<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Consulta
 *
 * @ORM\Table(name="consulta", indexes={@ORM\Index(name="FK_paciente_id", columns={"paciente_id"}), @ORM\Index(name="FK_motivo_id", columns={"motivo_id"}), @ORM\Index(name="FK_derivacion_id", columns={"derivacion_id"}), @ORM\Index(name="FK_tratamiento_farmacologico_id", columns={"tratamiento_farmacologico_id"}), @ORM\Index(name="FK_acompanamiento_id", columns={"acompanamiento_id"})})
 * @ORM\Entity
 */
class Consulta
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
     * @var \DateTime
     *
     * @ORM\Column(name="fecha", type="date", nullable=false)
     */
    private $fecha;

    /**
     * @var string|null
     *
     * @ORM\Column(name="articulacion_con_instituciones", type="string", length=255, nullable=true)
     */
    private $articulacionConInstituciones;

    /**
     * @var bool
     *
     * @ORM\Column(name="internacion", type="boolean", nullable=false)
     */
    private $internacion = '0';

    /**
     * @var string|null
     *
     * @ORM\Column(name="diagnostico", type="string", length=255, nullable=true)
     */
    private $diagnostico;

    /**
     * @var string|null
     *
     * @ORM\Column(name="observaciones", type="string", length=255, nullable=true)
     */
    private $observaciones;

    /**
     * @var \Acompanamiento
     *
     * @ORM\ManyToOne(targetEntity="Acompanamiento")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="acompanamiento_id", referencedColumnName="id")
     * })
     */
    private $acompanamiento;

    /**
     * @var \Institucion
     *
     * @ORM\ManyToOne(targetEntity="Institucion")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="derivacion_id", referencedColumnName="id")
     * })
     */
    private $derivacion;

    /**
     * @var \MotivoConsulta
     *
     * @ORM\ManyToOne(targetEntity="MotivoConsulta")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="motivo_id", referencedColumnName="id")
     * })
     */
    private $motivo;

    /**
     * @var \Paciente
     *
     * @ORM\ManyToOne(targetEntity="Paciente")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="paciente_id", referencedColumnName="id")
     * })
     */
    private $paciente;

    /**
     * @var \TratamientoFarmacologico
     *
     * @ORM\ManyToOne(targetEntity="TratamientoFarmacologico")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="tratamiento_farmacologico_id", referencedColumnName="id")
     * })
     */
    private $tratamientoFarmacologico;



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
     * Set fecha.
     *
     * @param \DateTime $fecha
     *
     * @return Consulta
     */
    public function setFecha($fecha)
    {
        $this->fecha = $fecha;

        return $this;
    }

    /**
     * Get fecha.
     *
     * @return \DateTime
     */
    public function getFecha()
    {
        return $this->fecha;
    }

    /**
     * Set articulacionConInstituciones.
     *
     * @param string|null $articulacionConInstituciones
     *
     * @return Consulta
     */
    public function setArticulacionConInstituciones($articulacionConInstituciones = null)
    {
        $this->articulacionConInstituciones = $articulacionConInstituciones;

        return $this;
    }

    /**
     * Get articulacionConInstituciones.
     *
     * @return string|null
     */
    public function getArticulacionConInstituciones()
    {
        return $this->articulacionConInstituciones;
    }

    /**
     * Set internacion.
     *
     * @param bool $internacion
     *
     * @return Consulta
     */
    public function setInternacion($internacion)
    {
        $this->internacion = $internacion;

        return $this;
    }

    /**
     * Get internacion.
     *
     * @return bool
     */
    public function getInternacion()
    {
        return $this->internacion;
    }

    /**
     * Set diagnostico.
     *
     * @param string|null $diagnostico
     *
     * @return Consulta
     */
    public function setDiagnostico($diagnostico = null)
    {
        $this->diagnostico = $diagnostico;

        return $this;
    }

    /**
     * Get diagnostico.
     *
     * @return string|null
     */
    public function getDiagnostico()
    {
        return $this->diagnostico;
    }

    /**
     * Set observaciones.
     *
     * @param string|null $observaciones
     *
     * @return Consulta
     */
    public function setObservaciones($observaciones = null)
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    /**
     * Get observaciones.
     *
     * @return string|null
     */
    public function getObservaciones()
    {
        return $this->observaciones;
    }

    /**
     * Set acompanamiento.
     *
     * @param \Acompanamiento|null $acompanamiento
     *
     * @return Consulta
     */
    public function setAcompanamiento(\Acompanamiento $acompanamiento = null)
    {
        $this->acompanamiento = $acompanamiento;

        return $this;
    }

    /**
     * Get acompanamiento.
     *
     * @return \Acompanamiento|null
     */
    public function getAcompanamiento()
    {
        return $this->acompanamiento;
    }

    /**
     * Set derivacion.
     *
     * @param \Institucion|null $derivacion
     *
     * @return Consulta
     */
    public function setDerivacion(\Institucion $derivacion = null)
    {
        $this->derivacion = $derivacion;

        return $this;
    }

    /**
     * Get derivacion.
     *
     * @return \Institucion|null
     */
    public function getDerivacion()
    {
        return $this->derivacion;
    }

    /**
     * Set motivo.
     *
     * @param \MotivoConsulta|null $motivo
     *
     * @return Consulta
     */
    public function setMotivo(\MotivoConsulta $motivo = null)
    {
        $this->motivo = $motivo;

        return $this;
    }

    /**
     * Get motivo.
     *
     * @return \MotivoConsulta|null
     */
    public function getMotivo()
    {
        return $this->motivo;
    }

    /**
     * Set paciente.
     *
     * @param \Paciente|null $paciente
     *
     * @return Consulta
     */
    public function setPaciente(\Paciente $paciente = null)
    {
        $this->paciente = $paciente;

        return $this;
    }

    /**
     * Get paciente.
     *
     * @return \Paciente|null
     */
    public function getPaciente()
    {
        return $this->paciente;
    }

    /**
     * Set tratamientoFarmacologico.
     *
     * @param \TratamientoFarmacologico|null $tratamientoFarmacologico
     *
     * @return Consulta
     */
    public function setTratamientoFarmacologico(\TratamientoFarmacologico $tratamientoFarmacologico = null)
    {
        $this->tratamientoFarmacologico = $tratamientoFarmacologico;

        return $this;
    }

    /**
     * Get tratamientoFarmacologico.
     *
     * @return \TratamientoFarmacologico|null
     */
    public function getTratamientoFarmacologico()
    {
        return $this->tratamientoFarmacologico;
    }
}
