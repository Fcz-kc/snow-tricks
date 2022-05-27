<?php

namespace App\Controller;

use App\Service\CommentService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController
 * @package App\Controller
 * @Route("/comments", name="_comments")
 */
class CommentController extends AbstractRestController
{
    /**
     * CommentController constructor.
     *
     * @param CommentService $service
     */
    public function __construct(CommentService $service)
    {
        parent::__construct($service);
    }
}
