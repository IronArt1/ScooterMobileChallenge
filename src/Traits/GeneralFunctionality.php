<?php

namespace App\Traits;

/**
 * Trait GeneralFunctionality
 * @package App\Traits
 */
trait GeneralFunctionality
{
    /**
     * @param null $data
     * @return string
     */
    public function getEntityNamespaceName($data = null)
    {
        return 'App\\Entity\\' . (isset($data['types']) ? $data['types'] : static::TYPES_HOLDER[0]);
    }
}