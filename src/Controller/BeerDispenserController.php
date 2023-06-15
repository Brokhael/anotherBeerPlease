<?php

namespace App\Controller;

use App\Entity\Dispenser;
use App\Model\DispenserHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\DispenserRepository;
use App\Repository\RevenueRepository;

class BeerDispenserController extends AbstractController
{
    /**
     * @var DispenserRepository $dispenserRepository
     */
    private DispenserRepository $dispenserRepository;
    /**
     * @var RevenueRepository $revenueRepository
     */
    private RevenueRepository $revenueRepository;
    /**
     * @var DispenserHelper $dispenserHelper
     */
    private DispenserHelper $dispenserHelper;

    public function __construct(DispenserRepository $dispenserRepository, RevenueRepository $revenueRepository, DispenserHelper $dispenserHelper)
    {
        $this->dispenserRepository = $dispenserRepository;
        $this->revenueRepository = $revenueRepository;
        $this->dispenserHelper = $dispenserHelper;
    }

    /**
     * @Route("/beerDispensers", name="create_beer_dispenser", methods={"POST"})
     */
    public function createBeerDispenser(Request $request): JsonResponse
    {
        $flowVolumne = $request->get('flow_volume');
        $price = $request->get('price');

        if (null === $flowVolumne || 0.0 === (float) $flowVolumne || 0 > (float) $flowVolumne) {
           return $this->json(
                [
                    "error" => "The flow volume value is invalid, it must be a number heigher than 0",
                ],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        $dispenser = $this->dispenserRepository->createBeerDispenser($flowVolumne, $price);
        $message = "Dispenser created";
        if (null === $price) {
            $message .= " (Price was set to ".Dispenser::PRICE_DEFAULT." by default and its disabled until the price is updated)";
        }

        return $this->json(
            [
                "message"   => $message, 
                "dispenser" => $this->dispenserHelper->getDispenserInfo($dispenser),
            ],
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @Route("/beerDispensers", name="list_beer_dispensers", methods={"GET"})
     */
    public function listBeerDispensers(): JsonResponse
    {
        $dispensers = $this->dispenserRepository->findAll();
        $dispensersArray = [];

        if (null === $dispensers) {
            return $this->json(
                [
                    "error" => "Dispensers not found",
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        foreach ($dispensers as $dispenser) {
            $dispensersArray[] = $this->dispenserHelper->getDispenserInfo($dispenser);
        }

        return $this->json(
            $dispensersArray,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/beerDispensers/{beerDispenserId}", name="get_beer_dispenser", methods={"GET"})
     */
    public function getBeerDispenser(int $beerDispenserId): JsonResponse
    {
        $dispenser = $this->dispenserRepository->findOneBy(["id" => $beerDispenserId]);

        if (!$dispenser) {
            return $this->json(
                [
                    "error" => "Dispenser not found",
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        return $this->json(
            [
                "dispenser" => $this->dispenserHelper->getDispenserInfo($dispenser),
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @Route("/beerDispensers/{beerDispenserId}", name="update_dispenser", methods={"PUT"})
     */
    public function updateBeerDispenser(int $beerDispenserId, Request $request): JsonResponse
    {
        $flowVolume = $request->get('flow_volume');
        $price = $request->get('price');

        $dispenser = $this->dispenserRepository->findOneBy(["id" => $beerDispenserId]);

        if (!$dispenser) {
            return $this->json(
                [
                    'error' => 'Dispenser not found',
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }
        if (0.0 === (float) $flowVolume && 0.0 === (float) $price) {
            return $this->json(
                [
                    'error' => 'No updates were made',
                ],
                JsonResponse::HTTP_NOT_MODIFIED
            );
        }

        $updatedDispenser = $this->dispenserRepository->updateBeerDispenser($dispenser, $flowVolume, $price);

        return $this->json(
            [
                "message"   => "Dispenser updated", 
                "dispenser" => $this->dispenserHelper->getDispenserInfo($updatedDispenser),
            ],
            JsonResponse::HTTP_ACCEPTED
        );


    }

    /**
     * @Route("/beerDispensers/{beerDispenserId}/tap", name="open_tap", methods={"PUT"})
     */
    public function openBeerTap(int $beerDispenserId): JsonResponse
    {
        $dispenser = $this->dispenserRepository->findOneBy(["id" => $beerDispenserId]);

        if (!$dispenser) {
            return $this->json(
                [
                    'error' => 'Dispenser not found',
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        if ($dispenser->getStatus() === Dispenser::STATUS_OPEN) {
            return $this->json(
                [
                    'error' => 'Dispenser is already open',
                ],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $this->dispenserRepository->openBeerDispenser($dispenser);

        return $this->json(
            [
                "message" => "Dispenser opened sucessfully",
                "dispenser" => $this->dispenserHelper->getDispenserInfo($dispenser),
            ],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * In this case in my opinion delete would be the appropiate method to close tap as is an "end" of someething,
     *
     * @Route("/beerDispensers/{beerDispenserId}/tap", name="close_tap", methods={"DELETE"})
     */
    public function closeBeerTap(int $beerDispenserId): JsonResponse
    {
        $dispenser = $this->dispenserRepository->findOneBy(["id" => $beerDispenserId]);

        if (!$dispenser) {
            return $this->json(
                [
                    'error' => 'Dispenser not found',
                ],
                JsonResponse::HTTP_NOT_FOUND
            );
        }

        if ($dispenser->getStatus() === Dispenser::STATUS_CLOSED) {
            return $this->json(
                [
                    'error' => 'Dispenser is already closed',
                ],
                JsonResponse::HTTP_CONFLICT
            );
        }

        $openedTime = $this->dispenserHelper->calculateDispenserOpenedTime($dispenser);
        $totalMoney = $this->dispenserHelper->calculateTotalMoney($dispenser, $openedTime);

        $updatedDispenser = $this->dispenserRepository->closeBeerDispenser($dispenser, $totalMoney, $openedTime);
        $this->revenueRepository->createDispenserRevenue($updatedDispenser->getId(), $totalMoney, $openedTime);

        return $this->json(
            [
                "message"   => "Dispenser closed sucessfully",
                "dispenser" => $this->dispenserHelper->getDispenserInfo($updatedDispenser),
            ],
            JsonResponse::HTTP_OK
        );
    }
}
