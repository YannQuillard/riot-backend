<?php

namespace App\Controller;

use App\Service\ChampionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChampionController extends AbstractController
{
    /**
     * @var ChampionService
     */
    private $championService;

    public function __construct(ChampionService $compositionService) {
        $this->championService = $compositionService;
    }

    /**
     * @Route("/champion", name="champion")
     */
    public function index(): Response
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
