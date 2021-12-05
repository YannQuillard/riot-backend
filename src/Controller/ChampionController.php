<?php

namespace App\Controller;

use App\Entity\Champion;
use App\Service\ChampionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class ChampionController extends AbstractController
{
    /**
     * @var ChampionService
     */
    private $championService;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ChampionService $compositionService,
        SerializerInterface $serializer
    ) {
        $this->championService = $compositionService;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/champions", name="champions")
     */
    public function index(): Response
    {
        $entityManger = $this->getDoctrine()->getManager();
        $champion = $entityManger->getRepository(Champion::class)->findAll();

        $result = $this->serializer->serialize(
            $champion,
            'json',
            [AbstractNormalizer::ATTRIBUTES =>
                ['riotId', 'name', 'image', 'imageLoading', 'imageSplash', 'lane', 'type'
                ]
            ]
        );
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route("/champion/by-name/{name}", name="byName")
     */
    public function byName(string $name): Response
    {
        $entityManger = $this->getDoctrine()->getManager();
        $champion = $entityManger->getRepository(Champion::class)->findOneByName($name);

        $result = $this->serializer->serialize(
            $champion,
            'json',
            [AbstractNormalizer::ATTRIBUTES =>
                ['riotId', 'name', 'image', 'imageLoading', 'imageSplash', 'lane', 'type'
                ]
            ]
        );
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route("/champion/riot-id/{id}", name="riotId")
     */
    public function byRiotId(int $id): Response
    {
        $entityManger = $this->getDoctrine()->getManager();
        $champion = $entityManger->getRepository(Champion::class)->findOneById($id);

        $result = $this->serializer->serialize(
            $champion,
            'json',
            [AbstractNormalizer::ATTRIBUTES =>
                ['riotId', 'name', 'image', 'imageLoading', 'imageSplash', 'lane', 'type'
                ]
            ]
        );
        return new JsonResponse($result, 200, [], true);
    }

    /**
     * @Route("/champion/lanes/", name="lanes")
     */
    public function lanes(): Response
    {

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ChampionController.php',
        ]);
    }

    /**
     * @Route("/champion/lane/{id}", name="laneId")
     */
    public function laneById(int $id): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ChampionController.php',
        ]);
    }

    /**
     * @Route("/champion/lane/{name}", name="laneName")
     */
    public function laneByName(string $name): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ChampionController.php',
        ]);
    }

    /**
     * @Route("/set", name="setchampion")
     */
    public function setChampion(): Response
    {
        $champions = [
            [
                "id" => 266,
                "name" => "Aatrox"
            ],
            [
                "id" => 254,
                "name" => "Vi"
            ],
            [
                "id" => 91,
                "name" => "Talon"
            ],
            [
                "id" => 96,
                "name" => "KogMaw"
            ],
            [
                "id" => 117,
                "name" => "Lulu"
            ]
        ];
        $getChampionsInfos = $this->championService->storeChampions($champions);
        return $this->json($getChampionsInfos);
    }

}
