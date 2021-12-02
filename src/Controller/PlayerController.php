<?php

namespace App\Controller;

use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlayerController extends AbstractController
{
    /**
     * @var PlayerService
     */
    private $playerService;

    public function __construct(PlayerService $playerService) {
        $this->playerService = $playerService;
    }
    /**
     * @Route("/player", name="player")
     */
    public function player(): Response
    {
        $riotPlayer = $this->playerService->getPlayer();
        return $this->json($riotPlayer);
    }

    /**
     * @Route("/newfavorite", name="newfavorite")
     */
    public function newFavorite(Request $request): Response
    {
        $params = json_decode($request->getContent(), true);

        if(!isset($params['champions'])) {
            throw new HttpException(400, 'Missing champion');
        }
        if(!isset($params['username'])) {
            throw new HttpException(400, 'Missing username');
        }
        $riotPlayer = $this->playerService->addFavorite($params);
        return $this->json($riotPlayer);
    }
    
}
