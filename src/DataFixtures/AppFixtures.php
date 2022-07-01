<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Group;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('en_EN');

        $groups = [];
        for ($i = 0; $i < 10; $i++) {
            $group = new Group();
            $group->setName('Group ' . $i);
            $groups[] = $group;
            $manager->persist($group);
        }

        $tricks = [];
        for ($i = 1; $i < 11; $i++) {
            $trick = new Trick();
            $trick->setName('Trick ' . $i)
                ->setDescription($faker->sentence())
                ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'))
                ->setUpdatedAt($faker->dateTimeBetween('-1 years', 'now'))
                ->addVideo($this->getReference('video_' . $i))
                ->setGroupName($faker->randomElement($groups));
            $tricks[] = $trick;
            $manager->persist($trick);
        }

        for ($i = 0; $i < 10; $i++) {
            $comment = new Comment();
            $comment->setComment($faker->sentence())
                ->setCreatedAt($faker->dateTimeBetween('-1 years', 'now'))
                ->setTrick($faker->randomElement($tricks));
            $manager->persist($comment);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return array(
            VideoFixtures::class,
        );
    }
}
