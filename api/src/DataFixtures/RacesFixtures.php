<?php
/**
 * Created by PhpStorm.
 * User: Morphinof
 * Date: 31/12/2017
 * Time: 19:08
 */

namespace Rebelion\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Rebelion\Entity\Race;

class RacesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        # Race Skeleton
        $skeleton = new Race();
        $skeleton->setName('Skeleton');
        $skeleton->setDescription('A basic skeleton, nothing fancy');

        $this->addReference('race-skeleton', $skeleton);
        $manager->persist($skeleton);

        $manager->flush();
    }
}