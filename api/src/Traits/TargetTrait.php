<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 22/12/2017
 * Time: 20:04
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;
use Rebelion\Entity\Container\ProxyCard;
use Rebelion\Entity\Pile\Deck;
use Rebelion\Entity\Pile\Discard;
use Rebelion\Entity\Pile\Draw;
use Rebelion\Entity\Pile\Exile;
use Rebelion\Entity\Pile\Hand;
use Rebelion\Entity\Player\Player;

/**
 * Trait TargetTrait
 *
 * @package Rebelion\Traits
 */
trait TargetTrait
{
    use EntityTrait;

    /**
     * @var Player
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Player\Player", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    protected $player;

    /**
     * @var Deck
     *
     * One Target (Ai or Player) has One Deck pile.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Pile\Deck", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="deck_id", referencedColumnName="id")
     */
    protected $deck;

    /**
     * @var Hand
     *
     * One Target (Ai or Player) has One Hand pile.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Pile\Hand", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="hand_id", referencedColumnName="id")
     */
    protected $hand;

    /**
     * @var Draw
     *
     * One Target (Ai or Player) has One Draw pile.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Pile\Draw", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="draw_id", referencedColumnName="id")
     */
    protected $draw;

    /**
     * @var Discard
     *
     * One Target (Ai or Player) has One Discard pile.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Pile\Discard", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="discard_id", referencedColumnName="id")
     */
    protected $discard;

    /**
     * @var Exile
     *
     * One Target (Ai or Player) has One Exile pile.
     *
     * @ORM\ManyToOne(targetEntity="Rebelion\Entity\Pile\Exile", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="exile_id", referencedColumnName="id")
     */
    protected $exile;

    /**
     * @var bool
     *
     * @ORM\Column(name="dead", type="boolean", nullable=false, options={"default":false})
     */
    protected $dead = false;

    /**
     * TargetTrait constructor.
     *
     * @param Player $player
     * @param Deck   $deck
     */
    public function __construct($player, Deck $deck)
    {
        $this->player  = $player;
        $this->deck    = $deck;
        $this->hand    = new Hand();
        $this->draw    = new Draw();
        $this->discard = new Discard();
        $this->exile   = new Exile();
    }

    /**
     * @return Player
     */
    public function getPlayer(): Player
    {
        return $this->player;
    }

    /**
     * @param Player $player
     */
    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    /**
     * @return Deck
     */
    public function getDeck(): Deck
    {
        return $this->deck;
    }

    /**
     * @param mixed $deck
     */
    public function setDeck($deck): void
    {
        $this->deck = $deck;
    }

    /**
     * @return Draw
     */
    public function getDraw(): Draw
    {
        return $this->draw;
    }

    /**
     * @param mixed $draw
     */
    public function setDraw($draw): void
    {
        $this->draw = $draw;
    }

    /**
     * @return Discard
     */
    public function getDiscard(): Discard
    {
        return $this->discard;
    }

    /**
     * @param $discard
     */
    public function setDiscard($discard): void
    {
        $this->discard = $discard;
    }

    /**
     * @return Hand
     */
    public function getHand()
    {
        return $this->hand;
    }

    /**
     * @param mixed $hand
     */
    public function setHand($hand): void
    {
        $this->hand = $hand;
    }

    /**
     * @return Exile
     */
    public function getExile(): Exile
    {
        return $this->exile;
    }

    /**
     * @param Exile $exile
     */
    public function setExile(Exile $exile): void
    {
        $this->exile = $exile;
    }

    /**
     * @return bool
     */
    public function isDead(): bool
    {
        return $this->dead;
    }

    /**
     * @param bool $dead
     */
    public function setDead(bool $dead): void
    {
        $this->dead = $dead;
    }

    /**
     * @param ProxyCard $proxy
     *
     * @return null|Discard|Draw|Exile|Hand
     */
    public function getCardPile(ProxyCard $proxy)
    {
        if ($this->hand->getCards()->contains($proxy)) {
            return $this->hand;
        }

        if ($this->draw->getCards()->contains($proxy)) {
            return $this->draw;
        }

        if ($this->discard->getCards()->contains($proxy)) {
            return $this->discard;
        }

        if ($this->exile->getCards()->contains($proxy)) {
            return $this->exile;
        }

        return null;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf('Target #%s', $this->id);
    }
}