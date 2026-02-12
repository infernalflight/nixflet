<?php

namespace App\DataFixtures;

use App\Entity\Serie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SerieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        for ($i=0; $i < 1000; $i++) {
            $serie = new Serie();
            $serie->setName($faker->realText(30))
                ->setOverview($faker->realText(100))
                ->setStatus($faker->randomElement(['returning', 'ended', 'Canceled']))
                ->setVote($faker->randomFloat(2, 1, 9.5))
                ->setGenres($faker->randomElement(['Thriller', 'Horror', 'Policier allemand', 'Drama', 'Western', 'SF', 'Comedy']))
                ->setPopularity($faker->randomFloat(2, 200, 1000))
                ->setFirstAirDate($faker->dateTimeBetween('-10 year', '- 1 month'))
                ->setDateCreated($faker->dateTimeBetween($serie->getFirstAirDate()));

            if ($serie->getStatus() !== 'returning') {
                $serie->setLastAirDate($faker->dateTimeBetween($serie->getFirstAirDate(), '-3 days'));
            }

            $manager->persist($serie);
        }

        $manager->flush();
    }
}
