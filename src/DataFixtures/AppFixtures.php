<?php

namespace App\DataFixtures;

use App\Entity\Component;
use App\Entity\Variables;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures extends Fixture
{
    /**
     * @var Generator
     */

    private Generator $faker;

    public function __construct(){
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $componentEntries = [];
        for ($i = 0; $i < 100; $i++) {
            $component = new Component();
            //Hanfle created && updated datetime
            $created = $this->faker->dateTimeBetween("-1 week","now");
            $updated = $this->faker->dateTimeBetween($created,"now");
 
            //Asign properties to Entity
            $component
                ->setName($this->faker->name())->setType("card")
                ->setPrix($this->faker->randomNumber(2))
                ->setCreatAt($created)
                ->setUpdateAt($updated)
                ->setStatus("Pas commencé");

            //stock Librairie entry
            $componentEntries[] = $component;
            //Add to transaction
            $manager->persist($component);
        }
        //Initialiser un tableau pret a recevoir des "Variables"
        $variablesEntries = [];

        //boucle qui itere 
        for( $i = 0; $i < 100; $i++) {
            //creer une entitée "Variable" 
            $variable = new Variables();
            //definir les propietes de "Variable"
            $createdVar = $this->faker->dateTimeBetween("-1 week","now");
            $updatedVar = $this->faker->dateTimeBetween($createdVar,"now");
            //Ajouter variables au tableau precedement créé (cf. l.46)

            $variable 
                ->setName($this->faker->name())->setType("card")
                ->setType($this->faker->randomNumber(2))
                ->setCreatedAt($createdVar)
                ->setUpdatedAt($updatedVar)
                ->setContent($this->faker->text(10))
                ->setStatus("Pas commencé");

            //Persister la donnée en bdd
            //stock Librairie entry
            $variablesEntries[] = $variable;
            $manager->persist($variable);
        }

        //Il n'y a pas d'évolution possible dans les composants 
        foreach ($variablesEntries as $key => $variablesEntry){
            // Choisir une "Variable" aléatoire dans le tableau de variable (cf.l.46) et l'attribué a variable
            $component = $componentEntries[array_rand($componentEntries, 1)];
            $variablesEntry->setComponent($component); // definir le parametre component de "Variable" 
            $manager->persist($variablesEntry); // persister la "Variable" modifiée
        }
        //Execute transaction
        $manager->flush();
    }
}
