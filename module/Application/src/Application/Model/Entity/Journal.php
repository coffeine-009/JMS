<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Journal
 *
 * @ORM\Table(name="journal", uniqueConstraints={@ORM\UniqueConstraint(name="isbn", columns={"isbn"})})
 * @ORM\Entity
 */
class Journal
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
     * @ORM\Column(name="isbn", type="string", length=100, nullable=false)
     */
    private $isbn;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="creation", type="datetime", nullable=false)
     */
    private $creation = 'CURRENT_TIMESTAMP';


    public function getId()
    {
        return $this -> id;
    }
    
    public function getIsbn()
    {
        return $this -> isbn;
    }
    
    public function getCreation()
    {
        return $this -> creation;
    }
}
