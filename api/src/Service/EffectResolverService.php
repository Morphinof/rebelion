<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 15/12/2017
 * Time: 22:08
 */

namespace Rebelion\Service;

use Rebelion\Abstracts\ServiceAbstract;
use Doctrine\ORM\EntityManagerInterface;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Effect\ProxyEffect;
use Rebelion\Interfaces\EffectInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EffectResolverService extends ServiceAbstract
{
    /**@var EntityManagerInterface $em */
    private $em;

    /** @var EventDispatcherInterface $dispatcher */
    private $dispatcher;

    /**
     * EffectService constructor.
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
     * @param Combat      $combat
     * @param ProxyEffect $proxy
     *
     * @param array       $targets
     *
     * @return bool
     */
    public function resolve(Combat $combat, ProxyEffect $proxy, array $targets): bool
    {
        $class = $proxy->getParent()->getClass();

        /** @var EffectInterface $resolveInstance */
        $resolveInstance = new $class($this->em, $this->dispatcher);

        if ($resolveInstance->resolve($combat, $proxy, $targets)) {
            return true;
        }

        return false;
    }
}