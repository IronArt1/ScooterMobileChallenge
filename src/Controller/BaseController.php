<?php

namespace App\Controller;

use App\Exception\InsufficientDataException;
use App\Controller\Abstracts\AbstractController;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class BaseController
 * @package App\Controller
 */
class BaseController extends AbstractController
{
    /**
     * @var EventDispatcherInterface
     */
    protected $dispatcher;

    /**
     * @var KernelInterface
     */
    private $kernel;

    /**
     * Current environment's
     *
     * @var string
     */
    protected $env;

    /**
     * BaseController constructor.
     *
     * @param EventDispatcherInterface $dispatcher
     * @param RequestStack $requestStack
     * @param KernelInterface $kernel
     */
    public function __construct(
        EventDispatcherInterface $dispatcher,
        RequestStack $requestStack,
        KernelInterface $kernel
    ) {
        $this->kernel = $kernel;
        $this->env = $kernel->getEnvironment();
        $this->dispatcher = $dispatcher;
        $this->request = $requestStack->getCurrentRequest();
    }
}
