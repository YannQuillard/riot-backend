<?php

namespace App\Controller;

use App\Service\CompositionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{

    /**
     * @var CompositionService
     */
    private $compositionService;

    public function __construct(CompositionService $compositionService) {
        $this->compositionService = $compositionService;
    }

    /**
     * @Route("/game", name="game")
     */
    public function game(): Response
    {

        $riotGame = $this->compositionService->getMatch($matchId);
        return $this->json($riotGame);
    }
}
