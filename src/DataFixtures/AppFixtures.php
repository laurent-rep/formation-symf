<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create("FR-fr");

        for ($i = 1; $i <= 30; $i++) {

            $annonce = new Annonce();

            $fTitle = $faker->sentence();
            $fImage = $faker->imageUrl(1000, 350);
            $fIntro = $faker->paragraph(2);
            $fContent = '<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>';

            $annonce->setTitle($fTitle)
                ->setCoverImage($fImage)
                ->setIntro($fIntro)
                ->setContent($fContent)
                ->setPrice(mt_rand(12, 520))
                ->setRooms(mt_rand(1, 8));

            for ($j = 1; $j <= mt_rand(2, 5); $j++) {

                $image = new Image();
                $image->setUrl($faker->imageUrl())
                    ->setCaption($faker->sentence())
                    ->setAnnonce($annonce);


                $manager->persist($image);
            }

            $manager->persist($annonce);
        }

        $manager->flush();
    }
}
