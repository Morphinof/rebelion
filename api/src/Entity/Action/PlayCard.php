<?php

namespace Rebelion\Entity\Action;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Rebelion\Entity\Container\ProxyCard;

/**
 * @ApiResource
 * @ORM\Entity(repositoryClass="Rebelion\Repository\PlayCardRepository")
 */
class PlayCard extends Action
{
    const CLASS_NAME = 'PlayCard';

    /**
     * @var ProxyCard
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Container\ProxyCard")
     * @ORM\JoinColumn(name="proxy_card_id", referencedColumnName="id")
     */
    protected $card;

    /**
     * @return ProxyCard
     */
    public function getCard(): ProxyCard
    {
        return $this->card;
    }

    /**
     * @param ProxyCard $card
     */
    public function setCard(ProxyCard $card): void
    {
        $this->card = $card;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf(
            '%s - %s',
            parent::__toString(),
            $this->card->getName()
        );
    }
}