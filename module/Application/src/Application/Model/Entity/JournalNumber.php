<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * JournalNumber
 *
 * @ORM\Table(name="journal_number", indexes={@ORM\Index(name="id_journal", columns={"id_journal"})})
 * @ORM\Entity
 */
class JournalNumber
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
     * @ORM\Column(name="volume", type="integer", nullable=false)
     */
    private $volume;

    /**
     * @var integer
     *
     * @ORM\Column(name="issue", type="integer", nullable=false)
     */
    private $issue;

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
