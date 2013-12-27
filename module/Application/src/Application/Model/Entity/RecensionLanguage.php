<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * RecensionLanguage
 *
 * @ORM\Table(name="recension_language", indexes={@ORM\Index(name="id_recension", columns={"id_recension"})})
 * @ORM\Entity
 */
class RecensionLanguage
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
     * @var string
     *
     * @ORM\Column(name="code_language", type="string", length=2, nullable=false)
     */
    private $codeLanguage;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="string", length=1024, nullable=false)
     */
    private $text;

    /**
     * @var \Application\Model\Entity\Recension
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Recension")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_recension", referencedColumnName="id")
     * })
     */
    private $idRecension;


}
