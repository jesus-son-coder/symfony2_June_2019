<?php


namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Class AntiFloodValidator
 * c'est la classe Validateur.
 *
 */
class AntiFloodValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        // Pour l'instant, on considère comme "flood" tout message de moins de 3 caractères :
        if (strlen($value) < 3) {
            // C'est cette ligne qui déclenche l'erreur pour le formulaire, avec en argument le message :
            $this->context->addViolation($constraint->message);
            // ou :
            // $this->context->addViolation($constraint->message, ['%string%' => $value]);
        }
    }
}