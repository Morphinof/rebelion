<?php

namespace Rebelion\Controller;

use Rebelion\Entity\Effect\Effect;
use Rebelion\Form\DeckBuilder\EffectType;
use Rebelion\Service\EffectService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class EffectController
 *
 * @package Rebelion\Controller
 */
class EffectController extends AbstractRebelionController
{
    /** @var EffectService $effectService */
    private $effectService;

    /**
     * EffectController constructor.
     *
     * @param TranslatorInterface $translator
     * @param EffectService       $effectService
     */
    public function __construct(TranslatorInterface $translator, EffectService $effectService)
    {
        parent::__construct($translator);

        $this->effectService = $effectService;
    }

    /**
     * @Route(
     *     "/effect/types/list",
     *     name="rebelion_effect_types_list",
     *     options = { "expose" = true }
     * )
     */
    public function typesList()
    {
        $types = $this->getDoctrine()->getRepository('Rebelion:EffectType')->findAll();

        return new JsonResponse(['types' => $types]);
    }

    /**
     * @Route(
     *     "/effect/add",
     *     name="rebelion_effect_add",
     *     options = { "expose" = true }
     * )
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function add(Request $request)
    {
        $form = $this->createForm(EffectType::class, null, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Effect $effect */
            $effect = $form->getData();

            #$resolve = $this->effectService->resolve($effect);
            #if (!array_key_exists('error', $resolve)) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($effect);
            $em->flush();

            return new JsonResponse([
                'message' => sprintf($this->translator->trans('effect_created'), $effect->getId(), $effect->getName()),
                'effect'  => json_encode(serialize($effect)),
            ], Response::HTTP_CREATED);
            #} else {
            #    $form->get('script')->addError(new FormError($resolve['error']));
            #}
        }

        return $this->render('forms/deck-builder/effect.html.twig', ['effectForm' => $form->createView()]);
    }

    /**
     * @Route(
     *      "/effect/edit/{effect}",
     *      options = { "expose" = true },
     *      name="rebelion_effect_edit"
     * )
     *
     * @param Effect  $effect
     * @param Request $request
     *
     * @return JsonResponse|Response
     */
    public function edit(Effect $effect, Request $request)
    {
        /** @var Effect $effect */
        $form = $this->createForm(EffectType::class, $effect, []);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Effect $card */
            $card = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($card);
            $em->flush();

            return new JsonResponse([
                'message' => sprintf($this->translator->trans('effect_updated'), $card->getId(), $card->getName()),
                'effect'  => $effect
            ], Response::HTTP_CREATED);
        }

        return $this->render('forms/deck-builder/effect.html.twig', ['effectForm' => $form->createView()]);
    }
}
