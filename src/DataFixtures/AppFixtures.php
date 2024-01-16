<?php

namespace App\DataFixtures;

use App\Entity\Ingredients;
use App\Entity\Recettes;
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
        // ingredients
        $ingredientstab = [];
        for ($i=0; $i < 50 ; $i++) { 
            $ingredient = new Ingredients();
            $ingredient->setNom($this->faker->word())
                    ->setPrix(mt_rand(1,200));
            $ingredientstab[] = $ingredient;
            $manager->persist($ingredient);
        }
       
        //recettes
        for ($i=0; $i < 25; $i++) { 
            $recette = new Recettes();
            $recette->setNom($this->faker->word())
                ->setTemps(mt_rand(0,1) == 1 ? mt_rand(1,1440) : null)
                ->setPersonne(mt_rand(0,1) == 1 ? mt_rand(1,50) : null)
                ->setDifficulte(mt_rand(0,1) == 1 ? mt_rand(1,5) : null)
                ->setDescription($this->faker->text(300))
                ->setPrix(mt_rand(0,1) == 1 ? mt_rand(1,500) : null)
                ->setFavori(mt_rand(0,1) == 1 ? true : false);

            for ($j=0; $j < mt_rand(5, 15) ; $j++) {
                $recette->addIngredient($ingredientstab[mt_rand(0, count($ingredientstab)-1)]);
            }
            $manager->persist($recette);
        }


        $manager->flush();
    }
}
