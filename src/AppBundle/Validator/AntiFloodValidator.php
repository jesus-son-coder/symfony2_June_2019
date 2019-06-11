<?php


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AntiFloodValidator
 * c'est la classe Validateur.
 *
 */
class AntiFloodValidator extends ConstraintValidator
{
    private $request;
    private $em;

    /*
     * Les arguments déclarés dans la définition du service arrivent au constructeur. On doit les enregitrer dans l'objet pour pouvoir s'en resservir dans la méthode "validate()" :
     */
    public function __construct(Request $request, EntityManager $em)
    {
        $this->request = $request;
        $this->em = $em;

    }

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        // On récupère l'ID de celui qui poste :
        $ip = $this->request->server->get('REMOTE_ADDR');

        // On vérifie si cette IP a déjà posté un message il y a moins de 15 secondes..
        // Bien entedu, il faudrait écrire cette méthode isFlood => c'est pour l'exemple !!!
        $isFlood = $this->em->getRepository('AppBundle:Commentaire')->isFlood($ip, 15);

        // On considère comme "flood" tout message de moins de 3 caractères :
        if (strlen($value) < 3 && $isFlood) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message :
            $this->context->addViolation($constraint->message);

        }
        /* Et voilà, nous venons de faire une contrainte qui s'utilise aussi facilement qu'une annotation, et qui pourtant fait un gros travail en allant chercher dans la base de données si l'IP courante envoie trop de messages.
        Un peu de travail à la création de la contrainte, mais son utilisation est un jeu d'enfant à présent ! */
    }
}