<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleAuthors
 *
 * @ORM\Table(name="article_authors", indexes={@ORM\Index(name="id_user", columns={"id_user"}), @ORM\Index(name="id_article", columns={"id_article"})})
 * @ORM\Entity
 */
class ArticleAuthors
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
     * @var \Application\Model\Entity\User
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user", referencedColumnName="id")
     * })
     */
    private $idUser;

    /**
     * @var \Application\Model\Entity\Article
     *
     * @ORM\ManyToOne(targetEntity="Application\Model\Entity\Article")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_article", referencedColumnName="id")
     * })
     */
    private $idArticle;


}
