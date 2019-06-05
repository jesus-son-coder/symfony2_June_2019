<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class ArticleCompetence
 * @ORM\Entity()
 */
class ArticleCompetence
{
    /**
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Article")
     */
    private $article;

    /**
     * @ORM\Id
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Competence")
     */
    private $competence;

    /**
     * @ORM\Column()
     */
    private $niveau;

    /**
     * Set niveau
     *
     * @param string $niveau
     *
     * @return ArticleCompetence
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;

        return $this;
    }

    /**
     * Get niveau
     *
     * @return string
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * Set article
     *
     * @param \AppBundle\Entity\Article $article
     *
     * @return ArticleCompetence
     */
    public function setArticle(\AppBundle\Entity\Article $article)
    {
        $this->article = $article;

        return $this;
    }

    /**
     * Get article
     *
     * @return \AppBundle\Entity\Article
     */
    public function getArticle()
    {
        return $this->article;
    }

    /**
     * Set competence
     *
     * @param \AppBundle\Entity\Competence $competence
     *
     * @return ArticleCompetence
     */
    public function setCompetence(\AppBundle\Entity\Competence $competence)
    {
        $this->competence = $competence;

        return $this;
    }

    /**
     * Get competence
     *
     * @return \AppBundle\Entity\Competence
     */
    public function getCompetence()
    {
        return $this->competence;
    }
}
