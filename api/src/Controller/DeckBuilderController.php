<?php

namespace Rebelion\Controller;

use Rebelion\Entity\Pile\Deck;
use Rebelion\Form\DeckBuilder\SelectDeckType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DeckBuilderController
 *
 * @package Rebelion\Controller
 */
class DeckBuilderController extends AbstractRebelionController
{
    /**
     * @Route(
     *      "/deck-builder/{deck}",
     *      options = { "expose" = true },
     *      name="rebelion_deck_builder"
     * )
     * @param Deck|null $deck
     *
     * @return Response
     */
    public function deckBuilder(Deck $deck = null)
    {
        $cards          = $this->getDoctrine()->getRepository('Rebelion:Container\Card')->findAll();
        $decks          = $this->getDoctrine()->getRepository('Rebelion:Pile\Deck')->findAll();
        $selectDeckForm = $this->createForm(SelectDeckType::class, null, ['deck' => $deck]);

        $parameters = [
            'cards'          => $cards,
            'decks'          => $decks,
            'deck'           => $deck,
            'selectDeckForm' => $selectDeckForm->createView(),
        ];

        return $this->render('deck-builder/deck-builder.html.twig', $parameters);
    }
}
