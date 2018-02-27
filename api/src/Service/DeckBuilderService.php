<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 22:08
 */

namespace Rebelion\Service;

use Rebelion\Abstracts\ServiceAbstract;
use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Pile\Deck;

class DeckBuilderService extends ServiceAbstract
{
    const GLOBAL_DUPLICATES_LIMIT = 4;

    /**
     * Create a new deck with a generated name
     *
     * @return Deck
     */
    public function createDeck(): Deck
    {
        $deck = new Deck();
        $date = new \DateTime('now');
        $deck->setName(md5(sprintf('%s', $date->format('m-d-Y h:m:s'))));

        return $deck;
    }

    /**
     * Check if card can be added to deck
     *
     * @param Deck $deck
     * @param Card $card
     *
     * @return bool
     */
    public function canAdd(Card $card, Deck $deck)
    {
        return (
            $deck->getCards()->isEmpty() ||
            !isset($deck->getCardsByParent()[$card->getId()]) ||
            count($deck->getCardsByParent()[$card->getId()]) < self::GLOBAL_DUPLICATES_LIMIT
        );
    }
}