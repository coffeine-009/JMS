<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JournalLanguage
 *
 * @ORM\Table(name="journal_language", indexes={@ORM\Index(name="id_journal", columns={"id_journal"})})
 * @ORM\Entity
 */
class JournalLanguage
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
     * @ORM\Column(name="title", type="string", length=100, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=false)
     */
    private $creation = 'CURRENT_TIMESTAMP';

    /**
     * @var \Application\Model\Entity\Journal
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Journal")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_journal", referencedColumnName="id")
     * })
     */
    private $idJournal;


}
