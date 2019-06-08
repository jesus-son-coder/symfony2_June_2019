<?php


namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class Image
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ImageRepository")
 */
class Image
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
     * @var string url
     *
     * @ORM\Column(name="url", type="string", length=255)
     */
    private $url;

    /*
     * @var UploadedFile $file
     *
     * Notez bien que nous n'avons pas d'annotation @ORM\Doctrine pour cet attribut $file.
     * Ce n'est pas cet attribut $file que nous allons persister.
     * Mais c'est bien cet attribut qui servira pour le formulaire...
     */
    private $file;

    /**
     * @var string $alt
     *
     * @ORM\Column(name="alt", type="string", length=255)
     */
    private $alt;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Image
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set alt
     *
     * @param string $alt
     *
     * @return Image
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;

        return $this;
    }

    /**
     * Get alt
     *
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    public function upload()
    {
        // Si jamais il n'y a pas de fichier (champ facultatif) :
        if(null === $this->file) {
            return;
        }

        // On garde le nom original du fichier de l'internaute :
        $name = $this->file->getClientOriginalName();

        // On déplace le fichier envoyé dans le répertoire de notre choix :
        $this->file->move($this->getUploadRootDir(), $name);

        // On sauvegarde le nom de fichier dans notre attribut $url :
        $this->url = $name;

        // On crée également le futur attribut alt de notre balise <img> :
        $this->alt = $name;
    }

    public function getUploadDir()
    {
        // On retourne le chemin relatif vers l'image pour un navigateur;
        // Ce répertoire 'upload/img' doit être localisé dans le répertoire "/web" du projet :
        return 'uploads/img';
    }

    protected function getUploadRootDir()
    {
        /* Ici, on retourne le chemin absolu qui mène vers le fichier uplaodé.
            Vous le savez, "__DIR__" représente le répertoire absolu du fichier courant (ici c'est notre Entité).
            Du coup, pour atteindre le répertoire "/web", il faut remonter pas mal de dossiers,
            ...comme vous pouvez le voir. */
        return __DIR__ . '/../../../web/' . $this->getUploadDir();
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }


}
