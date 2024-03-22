<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class EveryExpressionValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        /* @var EveryExpression $constraint */

        if (false !== strtotime($value)) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
