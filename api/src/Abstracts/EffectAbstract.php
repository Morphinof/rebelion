<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 12/01/2018
 * Time: 19:44
 */

namespace Rebelion\Abstracts;

use Doctrine\ORM\EntityManagerInterface;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Exceptions\CombatException;
use Rebelion\Interfaces\EffectInterface;
use Rebelion\Service\CombatService;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

abstract class EffectAbstract implements EffectInterface
{
    /** @var EntityManagerInterface $em */
    protected $em;

    /** @var EventDispatcherInterface $dispatcher */
    protected $dispatcher;

    /**
     * EffectAbstract constructor.
     *
     * @param EntityManagerInterface   $entityManager
     * @param EventDispatcherInterface $dispatcher
     */
    public function __construct(EntityManagerInterface $entityManager, EventDispatcherInterface $dispatcher)
    {
        $this->em         = $entityManager;
        $this->dispatcher = $dispatcher;
    }

    /**
     * The resolve function must be implemented by children
     *
     * @param Combat      $combat
     * @param ProxyEffect $proxy
     * @param array       $targets
     *
     * @return bool
     */
    abstract public function resolve(Combat $combat, ProxyEffect $proxy, array &$targets): bool;
}
