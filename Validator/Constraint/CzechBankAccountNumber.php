<?php

namespace App\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CzechBankAccountNumber extends Constraint
{
    public string $message = 'bankAccountNumber.format';

    /**
     * {@inheritDoc}
     */
    public function validatedBy()
    {
        return CzechBankAccountNumberValidator::class;
    }
}
