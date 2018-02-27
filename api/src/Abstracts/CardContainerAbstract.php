<?php

namespace Rebelion\Abstracts;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\DiscriminatorColumn;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\InheritanceType;
use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Traits\EntityTrait;

/**
 * @ORM\Entity(repositoryClass="Rebelion\Repository\CardContainerRepository")
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="discr", type="string")
 * @DiscriminatorMap
 * (
 *      {
 *          "deck" = "Rebelion\Entity\Pile\Deck",
 *          "draw" = "Rebelion\Entity\Pile\Draw",
 *          "hand" = "Rebelion\Entity\Pile\Hand",
 *          "discard" = "Rebelion\Entity\Pile\Discard",
 *          "exile" = "Rebelion\Entity\Pile\Exile"
 *      }
 * )
 */
abstract class CardContainerAbstract
{
    /**
     * @var ArrayCollection
     *
     * Many Piles have Many ProxyCards.
     *
     * @ORM\ManyToMany(targetEntity="Rebelion\Entity\Container\ProxyCard", cascade={"persist", "remove"})
     * @ORM\JoinTable
     * (
     *      joinColumns={@ORM\JoinColumn(name="container_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="proxy_card_id", referencedColumnName="id")}
     * )
     * @ORM\OrderBy({"position" = "ASC"})
     */
    protected $cards;

    /**
     * Pile constructor.
     */
    public function __construct()
    {
        $this->cards = new ArrayCollection();
    }

    /**
     * @return ArrayCollection
     */
    public function getCards()
    {
        return $this->cards;
    }

    /**
     * Add a card to the list
     *
     * @param Card|ProxyCard $card
     * @param null           $position
     */
    public function addCard($card, $position = null): void
    {
        if (!is_null($card)) {
            if ($card instanceof ProxyCard) {
                $card = $card->getParent();
            }

            if ($position === null) {
                $position = $this->cards->count();
            }

            $proxy = new ProxyCard($card);
            $proxy->setPosition($position);
            $this->cards->add($proxy);
        }
    }

    /**
     * Push a card in card list (does not create a new proxy)
     *
     * @param ProxyCard $proxy
     * @param null      $position
     */
    public function pushCard(ProxyCard $proxy, $position = null)
    {
        if ($position === null) {
            $position = $this->cards->count();
        }

        $proxy->setPosition($position);
        $this->cards->add($proxy);
    }

    /**
     * @param ProxyCard $proxy
     */
    public function removeCard(ProxyCard $proxy): void
    {
        /** @var ProxyCard $currentProxy */
        foreach ($this->cards as $currentProxy) {
            if ($proxy->getId() === $currentProxy->getId()) {
                $this->cards->removeElement($proxy);
            }
        }
    }

    /**
     * @return array
     */
    public function getCardsByParent(): array
    {
        $cards = [];

        if (!$this->cards->isEmpty()) {
            /** @var ProxyCard $card */
            foreach ($this->cards as $card) {
                $cards[sprintf("%s", $card->getParentId())][] = $card;
            }
        }

        return $cards;
    }

    /**
     * Draw top card from the card list
     *
     * @return ProxyCard
     */
    public function draw(): ?ProxyCard
    {
        if ($this->cards->isEmpty()) {
            return null;
        }

        $card = $this->cards->first();
        $this->cards->removeElement($card);

        return $card;
    }

    /**
     * Draw a random card from the card list
     *
     * @return ProxyCard
     */
    public function drawRandom(): ?ProxyCard
    {
        if ($this->cards->isEmpty()) {
            return null;
        }

        $index = mt_rand(0, $this->cards->count());

        $card = $this->cards->get($index);
        $this->cards->remove($index);

        return $card;
    }

    /**
     * Get a random card from the card list
     *
     * @return ProxyCard
     */
    public function getRandom(): ?ProxyCard
    {
        if ($this->cards->isEmpty()) {
            return null;
        }

        $index = mt_rand(0, $this->cards->count());

        $card = $this->cards->get($index);

        return $card;
    }

    /**
     * @return array
     */
    public function __toJson(): array
    {
        return [
            'cards' => $this->cards->toArray()
        ];
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return sprintf('[%s]', implode(', ', $this->cards->toArray()));
    }
}
