<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 31/12/2017
 * Time: 19:08
 */

namespace Rebelion\DataFixtures;

use Rebelion\Effect\GainAp;
use Rebelion\Effect\Attack;
use Rebelion\Effect\Defend;
use Rebelion\Effect\Discard;
use Rebelion\Effect\Draw;
use Rebelion\Effect\Heal;
use Rebelion\Effect\Leech;
use Rebelion\Effect\Steal;
use Rebelion\Effect\Stone;
use Rebelion\Entity\Effect\Effect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Rebelion\Service\EffectService;

class EffectsFixtures extends Fixture
{
    # Effects references
    const DRAW = 'effect-draw';
    const ATTACK = 'effect-attack';
    const DEFEND = 'effect-defence';
    const DISCARD = 'effect-discard';
    const HEAL = 'effect-heal';
    const LEECH = 'effect-leech';
    const STEAL = 'effect-steal';
    const STONE = 'effect-stone';
    const GAIN_AP = 'effect-gain-ap';

    /**
     * List of effect classes
     *
     * @var array
     */
    private static $effects = [
        self::ATTACK  => Attack::class,
        self::DEFEND  => Defend::class,
        self::DISCARD => Discard::class,
        self::DRAW    => Draw::class,
        self::HEAL    => Heal::class,
        self::LEECH   => Leech::class,
        self::STEAL   => Steal::class,
        self::STONE   => Stone::class,
        self::GAIN_AP => GainAp::class,
    ];

    /** @var EffectService $effectService */
    private $effectService;

    /**
     * EffectsFixtures constructor.
     *
     * @param EffectService $effectService
     */
    public function __construct(EffectService $effectService)
    {
        $this->effectService = $effectService;
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     * @throws \ReflectionException
     */
    public function load(ObjectManager $manager): void
    {
        foreach (self::$effects as $reference => $class) {
            $effect = $this->createEffect($class);

            $manager->persist($effect);

            $this->addReference($reference, $effect);
        }

        $manager->flush();
    }

    /**
     * @param string $class
     *
     * @return Effect
     * @throws \ReflectionException
     */
    private function createEffect(string $class): Effect
    {
        $defaults = $this->effectService->getEffectClassParameters($class);

        $name        = $defaults['name'];
        $description = $defaults['description'];
        $targetMode  = $defaults['targetMode'];
        $targetType  = $defaults['targetType'];

        $effect = new Effect($defaults);
        $effect->setName($name);
        $effect->setClass($class);
        $effect->setTargetMode($targetMode);
        $effect->setTargetType($targetType);
        $effect->setDescription($description);

        return $effect;
    }
}