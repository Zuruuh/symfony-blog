<?php

namespace App\Common;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator as FakerGenerator;

abstract class EntityFixture extends Fixture
{
    protected FakerGenerator $faker;

    public function __construct(
        protected EntityManagerInterface $em
    ) {
        $this->faker = FakerFactory::create('fr_FR');
    }

    protected function generate(int $amount, callable $callable, int $batch = 50): void
    {
        if ($batch > $amount) {
            $batch = $amount;
        }

        $flushing = [];
        for ($i=0; $i<$amount; $i++) {
            $entity = $callable($i);
            $this->em->persist($entity);
            $flushing[] = $entity;

            if (($i + 1) % $batch === 0) {
                $this->em->flush();
                foreach ($flushing as $key => $object) {
                    $this->addReference($object::class . "-$key", $object);
                }

                $flushing = [];
            }
        }
    }
}