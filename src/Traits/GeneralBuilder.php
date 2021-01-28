<?php

namespace App\Traits;

use Codeception\Util\HttpCode;

/**
 * Trait GeneralBuilder
 *
 * @package App\Traits
 */
trait GeneralBuilder
{
    /**
     * Make an appropriate response for a controller
     *
     * @throws \Exception
     */
    protected function bMakeResponse(): void
    {
        if (is_null($this->response)) {
            $this->statusCode = HttpCode::CREATED;
            $this->response = [];
        }
    }
}
