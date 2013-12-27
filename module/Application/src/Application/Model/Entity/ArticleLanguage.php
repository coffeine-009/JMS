<?php

namespace Application\Model\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ArticleLanguage
 *
 * @ORM\Table(name="article_language", indexes={@ORM\Index(name="id_article", columns={"id_article"})})
 * @ORM\Entity
 */
class ArticleLanguage
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
     * @ORM\Column(name="author", type="string", length=256, nullable=false)
     */
    private $author;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=256, nullable=false)
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="abstract", type="string", length=1024, nullable=false)
     */
    private $abstract;

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
