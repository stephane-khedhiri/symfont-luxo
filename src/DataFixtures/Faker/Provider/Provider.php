<?php

namespace App\DataFixtures\Faker\Provider;

use App\Entity\User;
use Doctrine\DBAL\Types\ObjectType;
use Faker\Generator;
use Faker\Provider\Image;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class Provider extends Image
{
    /**
     * @var string
     */
    static private $uploadDir;

    /**
     * @var string
     */
    static private $publicDir;

    /**
     * @var UserPasswordEncoderInterface
     */
    static private $encoder;

    /**
     * @var string
     */
    static private $resource;

    /**
     * ImageProvider constructor.
     * @param string $resource
     * @param string $uploadDir
     * @param string $publicDir
     * @param Generator $generator
     * @param UserPasswordEncoderInterface $encoder
     */
    public function __construct(string $resource, string $uploadDir, string $publicDir, Generator $generator, UserPasswordEncoderInterface $encoder)
    {
        self::$uploadDir = $uploadDir;
        self::$publicDir = $publicDir;
        self::$encoder = $encoder;
        self::$resource = $resource;
        parent::__construct($generator);
    }

    static public function imageUpload($type) {

        $sourcePath= rtrim(self::$resource, '/').DIRECTORY_SEPARATOR.$type;
        $images = array_diff(scandir( $sourcePath), array('..','.'));
        $count = rand(2, count($images));

        $filename = $images[$count];
        $filePublicPath = $type.DIRECTORY_SEPARATOR. $filename;
        $sourcePath = $sourcePath.DIRECTORY_SEPARATOR.$filename;
        $filepath = rtrim(self::$publicDir, '/').DIRECTORY_SEPARATOR.$filePublicPath;
       /* $name = md5(uniqid(empty($_SERVER['SERVER_ADDR']) ? '' : $_SERVER['SERVER_ADDR'], true));
        $filename = $name .'.jpg';
        $filePublicPath = $type . DIRECTORY_SEPARATOR .  $filename;
        $filepath = rtrim(self::$uploadDir, '/') . DIRECTORY_SEPARATOR . $filePublicPath;
*/
        if (!file_exists($dir = rtrim(self::$uploadDir, '/') . DIRECTORY_SEPARATOR . $type)) {
            mkdir($dir);
        }

        copy($sourcePath, $filepath);

        return $filePublicPath;
    }

    static public function encodePassword($password)
    {
        $user = new User();
        return self::$encoder->encodePassword($user, $password);
    }
}