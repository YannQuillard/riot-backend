<?php

namespace App\Controller;

use App\Service\CompositionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CompositionsController extends AbstractController
{
    /**
     * @var CompositionService
     */
    private $compositionService;

    public function __construct(CompositionService $compositionService) {
        $this->compositionService = $compositionService;
    }

    /**
     * @Route("/compo", name="compo")
     */
    public function compo(): Response
    {
        $riotChallenger = $this->compositionService->getChallengers();
        $challengerMatchs = $this->compositionService->getMatchsInfo($riotChallenger);

        return $this->json($challengerMatchs);
    }
}
