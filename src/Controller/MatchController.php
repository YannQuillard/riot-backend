<?php

namespace App\Controller;

use App\Service\MatchService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class MatchController extends AbstractController
{
    /**
     * @var MatchService
     */
    private $matchService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(MatchService $matchService, SerializerInterface $serializer) {
        $this->matchService = $matchService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/match", name="match")
     */
    public function index(Request $request): Response
    {
        // Lane, Player

        $params = json_decode($request->getContent(), true);
        if(!isset($params['maxNumber']) || empty($params['maxNumber']) || !is_int($params['maxNumber'])) {
            throw new HttpException(400, 'Missing max number');
        }
        if(!isset($params['username']) || empty($params['username']) || !is_string($params['username']) ) {
            throw new HttpException(400, 'Missing username');
        }

        if(!isset($params['lane']) || empty($params['lane']) || !is_string($params['lane']) ) {
            throw new HttpException(400, 'Missing lane');
        }

        if(!isset($params['ban']) || !is_array($params['ban']) ) {
            throw new HttpException(400, 'Missing ban');
        }

        if(!isset($params['pick']['team']) || !is_array($params['pick']['team']) ) {
            throw new HttpException(400, 'Missing pick team');
        }

        if(!isset($params['pick']['enemy']) || !is_array($params['pick']['enemy']) ) {
            throw new HttpException(400, 'Missing pick enemy');
        }

        $newMatch = $this->matchService->newMatch($params);

        $result = $this->serializer->serialize(
            $newMatch,
            'json',
            [
                AbstractNormalizer::ATTRIBUTES =>
                    ['riotId', 'name', 'image', 'imageLoading', 'imageSplash', 'lane', 'type'
                    ]
            ]
        );
        return new JsonResponse($result, 200, [], true);
    }
}
