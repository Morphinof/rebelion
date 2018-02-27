<?php

namespace Rebelion\Controller;

use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Pile\Deck;
use Rebelion\Form\DeckBuilder\EditDeckNameType;
use Rebelion\Service\DeckBuilderService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class DeckController
 *
 * @package Rebelion\Controller
 */
class DeckController extends AbstractRebelionController
{
    /** @var DeckBuilderService $effectService */
    private $deckBuilderService;

    /**
     * EffectController constructor.
     *
     * @param TranslatorInterface $translator
     * @param DeckBuilderService  $deckBuilderService
     */
    public function __construct(TranslatorInterface $translator, DeckBuilderService $deckBuilderService)
    {
        parent::__construct($translator);

        $this->deckBuilderService = $deckBuilderService;
    }

    /**
     * @Route(
     *     "/deck/add",
     *     name="rebelion_deck_add"
     * )
     * @param Deck $deck
     *
     * @return JsonResponse
     */
    public function add(Deck $deck)
    {
        $json = [];

        return new JsonResponse($json);
    }

    /**
     * @Route(
     *     "/deck/edit/{id}",
     *     name="rebelion_deck_edit"
     * )
     * @param Deck $deck
     *
     * @return JsonResponse
     */
    public function edit(Deck $deck)
    {
        $json = [];

        return new JsonResponse($json);
    }

    /**
     * @Route(
     *     "/deck/add/card/{card}/{deck}",
     *     options = { "expose" = true },
     *     name="rebelion_deck_add_card"
     * )
     * @param Card $card
     * @param Deck $deck
     *
     * @return JsonResponse
     */
    public function addCard(Card $card, Deck $deck = null)
    {
        if ($deck === null) {
            $deck = $this->deckBuilderService->createDeck();
        }

        $message = null;
        if ($this->deckBuilderService->canAdd($card, $deck)) {
            $deck->addCard($card);

            $em = $this->getDoctrine()->getManager();
            $em->persist($deck);
            $em->flush();

            $message = sprintf($this->translator->trans('card_added_to_deck'), $card->getId(), $deck->getId());
        } else {
            $message = sprintf($this->translator->trans('global_duplicate_limit_reached'), $card->getName());
        }

        return new JsonResponse([
            'message' => $message,
            'deck'    => $deck->__toJson(),
        ], Response::HTTP_OK);
    }

    /**
     * @Route(
     *     "/deck/{deck}/edit/name",
     *     options = { "expose" = true },
     *     name="rebelion_deck_edit_name"
     * )
     * @param Deck    $deck
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function editDeckName(Deck $deck = null, Request $request)
    {
        $form = $this->createForm(EditDeckNameType::class, $deck, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($deck);
            $em->flush();

            return new JsonResponse([
                'message' => sprintf($this->translator->trans('deck_edit_name'), $deck->getId()),
                'deck'    => $deck->__toJson(),
            ], Response::HTTP_CREATED);
        }

        return $this->render('forms/deck-builder/deck-edit-name.html.twig', [
            'deck'             => $deck,
            'deckEditNameForm' => $form->createView()
        ]);
    }
}
