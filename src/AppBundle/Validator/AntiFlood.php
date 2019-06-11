<?php


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * Class AntiFlood
 * c'est la classe Contrainte.
 * La Contrainte décide par quel validateur elle doit se faire valider.
 * Par défaut,  une contrainte "XXX" demande à se faire valider par le validateur "XXXValidator", donc ici ce sera le Validateur "AntiFloodValidator".
 *
 * @Annotation
 * L'annotation ci-dessus est nécessaire pour que cette nouvelle contrainte soit disponilbe via les annotations dans les autres classes. En effet, toutes les classes ne sont pas des annotations : heureusement.
 */
class AntiFlood extends Constraint
{
    public $message = "Vous avez déjà posté un message il y a moins de 15 secondes, merci d'attendre un peu.";

    public function validatedBy()
    {
        return 'blog_antiflood';
    }
}