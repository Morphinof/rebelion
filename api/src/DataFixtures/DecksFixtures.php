<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 31/12/2017
 * Time: 19:08
 */

namespace Rebelion\DataFixtures;

use Rebelion\Entity\Container\Card;
use Rebelion\Entity\Pile\Deck;
use Rebelion\Enum\DeckStateEnum;
use Rebelion\Service\DeckBuilderService;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class DecksFixtures extends Fixture implements DependentFixtureInterface
{
    # Decks references
    const PLAYER = 'deck-player';
    const DUMMY = 'deck-dummy';

    # Cards lists
    const ALL_CARDS_SINGLE_SAMPLE = [
        'attack'       => 1,
        'doubleAttack' => 1,
        'tripleAttack' => 1,
        'defend'       => 1,
        'avidity'      => 1,
        'allInOne'     => 1,
        'void'         => 1,
        'heal'         => 1,
        'steal'        => 1,
        'stone'        => 1,
        'wildGrow'     => 1,
    ];

    /**
     * @var array
     */
    private static $decks = [
        self::PLAYER => [
            'name'        => 'Player',
            'description' => 'Player deck',
            'state'       => DeckStateEnum::DRAFT,
            'cards'       => self::ALL_CARDS_SINGLE_SAMPLE,
        ],
        self::DUMMY  => [
            'name'        => 'Dummy',
            'description' => 'Dummy deck',
            'state'       => DeckStateEnum::PUBLISHED,
            'cards'       => self::ALL_CARDS_SINGLE_SAMPLE,
        ]
    ];
    /** @var Card $attack */
    private $attack;

    /** @var Card $doubleAttack */
    private $doubleAttack;

    /** @var Card $tripleAttack */
    private $tripleAttack;

    /** @var Card $defend */
    private $defend;

    /** @var Card $avidity */
    private $avidity;

    /** @var Card $allInOne */
    private $allInOne;

    /** @var Card $void */
    private $void;

    /** @var Card $heal */
    private $heal;

    /** @var Card $leech */
    private $leech;

    /** @var Card $steal */
    private $steal;

    /** @var Card $stone */
    private $stone;

    /** @var Card $wildGrow */
    private $wildGrow;

    /**
     * Add N cards to the given deck
     *
     * @param Deck $deck
     * @param Card $card
     * @param int  $number
     */
    private function addCard(Deck $deck, Card $card, $number = DeckBuilderService::GLOBAL_DUPLICATES_LIMIT): void
    {
        if ($number > DeckBuilderService::GLOBAL_DUPLICATES_LIMIT) {
            $number = DeckBuilderService::GLOBAL_DUPLICATES_LIMIT;
        }

        for ($i = 0; $i < $number; $i++) {
            $deck->addCard($card);
        }
    }

    /**
     * @param ObjectManager $manager
     *
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    public function load(ObjectManager $manager): void
    {
        $this->loadCards();

        foreach (array_keys(self::$decks) as $reference) {
            $deck = $this->createDeck($reference);

            $manager->persist($deck);

            $this->addReference($reference, $deck);
        }

        $manager->flush();
    }

    /**
     * Load Cards
     */
    private function loadCards(): void
    {
        $this->attack       = $this->getReference(CardsFixtures::ATTACK);
        $this->doubleAttack = $this->getReference(CardsFixtures::DOUBLE_ATTACK);
        $this->tripleAttack = $this->getReference(CardsFixtures::TRIPLE_ATTACK);
        $this->defend       = $this->getReference(CardsFixtures::DEFEND);
        $this->avidity      = $this->getReference(CardsFixtures::AVIDITY);
        $this->allInOne     = $this->getReference(CardsFixtures::ALL_IN_ONE);
        $this->void         = $this->getReference(CardsFixtures::VOID);
        $this->heal         = $this->getReference(CardsFixtures::HEAL);
        $this->leech        = $this->getReference(CardsFixtures::LEECH);
        $this->steal        = $this->getReference(CardsFixtures::STEAL);
        $this->stone        = $this->getReference(CardsFixtures::STONE);
        $this->wildGrow     = $this->getReference(CardsFixtures::WILD_GROW);
    }

    /**
     * @param string $reference
     *
     * @return Deck
     */
    private function createDeck(string $reference): Deck
    {
        $data        = self::$decks[$reference];
        $name        = $data['name'];
        $description = $data['description'];
        $state       = $data['state'];
        $cards       = $data['cards'];

        $deck = new Deck();
        $deck->setName($name);
        $deck->setDescription($description);
        $deck->setState($state);
        foreach ($cards as $card => $number) {
            $sample = $this->{$card};

            $this->addCard($deck, $sample, $number);
        }

        return $deck;
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            CardsFixtures::class,
        ];
    }
}