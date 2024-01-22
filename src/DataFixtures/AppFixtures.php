<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Team;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    private $passwordHasher;
    private $faker;

    public function __construct(
        UserPasswordHasherInterface $passwordHasher
    )
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create('fr_FR');
    }
    
    public function load(ObjectManager $manager): void
    {
        $team = new Team;
        $team->setEmail('xavier.tezza@comnstay.fr');
        $hashedPassword = $this->passwordHasher->hashPassword(
            $team,
            'motdepasse'
        );
        $team->setPassword($hashedPassword);
        $team->setRoles(['ROLE_SUPER_ADMIN']);
        $team->setFirstname('Xavier');
        $team->setLastname('TEZZA');
        $manager->persist($team);

        $manager->flush();

        for($i=1; $i<=3; $i++) {
            $user[$i] = new User;
            $user[$i]->setEmail('user' . $i . '@symfony.local');
            $user[$i]->setFirstname($this->faker->firstname());
            $user[$i]->setLastname($this->faker->lastname());
            $hashedPassword = $this->passwordHasher->hashPassword($user[$i], 'motdepasse');
            $user[$i]->setPassword($hashedPassword);
            $roles = ['ROLE_IDENTIFIED', 'ROLE_CUSTOMER', 'ROLE_ADMIN_CUSTOMER'];
            $role = array_rand(array_flip($roles), 1);
            $user[$i]->setRoles([$role]);
            $manager->persist($user[$i]);
        }
        
        $manager->flush();
    }
}
