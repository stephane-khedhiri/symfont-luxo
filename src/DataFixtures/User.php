<?php

namespace App\DataFixtures;

use App\Entity\Announcement;
use App\Entity\Image;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class User extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $loader = new \Nelmio\Alice\Loader\NativeLoader();
        $objectSet = $loader->loadData([
            \App\Entity\User::class => [
                'user_{1..5}' => [
                    'firstName' => '<username()>',
                    'lastName' => '<username()>',
                    'birth' => "<dateTimeBetween('-30 years', '-18 years')>",
                    'email' => '<email()>',
                    'password' => 'test',
                ],
            ],
            Image::class => [
                'image_{1..2}' => [
                    'name' => '<username()>',
                    'path' => '<imageUrl(640, 480)>',
                ],
            ],
            Announcement::class => [
                'announcement_{1..20}' => [
                    'title' => '<username()>',
                    'description' => '<text()>',
                    'city' => '<city()>',
                    'zipCode' => '<numberBetween(00000, 99999)>',
                    'type' => '<numberBetween(0, 3)>',
                    'category' => '<numberBetween(0, 1)>',
                    'price' => '<numberBetween(300, 250000)>',
                    'area' => '<numberBetween(10, 350)>',
                    'room' => '<numberBetween(1, 5)>',
                    'images' => ['@image_1', '@image_2'],
                    'energy' => '<numberBetween(0, 1)>',
                    'floor' => '<numberBetween(1, 3)>',
                    'bedroom' => '<numberBetween(1, 3)>',
                    'postedBy' => '@user_<numberBetween(1, 5)>',
                ],
            ],
        ]);

        foreach ($objectSet->getObjects() as $item) {
            $metadata = $manager->getClassMetadata(get_class($item));
            if (false === $metadata->isMappedSuperclass && false === (isset($metadata->isEmbeddedClass) && $metadata->isEmbeddedClass)) {
                if ($item instanceof \App\Entity\User ) {
                    dump($item);
                    $item->setPassword($this->encoder->encodePassword($item,$item->getPassword()));
                }
                $manager->persist($item);
            }
        }
        // $product = new Product();
        // $manager->persist($product);

        $manager->flush();

        $manager->flush();
    }
}
