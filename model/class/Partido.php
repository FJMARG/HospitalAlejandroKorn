<?php



use Doctrine\ORM\Mapping as ORM;

/**
 * Partido
 *
 * @ORM\Table(name="partido")
 * @ORM\Entity
 */
class Partido
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
     * @var int
     *
     * @ORM\Column(name="region_sanitaria_id", type="integer", nullable=false)
     */
    private $regionSanitariaId;



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
     * @return Partido
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
     * Set regionSanitariaId.
     *
     * @param int $regionSanitariaId
     *
     * @return Partido
     */
    public function setRegionSanitariaId($regionSanitariaId)
    {
        $this->regionSanitariaId = $regionSanitariaId;

        return $this;
    }

    /**
     * Get regionSanitariaId.
     *
     * @return int
     */
    public function getRegionSanitariaId()
    {
        return $this->regionSanitariaId;
    }
}
