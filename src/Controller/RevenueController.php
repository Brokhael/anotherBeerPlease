<?php

namespace App\Controller;

use App\Repository\RevenueRepository;
use App\Repository\DispenserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class RevenueController extends AbstractController
{
    /**
     * @var RevenueRepository $revenueRepository
     */
    private $revenueRepository;

    /**
     * @var DispenserRepository $dispenserRepository
     */
    private $dispenserRepository;

    public function __construct (RevenueRepository $revenueRepository, DispenserRepository $dispenserRepository) {
        $this->revenueRepository = $revenueRepository;
        $this->dispenserRepository = $dispenserRepository;
    }

    /**
     * @Route("/revenues", name="all_revenues", methods={"GET"})
     */
    public function allRevenues(): JsonResponse
    {
        $revenues = $this->revenueRepository->findAll();

        $revenuesArray = [];
        foreach ($revenues as $revenue) {
            $revenuesArray[] = [
                "dispenser_id" => $revenue->getDispenserId(),
                "service_time" => $revenue->getServiceTime(),
                "service_money" => $revenue->getServiceMoney(),
            ];
        }
        return $this->json(
            [
                'message' => 'Listing all revenues for all dispensers',
                'revenues' => $revenuesArray,
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/revenues/{dispenserId}", name="dispenser_revenues", methods={"GET"})
     */
    public function dispenserRevenues(int $dispenserId): JsonResponse
    {
        $dispenser = $this->dispenserRepository->findOneBy(['id' => $dispenserId]);

        if (!$dispenser) {
            return $this->json(
                [
                    "error" => "Dispenser not found",
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        $dispenserRevenues = $this->revenueRepository->findBy(['dispenser_id' => $dispenserId]);
        $reveneuesArray = [];
        foreach ($dispenserRevenues as $revenue) {
            $revenuesArray[] = [
                "service_time" => $revenue->getServiceTime(),
                "service_money" => $revenue->getServiceMoney(),
            ];
        }


        return $this->json(
            [
                'message' => "Revenues of the dispenser $dispenserId",
                'revenues' => $revenuesArray,
            ],
            JsonResponse::HTTP_OK
        );
    }

}
