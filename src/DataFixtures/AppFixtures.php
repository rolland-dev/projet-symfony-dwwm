<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    private Generator $faker;

    public function __construct()
    {
        $this -> faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {
        for ($i=0; $i < 50 ; $i++) { 
            $ingredient = new Ingredients();
            $ingredient->setNom($this->faker->word())
                    ->setPrix(mt_rand(1,200));
            
            $manager->persist($ingredient);
        }
       
        $manager->flush();
    }
}
