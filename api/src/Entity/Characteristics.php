<?php

namespace Rebelion\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Traits\CharacteristicsTrait;
use Rebelion\Traits\EntityTrait;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CharacteristicsRepository")
 * @ORM\HasLifecycleCallbacks()
 *
 * @package Rebelion\Entity
 */
class Characteristics
{
    use EntityTrait;
    use CharacteristicsTrait;

    /**
     * @param Race $race
     *
     * @throws \ReflectionException
     */
    public function setCharacteristicsFromRace(Race $race)
    {
        $rCharacteristics = new \ReflectionClass(Characteristics::class);

        foreach ($rCharacteristics->getProperties() as $characteristic) {
            $this->setCharacteristic($characteristic, $race->getCharacteristics()->getCharacteristic($characteristic));
        }
    }
}
