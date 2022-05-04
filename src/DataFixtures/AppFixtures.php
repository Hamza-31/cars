<?php

namespace App\DataFixtures;

use App\Entity\Cars;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $brands=['Renault', 'Audi','BMW', 'Tesla','Peugeot'];
        // $product = new Product();
        // $manager->persist($product);
        for ($i=0;$i<count($brands); $i++){
            $car = new Cars();
            $car->setBrand($brands[$i]);
            $manager->persist($car);
        }
        $manager->flush();
    }
}
