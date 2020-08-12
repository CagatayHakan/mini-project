<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

         $product = new Product();
         $product->setName('Necklace');
         $product->setDescription('Diamond Necklace');
         $product->setPrice(200);
         $product->setColor('White');
         $product->setImage('assets/img/bootstrap-template.png');
         $manager->persist($product);

         $product = new Product();
         $product->setName('Bracelet');
         $product->setDescription('22k Gold Bracelet');
         $product->setPrice(144);
         $product->setColor('Gold');
         $product->setImage('assets/img/b.jpg');
         $manager->persist($product);

         $product = new Product();
         $product->setName('Watch');
         $product->setDescription('Analog and digital watch');
         $product->setPrice(96);
         $product->setColor('Black');
         $product->setImage('assets/img/a.jpg');
         $manager->persist($product);

         $product = new Product();
         $product->setName('Necklace');
         $product->setDescription('Diamond Necklace');
         $product->setPrice(250);
         $product->setColor('Colored');
         $product->setImage('assets/img/f.jpg');
         $manager->persist($product);

         $product = new Product();
         $product->setName('Solitaire');
         $product->setDescription('Diamond Solitaire');
         $product->setPrice(185);
         $product->setColor('Gold');
         $product->setImage('assets/img/d.jpg');
         $manager->persist($product);

        $manager->flush();
    }
}
