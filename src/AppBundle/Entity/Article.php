<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Article
 *
 * @ORM\Table(name="article")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ArticleRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * L'annotation se définit sur la classe, et non sur une méthode ou un attribut !
 * @Assert\Callback(methods={"contenuValide"})
 *
 * @UniqueEntity(fields="titre", message="Un article existe déjà avec ce titre.")
 */
class Article
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime", nullable=true)
     * @Assert\DateTime()
     *
     */
    private $date;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, unique=true)
     * Assert\Length(limit=10, message="Le titre doit faire au moins {{limit}} caractères.")
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="auteur", type="string", length=255, nullable=true)
     * Assert\Length(2)
     */
    private $auteur;

    /**
     * @ORM\Column(name="publication", type="boolean")
     */
    private $publication;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", nullable=true)
     * @Assert\NotBlank()
     */
    private $contenu;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $dateEdition;

    /**
     * @Gedmo\Slug(fields={"titre"})
     * @ORM\Column(length=128, unique=true, nullable=true)
     */
    private $slug;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Image", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    private $image;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Commentaire", mappedBy="article")
     */
    private $commentaires;


    /**
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Categorie", cascade={"persist"})
     */
    private $categories;

    /**
     * Article constructor.
     */
    public function __construct()
    {
        $this->date = new \DateTime();
        $this->publication = true;
        $this->categories = new ArrayCollection();
        $this->commentaires = new ArrayCollection();
    }

    /**
     * @ORM\preUpdate
     * Callback pour mettre à jour la date d'édition à chaque modificaiton de l'entité
     */
    public function updateDate()
    {
        $this->setDateEdition(new \DateTime());
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Article
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set titre
     *
     * @param string $titre
     *
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set auteur
     *
     * @param string $auteur
     *
     * @return Article
     */
    public function setAuteur($auteur)
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Get auteur
     *
     * @return string
     */
    public function getAuteur()
    {
        return $this->auteur;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     *
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * @return mixed
     */
    public function getPublication()
    {
        return $this->publication;
    }

    /**
     * @param mixed $publication
     */
    public function setPublication($publication)
    {
        $this->publication = $publication;
    }

    

    /**
     * Add commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     *
     * @return Article
     */
    public function addCommentaire(\AppBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires[] = $commentaire;

        return $this;
    }

    /**
     * Remove commentaire
     *
     * @param \AppBundle\Entity\Commentaire $commentaire
     */
    public function removeCommentaire(\AppBundle\Entity\Commentaire $commentaire)
    {
        $this->commentaires->removeElement($commentaire);
    }

    /**
     * Get commentaires
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCommentaires()
    {
        return $this->commentaires;
    }

    /**
     * Add category
     *
     * @param \AppBundle\Entity\Categorie $category
     *
     * @return Article
     */
    public function addCategory(\AppBundle\Entity\Categorie $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param \AppBundle\Entity\Categorie $category
     */
    public function removeCategory(\AppBundle\Entity\Categorie $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * @return mixed
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * @param mixed $slug
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;
    }



    /**
     * Set dateEdition
     *
     * @param \DateTime $dateEdition
     *
     * @return Article
     */
    public function setDateEdition($dateEdition)
    {
        $this->dateEdition = $dateEdition;

        return $this;
    }

    /**
     * Get dateEdition
     *
     * @return \DateTime
     */
    public function getDateEdition()
    {
        return $this->dateEdition;
    }

    /**
     * Set image
     *
     * @param \AppBundle\Entity\Image $image
     *
     * @return Article
     */
    public function setImage(\AppBundle\Entity\Image $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \AppBundle\Entity\Image
     */
    public function getImage()
    {
        return $this->image;
    }


    public function contenuValide(ExecutionContextInterface $context)
    {
        $mots_interdits = array('echec', 'abandon');

        // On vérifie que le contenu ne contient pas l'un des mots :
        if (preg_match('#'. implode('|', $mots_interdits), '#', $this->getContenu() )) {

            /* La règle est violée, on définit l'erreur et son message.
                1er argument: on dit quel attribut l'erreur concerne, ici : "contenu" ;
                2ème argument: le message d'erreur. */
            $context->addViolationAt('contenu', 'Contenu invalide car il contient un mot interdit', array(), null);
        }
    }


}
