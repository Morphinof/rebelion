<?php

namespace Rebelion\Controller;

use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Exceptions\CombatException;
use Rebelion\Entity\Combat;
use Rebelion\Service\CombatService;
use Rebelion\Service\TurnService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class CombatController
 *
 * @package Rebelion\Controller
 */
class CombatController extends AbstractRebelionController
{
    private $combatService;
    private $turnService;

    /**
     * CombatController constructor.
     *
     * @param TranslatorInterface $translator
     * @param CombatService       $combatService
     * @param TurnService         $turnService
     */
    public function __construct(TranslatorInterface $translator, CombatService $combatService, TurnService $turnService)
    {
        parent::__construct($translator);

        $this->combatService = $combatService;
        $this->turnService   = $turnService;
    }

    /**
     * @param Combat    $combat
     * @param ProxyCard $proxy
     * @param Request   $request
     *
     * @return JsonResponse
     * @throws CombatException
     * @Route(
     *     "/combat/{combat}/play-card/{proxy}",
     *     options = { "expose" = true },
     *     name="rebelion_combat_play_card"
     * )
     */
    public function playCard(Combat $combat, ProxyCard $proxy, Request $request)
    {
        try {
            $this->combatService->setCombat($combat);

            $message = $this->combatService->playCard($proxy, $request);
        } catch (\Exception $e) {
            $message = sprintf('%s - %s()', self::class, __FUNCTION__);

            throw new CombatException($message, $combat, $e);
        }

        return new JsonResponse([
            'message' => $message,
        ], Response::HTTP_OK);
    }

    /**
     * @param Combat    $combat
     * @param ProxyCard $proxy
     *
     * @return JsonResponse
     * @throws CombatException
     * @Route(
     *     "/combat/{combat}/discard-card/{proxy}",
     *     options = { "expose" = true },
     *     name="rebelion_combat_discard_card"
     * )
     */
    public function discardCard(Combat $combat, ProxyCard $proxy)
    {
        try {
            $this->combatService->setCombat($combat);

            $message = $this->combatService->discardCard($proxy);
        } catch (\Exception $e) {
            $message = sprintf('%s - %s()', self::class, __FUNCTION__);

            throw new CombatException($message, $combat, $e);
        }

        return new JsonResponse([
            'message' => $message,
        ], Response::HTTP_OK);
    }
}