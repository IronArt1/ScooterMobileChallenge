<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ContainsAlpha extends Constraint
{
    /**
     * A violation constrains message's
     *
     * @var string
     */
    public string $message = 'A status "{{ status }}" of a scooter must be one of the following: all, available, occupied!';
}
