<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    public const USERS_AMOUNT = 10;
    public const CARTS_AMOUNT = 5;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /** @var Generator */
    private $faker;

    /**
     * AppFixtures constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->faker           = Factory::create();;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $products[] = new Product('Fallout', 199);
        $products[] = new Product('Don\’t Starve', 299);
        $products[] = new Product('Baldur\’s Gate', 399);
        $products[] = new Product('Icewind Dale', 499);
        $products[] = new Product('Bloodborne', 599);
        foreach ($products as $product) {
            $manager->persist($product);
        }

        for ($i=0; $i<self::USERS_AMOUNT; $i++) {
            $user = new User();
            $user->setPassword($this->passwordEncoder->encodePassword($user, "password$i"));
            $user->setEmail("user$i@mail.com");
            $users[] = $user;
            $manager->persist($user);
        }

        for ($i=0; $i<self::CARTS_AMOUNT; $i++) {
            $cart = new Cart($users[$i]);
            $carts[] = $cart;
            $manager->persist($cart);
        }

        for ($i=0; $i<self::CARTS_AMOUNT; $i++) {
            $productsCopy = $products;
            for ($j=0; $j<$this->faker->biasedNumberBetween(1,3); $j++) {
                /** @var Product $product */
                $product = $this->faker->randomElement($productsCopy);
                $carts[$i]->addProduct($product);
                unset($productsCopy[array_search($product->getTitle(), $productsCopy)]);
            }
            $manager->persist($carts[$i]);
        }
        $manager->flush();
    }
}
