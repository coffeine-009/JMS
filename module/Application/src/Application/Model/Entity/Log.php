<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Log
 *
 * @ORM\Table(name="log", indexes={@ORM\Index(name="id_log_type", columns={"id_log_type"})})
 * @ORM\Entity
 */
class Log
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="id_user", type="integer", nullable=true)
     */
    private $idUser;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=128, nullable=true)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="os", type="string", length=32, nullable=true)
     */
    private $os;

    /**
     * @var string
     *
     * @ORM\Column(name="browser", type="string", length=32, nullable=true)
     */
    private $browser;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=false)
     */
    private $creation = 'CURRENT_TIMESTAMP';

    /**
     * @var \Application\Model\Entity\LogType
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\LogType")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_log_type", referencedColumnName="id")
     * })
     */
    private $idLogType;


}
