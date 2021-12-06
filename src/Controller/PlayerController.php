<?php

namespace App\Controller;

use App\Service\PlayerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class PlayerController extends AbstractController
{
    /**
     * @var PlayerService
     */
    private $playerService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(PlayerService $playerService, SerializerInterface $serializer) {
        $this->playerService = $playerService;
        $this->serializer = $serializer;
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
        $this->playerService->addFavorite($params);
        return  $this->json('new Player added');
    }
       
}
