<?php

namespace Rebelion\Controller;

use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Form\DeckBuilder\CardEffectsType;
use Rebelion\Form\DeckBuilder\CardType;
use Rebelion\Service\EffectService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CardController
 *
 * @package Rebelion\Controller
 */
class CardController extends AbstractRebelionController
{
    /** @var EffectService $effectService */
    private $effectService;

    public function __construct(TranslatorInterface $translator, EffectService $effectService)
    {
        parent::__construct($translator);

        $this->effectService = $effectService;
    }

    /**
     * @Route(
     *      "/card/add",
     *      options = { "expose" = true },
     *      name="rebelion_card_add"
     * )
     *
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(CardType::class, null, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Card $card */
            $card = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($card);
            $em->flush();

            return new JsonResponse([
                'message' => sprintf($this->translator->trans('card_created'), $card->getId(), $card->getName()),
                'card'    => $card
            ], Response::HTTP_CREATED);
        }

        return $this->render('forms/deck-builder/card.html.twig', ['cardForm' => $form->createView()]);
    }

    /**
     * @Route(
     *      "/card/{card}/edit",
     *      options = { "expose" = true },
     *      name="rebelion_card_edit"
     * )
     *
     * @param Card    $card
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function edit(Card $card, Request $request)
    {
        $form = $this->createForm(CardType::class, $card, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Card $card */
            $card = $form->getData();
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($card);
            $em->flush();

            return new JsonResponse([
                'message' => sprintf($this->translator->trans('card_updated'), $card->getId(), $card->getName()),
                'card'    => $card
            ], Response::HTTP_CREATED);
        }

        return $this->render('forms/deck-builder/card.html.twig', ['card' => $card, 'cardForm' => $form->createView()]);
    }

    /**
     * @Route(
     *      "/card/{card}/edit-proxy-effects",
     *      options = { "expose" = true },
     *      name="rebelion_card_edit_proxy_effects"
     * )
     *
     * @param Card    $card
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function editProxyEffects(Card $card, Request $request)

    {
        $form = $this->createForm(CardEffectsType::class, null, ['card' => $card]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->effectService->saveCardEffects($card, $form);

            return new JsonResponse([
                'message' => sprintf($this->translator->trans('card_effects_updated'), $card->getId(), $card->getName()),
                'card'    => $card
            ], Response::HTTP_CREATED);
        }

        return $this->render('forms/deck-builder/card-effects.html.twig', ['card' => $card, 'cardEffectsForm' => $form->createView()]);
    }
}
