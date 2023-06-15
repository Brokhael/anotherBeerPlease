<?php

namespace App\Model;

use App\Entity\Dispenser;
use Doctrine\Persistence\ManagerRegistry;

class DispenserHelper
{
        /**
     * @return mixed[]
     * 
     * This function returns array with the information about the dispenser, just to don't rewrite all this lines in all responses.
     */
    public function getDispenserInfo(Dispenser $dispenser): array
    {
        return [
            "id" => $dispenser->getId(),
            "flow_volume" => $dispenser->getFlowVolume(),
            "status" => $dispenser->getStatus(),
            "total_time_opened" => $dispenser->getTotalTimeOpen(),
            "total_money" => $dispenser->getTotalMoney(),
            "usage_count" => $dispenser->getUsageCount(),
            "last_open_time" => $dispenser->getLastOpenTime(),
            "price" => $dispenser->getPrice(),
            "active" => $dispenser->isActive(),
        ];
    }

    /**
     * @var Dispenser $dispenser
     * 
     * This return the time that a dispenser was opened.
     */
    public function calculateDispenserOpenedTime(Dispenser $dispenser)
    {
        $openTime = $dispenser->getLastOpenTime();
        $currentTime = new \DateTime();
        $timeDiff = $currentTime->getTimestamp() - $openTime->getTimestamp();
        $totalTimeOpen = $dispenser->getTotalTimeOpen() + $timeDiff;

        return $totalTimeOpen;
    }

    /**
     * @var Dispenser $dispenser
     * @var int       $timeDiff
     * 
     * This function returns the money earned by a dispenser.
     */
    public function calculateTotalMoney(Dispenser $dispenser, int $timeDiff) {
        $flowVolume = $dispenser->getFlowVolume();
        $price = $dispenser->getPrice();
        $beerVolume = $flowVolume * $timeDiff;
        $totalMoney = $dispenser->getTotalMoney() + ($beerVolume * $price);

        return $totalMoney;
    }

}