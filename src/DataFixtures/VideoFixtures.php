<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Group;
use App\Entity\Trick;
use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $video1 = (new Video())
            ->setUrl('https://www.youtube.com/embed/_hxLS2ErMiY');
        $manager->persist($video1);

        $video2 = (new Video())
            ->setUrl('https://www.youtube.com/embed/_Qq-YoXwNQY');
        $manager->persist($video2);

        $video3 = (new Video())
            ->setUrl('https://www.youtube.com/embed/ZlNmeM1XdM4');
        $manager->persist($video3);

        $video4 = (new Video())
            ->setUrl('https://www.youtube.com/embed/CzDjM7h_Fwo');
        $manager->persist($video4);

        $video5 = (new Video())
            ->setUrl('https://www.youtube.com/embed/9T5AWWDxYM4');
        $manager->persist($video5);

        $video6 = (new Video())
            ->setUrl('https://www.youtube.com/embed/SLncsNaU6es');
        $manager->persist($video6);

        $video7 = (new Video())
            ->setUrl('https://www.youtube.com/embed/_CN_yyEn78M');
        $manager->persist($video7);

        $video8 = (new Video())
            ->setUrl('https://www.youtube.com/embed/12OHPNTeoRs');
        $manager->persist($video8);

        $video9 = (new Video())
            ->setUrl('https://www.youtube.com/embed/kxZbQGjSg4w');
        $manager->persist($video9);

        $video10 = (new Video())
            ->setUrl('https://www.youtube.com/embed/O5DpwZjCsgA');

        $manager->persist($video10);
        $manager->flush();

        //Reference
        $this->addReference('video_1', $video1);
        $this->addReference('video_2', $video2);
        $this->addReference('video_3', $video3);
        $this->addReference('video_4', $video4);
        $this->addReference('video_5', $video5);
        $this->addReference('video_6', $video6);
        $this->addReference('video_7', $video7);
        $this->addReference('video_8', $video8);
        $this->addReference('video_9', $video9);
        $this->addReference('video_10', $video10);
    }
}
