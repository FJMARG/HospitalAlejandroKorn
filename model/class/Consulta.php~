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


}
