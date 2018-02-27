<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 13/12/2017
 * Time: 19:57
 */

namespace Rebelion\Command;

use Doctrine\ORM\EntityManagerInterface;
use Rebelion\Abstracts\CommandAbstract;
use Rebelion\Entity\Combat;
use Rebelion\Entity\Pile\Deck;
use Rebelion\Entity\Player\Ai;
use Rebelion\Entity\Player\Human;
use Rebelion\Entity\Race;
use Rebelion\Entity\Target;
use Rebelion\Enum\PlayerTypeEnum;
use Rebelion\Enum\RaceEnum;
use Symfony\Component\Console\Input\InputArgument;

class CreateCombatCommand extends CommandAbstract
{
    const NAME = 'rebelion:create:combat';
    const OPT_P1_TYPE = 'p1-type';
    const OPT_P1_DECK = 'p1-deck';
    const OPT_P2_TYPE = 'p2-type';
    const OPT_P2_DECK = 'p2-deck';
    const AVAILABLE_TYPES = '(available types: [%s], default: %s)';

    /**
     * Configuration
     *
     * @throws \ReflectionException
     */
    protected function configure(): void
    {
        parent::configure();

        $types = implode(', ', PlayerTypeEnum::__toArray());

        $this->setName(static::NAME)
            ->setDescription('Creates a new user combat.')
            ->setHelp('This command allows you to create a combat.')
            ->addArgument(
                static::OPT_P1_TYPE,
                InputArgument::REQUIRED,
                sprintf('Player %d user type %s', 1, $types, PlayerTypeEnum::HUMAN, static::AVAILABLE_TYPES)

            )
            ->addArgument(
                static::OPT_P1_DECK,
                InputArgument::REQUIRED,
                'a deck id is required for player 1'
            )
            ->addArgument(
                static::OPT_P2_TYPE,
                InputArgument::REQUIRED,
                sprintf('Player %d user type %s', 2, $types, PlayerTypeEnum::AI, static::AVAILABLE_TYPES)
            )
            ->addArgument(
                static::OPT_P2_DECK,
                InputArgument::REQUIRED,
                'a deck id is required for player 2'
            );
    }

    /**
     * Creates a combat
     *
     * @return bool
     * @throws \ReflectionException
     */
    protected function command(): bool
    {
        $args  = $this->getArguments();
        $trans = $this->getContainer()->get('translator');
        $em    = $this->getContainer()->get('doctrine.orm.entity_manager');

        if ($args !== null) {
            echo sprintf(
                "Creating combat %s with deck %s vs %s with deck %s\n",
                $trans->trans($args[static::OPT_P1_TYPE]),
                $args[static::OPT_P1_DECK],
                $trans->trans($args[static::OPT_P2_TYPE]),
                $args[static::OPT_P2_DECK]
            );

            /** @var Human|Ai $player1 */
            $player1 = null;
            /** @var Human|Ai $player2 */
            $player2 = null;

            if ($args[static::OPT_P1_TYPE] == PlayerTypeEnum::HUMAN) {
                $player1 = new Human('HUMAN');
            } else {
                $player1 = new Ai('DUMMY');
            }

            if ($args[static::OPT_P2_TYPE] == PlayerTypeEnum::HUMAN) {
                $player2 = new Human('HUMAN');
            } else {
                $player2 = new Ai('DUMMY');
            }

            /** @var Deck $deck1 */
            $deck1 = $em->getRepository('Rebelion:Pile\Deck')->find($args[static::OPT_P1_DECK]);

            /** @var Deck $deck2 */
            $deck2 = $em->getRepository('Rebelion:Pile\Deck')->find($args[static::OPT_P2_DECK]);

            if ($deck1 !== null) {
                $target1 = new Target($player1, $deck1);
            } else {
                echo sprintf("Unable to load player's 1 deck #%s\n", $args[static::OPT_P1_DECK]);

                return false;
            }

            if ($deck2 !== null) {
                $target2 = new Target($player2, $deck2);
            } else {
                echo sprintf("Unable to load player's 2 deck #%s\n", $args[static::OPT_P2_DECK]);

                return false;
            }

            /** @var Race $skeleton */
            $skeleton = $em->getRepository('Rebelion:Race')->findOneBy(['slug' => RaceEnum::SKELETON]);

            if ($skeleton === null) {
                echo sprintf("Unable to load default race [%s]\n", RaceEnum::SKELETON);

                return false;
            }

            $player1->setRace($skeleton);
            $player2->setRace($skeleton);

            $combat = new Combat();
            $combat->setPlayer($target1);
            $combat->setAdversary($target2);

            /** @var EntityManagerInterface $em */
            $em = $this->getContainer()->get('doctrine')->getManager();
            $em->persist($combat);
            $em->flush();

            echo sprintf("Combat %s created with players [%s, %s] !\n", $combat->getId(), $player1->getId(), $player2->getId());
        }

        return true;
    }

    /**
     * Get an associative array with command arguments
     *
     * @return array
     * @throws \ReflectionException
     */
    private function getArguments(): array
    {
        $player1Type = $this->input->getArgument(static::OPT_P1_TYPE);
        $player1Deck = $this->input->getArgument(static::OPT_P1_DECK);
        $player2Type = $this->input->getArgument(static::OPT_P2_TYPE);
        $player2Deck = $this->input->getArgument(static::OPT_P2_DECK);
        $types       = implode(', ', PlayerTypeEnum::__toArray());

        if (!in_array($player1Type, PlayerTypeEnum::__toArray())) {
            echo sprintf(
                "Unknown player type [%s] for player %d, %s\n",
                $player1Type,
                1,
                sprintf(static::AVAILABLE_TYPES, $types, PlayerTypeEnum::HUMAN)
            );

            return null;
        }

        if (!in_array($player2Type, PlayerTypeEnum::__toArray())) {
            echo sprintf(
                "Unknown player type [%s] for player %d, %s\n",
                $player2Type,
                2,
                sprintf(static::AVAILABLE_TYPES, $types, PlayerTypeEnum::AI)
            );

            return null;
        }

        return [
            static::OPT_P1_TYPE => $player1Type,
            static::OPT_P1_DECK => $player1Deck,
            static::OPT_P2_TYPE => $player2Type,
            static::OPT_P2_DECK => $player2Deck,
        ];
    }
}