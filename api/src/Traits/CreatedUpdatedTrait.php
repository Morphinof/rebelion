<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 16/12/2017
 * Time: 14:28
 */
declare(strict_types=1);

namespace Rebelion\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait CreatedUpdatedTrait
 * @see     Don't forget to add HasLifecycleCallbacks annotation to entities that use this trait
 *
 * @package Rebelion\Traits
 */
trait CreatedUpdatedTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    protected $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=false)
     */
    protected $updatedAt;

    /**
     * Auto-update createdAt and updatedAt automatically
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function autoUpdate(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));

        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}