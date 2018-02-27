<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 10/12/2017
 * Time: 13:56
 */

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Trait CharacteristicsTrait
 *
 * @package Rebelion\Traits
 */
trait CharacteristicsTrait
{
    /**
     * Strength value
     *
     * @var integer $strength
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $strength = 0;

    /**
     * Dexterity value
     *
     * @var integer $dexterity
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $dexterity = 0;

    /**
     * Intellect value
     *
     * @var integer $intellect
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $intellect = 0;

    /**
     * Constitution value
     *
     * @var integer $constitution
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $constitution = 0;

    /**
     * Luck value
     *
     * @var integer $luck
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $luck = 0;

    /**
     * Hand size
     *
     * @var integer $handSize
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $handSize = 5;

    /**
     * Action points
     *
     * @var integer $ap
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $ap = 3;

    /**
     * Hit points
     *
     * @var integer $hp
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $hp = 80;

    /**
     * Defence
     *
     * @var integer $defence
     *
     * @ORM\Column(type="integer")
     * @Assert\NotBlank()
     */
    protected $defence = 0;

    /**
     * @return int
     */
    public function getStrength(): int
    {
        return $this->strength;
    }

    /**
     * @param int $strength
     */
    public function setStrength(int $strength): void
    {
        $this->strength = $strength;
    }

    /**
     * @return int
     */
    public function getDexterity(): int
    {
        return $this->dexterity;
    }

    /**
     * @param int $dexterity
     */
    public function setDexterity(int $dexterity): void
    {
        $this->dexterity = $dexterity;
    }

    /**
     * @return int
     */
    public function getIntellect(): int
    {
        return $this->intellect;
    }

    /**
     * @param int $intellect
     */
    public function setIntellect(int $intellect): void
    {
        $this->intellect = $intellect;
    }

    /**
     * @return int
     */
    public function getConstitution(): int
    {
        return $this->constitution;
    }

    /**
     * @param int $constitution
     */
    public function setConstitution(int $constitution): void
    {
        $this->constitution = $constitution;
    }

    /**
     * @return int
     */
    public function getLuck(): int
    {
        return $this->luck;
    }

    /**
     * @param int $luck
     */
    public function setLuck(int $luck): void
    {
        $this->luck = $luck;
    }

    /**
     * @return int
     */
    public function getHandSize(): int
    {
        return $this->handSize;
    }

    /**
     * @param int $handSize
     */
    public function setHandSize(int $handSize): void
    {
        $this->handSize = $handSize;
    }

    /**
     * @return int
     */
    public function getAp(): int
    {
        return $this->ap;
    }

    /**
     * @param int $ap
     */
    public function setAp(int $ap): void
    {
        $this->ap = $ap;
    }

    /**
     * @return int
     */
    public function getHp(): int
    {
        return $this->hp;
    }

    /**
     * @param int $hp
     */
    public function setHp(int $hp): void
    {
        $this->hp = $hp;
    }

    /**
     * @return int
     */
    public function getDefence(): int
    {
        return $this->defence;
    }

    /**
     * @param int $defence
     */
    public function setDefence(int $defence): void
    {
        $this->defence = $defence;
    }

    /**
     * @param string $characteristic
     *
     * @return integer
     */
    public function getCharacteristic(string $characteristic): int
    {
        return $this->$characteristic;
    }

    /**
     * CharacteristicsTrait
     *
     * @param string $characteristic
     * @param int    $value
     *
     * @return self
     */
    public function setCharacteristic(string $characteristic, int $value): self
    {
        $this->$characteristic = $value;

        return $this;
    }

    /**
     * CharacteristicsTrait
     *
     * @param string $characteristic
     * @param int    $value
     *
     * @return self
     */
    public function alterCharacteristic(string $characteristic, int $value): self
    {
        $this->$characteristic += $value;

        return $this;
    }
}