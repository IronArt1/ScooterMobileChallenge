<?php

namespace App\Controller\Abstracts;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as SymfonyAbstractController;

/**
 * Class AbstractController
 *
 * @package App\Controller\Abstracts
 */
abstract class AbstractController extends SymfonyAbstractController
{
    /**
     * @var null|\Symfony\Component\HttpFoundation\Request
     */
    protected $request = null;

    /**
     * A parameter `body` from a request's
     *
     * @var array | null
     */
    protected $body;
}
