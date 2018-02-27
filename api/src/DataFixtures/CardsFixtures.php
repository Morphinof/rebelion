<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 31/12/2017
 * Time: 19:08
 */

namespace Rebelion\DataFixtures;

use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Effect\Effect;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CardsFixtures extends Fixture implements DependentFixtureInterface
{
    # Cards references
    const ATTACK = 'card-attack';
    const DEFEND = 'card-defend';
    const AVIDITY = 'card-avidity';
    const DOUBLE_ATTACK = 'card-double-attack';
    const TRIPLE_ATTACK = 'card-triple-attack';
    const ALL_IN_ONE = 'card-all-in-one';
    const VOID = 'card-void';
    const HEAL = 'card-heal';
    const LEECH = 'card-leech';
    const STEAL = 'card-steal';
    const STONE = 'card-stone';
    const WILD_GROW = 'card-wild-grow';

    /**
     * @var array
     */
    private static $cards = [
        self::ATTACK        => [
            'name'        => 'Attack',
            'description' => 'Attack for X damages',
            'cost'        => 0,
            'effects'     => ['attackEffect']
        ],
        self::DEFEND        => [
            'name'        => 'Defend',
            'description' => 'Gain X defence',
            'cost'        => 0,
            'effects'     => ['defendEffect']
        ],
        self::AVIDITY       => [
            'name'        => 'Avidity',
            'description' => 'Draw X card(s)',
            'cost'        => 0,
            'effects'     => ['drawEffect']
        ],
        self::DOUBLE_ATTACK => [
            'name'        => 'Double attack',
            'description' => 'Attack 2 times for X',
            'cost'        => 2,
            'effects'     => ['attackEffect', 'attackEffect']
        ],
        self::TRIPLE_ATTACK => [
            'name'        => 'Triple attack',
            'description' => 'Attack 3 times for X',
            'cost'        => 3,
            'effects'     => ['attackEffect', 'attackEffect', 'attackEffect']
        ],
        self::ALL_IN_ONE    => [
            'name'        => 'All in one',
            'description' => 'Attack for X, Defend for X and Draw X card(s), all in one !',
            'cost'        => 2,
            'effects'     => ['attackEffect', 'defendEffect', 'drawEffect']
        ],
        self::VOID          => [
            'name'        => 'Void',
            'description' => 'Target discards X card(s)',
            'cost'        => 1,
            'effects'     => ['discardEffect']
        ],
        self::HEAL          => [
            'name'        => 'Heal',
            'description' => 'Player heals X HP(s)',
            'cost'        => 1,
            'effects'     => ['healEffect']
        ],
        self::LEECH         => [
            'name'        => 'Leech',
            'description' => 'Target attack for X and leech X HP(s) from the target',
            'cost'        => 1,
            'effects'     => ['leechEffect']
        ],
        self::STEAL         => [
            'name'        => 'Steal',
            'description' => 'Player steal X card(s) from the target hand',
            'cost'        => 1,
            'effects'     => ['stealEffect']
        ],
        self::STONE         => [
            'name'        => 'Stone',
            'description' => 'Player stone X card(s) from the target hand',
            'cost'        => 1,
            'effects'     => ['stoneEffect']
        ],
        self::WILD_GROW     => [
            'name'        => 'Wild grow',
            'description' => 'Player gain X AP',
            'cost'        => 1,
            'effects'     => ['gainApEffect']
        ],
    ];

    /** @var Effect $attackEffect */
    private $attackEffect;

    /** @var Effect $defendEffect */
    private $defendEffect;

    /** @var Effect $drawEffect */
    private $drawEffect;

    /** @var Effect $discardEffect */
    private $discardEffect;

    /** @var Effect $healEffect */
    private $healEffect;

    /** @var Effect $leechEffect */
    private $leechEffect;

    /** @var Effect $stealEffect */
    private $stealEffect;

    /** @var Effect $stealEffect */
    private $stoneEffect;

    /** @var Effect $gainApEffect */
    private $gainApEffect;

    /**
     * @param ObjectManager $manager
     *
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadEffects();

        foreach (array_keys(self::$cards) as $reference) {
            $card = $this->createCard($reference);

            $manager->persist($card);

            $this->addReference($reference, $card);
        }

        $manager->flush();
    }

    /**
     * Load Effects
     */
    private function loadEffects(): void
    {
        $this->attackEffect  = $this->getReference(EffectsFixtures::ATTACK);
        $this->defendEffect  = $this->getReference(EffectsFixtures::DEFEND);
        $this->drawEffect    = $this->getReference(EffectsFixtures::DRAW);
        $this->discardEffect = $this->getReference(EffectsFixtures::DISCARD);
        $this->healEffect    = $this->getReference(EffectsFixtures::HEAL);
        $this->leechEffect   = $this->getReference(EffectsFixtures::LEECH);
        $this->stealEffect   = $this->getReference(EffectsFixtures::STEAL);
        $this->stoneEffect   = $this->getReference(EffectsFixtures::STONE);
        $this->gainApEffect  = $this->getReference(EffectsFixtures::GAIN_AP);
    }

    /**
     * @param string $reference
     *
     * @return Card
     */
    private function createCard(string $reference): Card
    {
        $data        = self::$cards[$reference];
        $name        = $data['name'];
        $description = $data['description'];
        $cost        = (int)$data['cost'];
        $effects     = $data['effects'];

        $card = new Card();
        $card->setName($name);
        $card->setDescription($description);
        $card->setCost($cost);
        foreach ($effects as $effectVarName) {
            $card->addEffect($this->{$effectVarName});
        }

        return $card;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            EffectsFixtures::class,
        ];
    }
}
