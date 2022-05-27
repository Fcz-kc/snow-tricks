<?php

namespace App\Controller;

use App\Service\GroupService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class GroupController
 * @package App\Controller
 * @Route("/groups", name="_groups")
 */
class GroupController extends AbstractRestController
{
    /**
     * GroupController constructor.
     *
     * @param GroupService $service
     */
    public function __construct(GroupService $service)
    {
        parent::__construct($service);
    }
}
