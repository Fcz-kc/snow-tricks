<?php

namespace App\Controller;

use App\Service\CommentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UsersController
 * @package App\Controller
 * @Route("/users", name="_users")
 */
class UsersController extends AbstractController
{
    /**
     * UsersController constructor.
     *
     * @param CommentService $service
     */
    public function __construct(CommentService $service)
    {
        parent::__construct($service);
    }

    /**
     * @Route("/register", methods={"OPTION","POST"}, name="_register")
     */
    public function register()
    {
        return $this->render('layout/registration.html.twig');
    }
}
