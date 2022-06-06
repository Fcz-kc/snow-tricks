<?php

namespace App\Controller;

use App\Service\TrickService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class HomeController
 * @package App\Controller
 * @Route("/", name="_home")
 */
class HomeController extends AbstractRestController
{
    /**
     * HomeController constructor.
     *
     * @param TrickService $service
     */
    public function __construct(TrickService $service)
    {
        parent::__construct($service);
    }

    /**
     * @Route("/", methods={"OPTION","GET"}, name="_index")
     */
    public function index()
    {
        $items = $this->service->findBy([], ['createdAt' => 'DESC'], 8, 0);
        return $this->render('layout/home.html.twig', [
            'items' => $items
        ]);
    }
}
