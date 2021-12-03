<?php

namespace App\Controller;

use App\Service\MatchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MatchController extends AbstractController
{
    /**
     * @var MatchService
     */
    private $matchService;

    public function __construct(MatchService $matchService) {
        $this->matchService = $matchService;
    }

    /**
     * @Route("/match", name="match")
     */
    public function index(Request $request): Response
    {
        // Lane, Player
        $params = json_decode($request->getContent(), true);
        $newMatch = $this->matchService->newMatch($params);
        return $this->json($params);
    }
}
