<?php

namespace App\Controller;

use App\Service\TrickService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class TrickController
 * @package App\Controller
 * @Route("/tricks", name="_tricks")
 */
class TrickController extends AbstractRestController
{
    /**
     * TrickController constructor.
     *
     * @param TrickService $service
     */
    public function __construct(TrickService $service)
    {
        parent::__construct($service);
    }
}
