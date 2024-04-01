<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Faker\Generator;
use App\Entity\Component;
use App\Entity\Variables;
use App\Entity\PrivateUser;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * Password Hasher
     *
     * @var UserPasswordHasherInterface
     */
    private $userPasswordHasher;
    /**
     * @var Generator
     */

    private Generator $faker;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->faker = Factory::create('fr_FR');
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {

        $privateUsers = [];
        for( $i = 0; $i < 25; $i++){
            $gender = random_int(0,1);
            $genderStr = $gender ? 'male' : 'female';

            $privateUser = new PrivateUser();
            $birthdateStart = new \DateTime('1980-01-01');
            $birthdateEnd = new \DateTime('2007-12-31');
            $created = $this->faker->dateTimeBetween("-1 week","now");
            $updated = $this->faker->dateTimeBetween($created,"now");
            $birthDate =  $this->faker->dateTimeBetween($birthdateStart,$birthdateEnd);

            $privateUser
            ->setName($this->faker->lastName($genderStr))
            ->setSurname($this->faker->firstName($genderStr))
            ->setEmail($this->faker->email())
            ->setPhone($this->faker->e164PhoneNumber())
            ->setCreatedAt($created)
            ->setUpdatedAt($updated)
            ->setAnonymous(false)
            ->setGender($gender);
            $manager->persist($privateUser);

            $privateUsers[] = $privateUser;
        }

        $user = [];

        $publicUser = new User();
        $publicUser->setUsername("public");
        $publicUser->setRoles(["PUBLIC"]);
        $publicUser->setPrivateUser($privateUsers[array_rand($privateUsers,1)]);
        $publicUser->setPassword($this->userPasswordHasher->hashPassword($publicUser, "public"));
        $manager->persist($publicUser);
        $users[] = $publicUser; 

        for( $i = 0; $i < 5; $i++){
            $userUser = new User();
            $password = $this->faker->password();
            $userUser->setUsername($this->faker->username() . "@" . $password);
            $userUser->setRoles(["USER"]);
            $userUser->setPassword($this->userPasswordHasher->hashPassword($userUser, $password));
            $userUser->setPrivateUser($privateUsers[array_rand($privateUsers,1)]);
            $manager->persist($userUser);
            $users[] = $userUser;
        }

          // Admins
          $adminUser = new User();
          $adminUser->setUsername("admin");
          $adminUser->setRoles(["ADMIN"]);
          $adminUser->setPassword($this->userPasswordHasher->hashPassword($adminUser, "password"));
          $adminUser->setPrivateUser($privateUsers[array_rand($privateUsers, 1)]);
          $manager->persist($adminUser);
          $users[] = $adminUser;
  
        $componentEntries = [];
        for ($i = 0; $i < 100; $i++) {
            $component = new Component();
            $created = $this->faker->dateTimeBetween("-1 week","now");
            $updated = $this->faker->dateTimeBetween($created,"now");
            //Asign properties to Entity
            $component
                ->setName($this->faker->name())
                ->setType($this->faker->randomElement(['card', 'button', 'form', 'ul', 'li']))
                ->setPrix($this->faker->randomNumber(2))
                ->setCreatAt($created)
                ->setUpdateAt($updated)
                ->setStatus("Pas commencé")
                ->setCode("
                background-color: #2ea44f;
                border: 1px solid rgba(27, 31, 35, .15);
              }");
            $componentEntries[] = $component;
            $manager->persist($component);
        }
        $variablesEntries = [];
        //boucle qui itere 
        for( $i = 0; $i < 100; $i++) {
            $variable = new Variables();
            $createdVar = $this->faker->dateTimeBetween("-1 week","now");
            $updatedVar = $this->faker->dateTimeBetween($createdVar,"now");

            $variable 
                ->setName($this->faker->name())
                ->setType($this->faker->randomElement(['card', 'button', 'form', 'ul', 'li']))
                ->setCreatedAt($createdVar)
                ->setUpdatedAt($updatedVar)
                ->setContent($this->faker->text(10))
                ->setStatus("Pas commencé");
            $variablesEntries[] = $variable;
            $manager->persist($variable);
        }
        foreach ($variablesEntries as $key => $variablesEntry){
            $component = $componentEntries[array_rand($componentEntries, 1)];
            $variablesEntry->setComponent($component);
            $manager->persist($variablesEntry);
        }
        $manager->flush();
    }
}
