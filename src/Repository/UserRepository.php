<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use function Doctrine\ORM\QueryBuilder;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry $registry
     * @param EncoderFactoryInterface $encoderFactory
     */
    public function __construct(ManagerRegistry $registry, EncoderFactoryInterface $encoderFactory)
    {
        $this->encoderFactory = $encoderFactory;
        parent::__construct($registry, User::class);
    }


    public function register($getData)
    {
        $this->_em->persist($getData);
        $this->_em->flush($getData);
    }

    public function edit($user)
    {
        $uow = $this->_em->getUnitOfWork();
        $uow->computeChangeSets();
        $changeSet = $uow->getEntityChangeSet($user);

        if (isset($changeSet['password']) && strlen($changeSet['password'][1]) > 0) {
            $user->setPassword($this->encoderFactory->getEncoder($user)->encodePassword($changeSet['password'][1], null));
            $uow->recomputeSingleEntityChangeSet(
                $this->_em->getClassMetadata(User::class),
                $user
            );
        };
            $this->_em->persist($user);
            $this->_em->flush();

        return $user;
    }

    public function delete(User $user)
    {
        $this->_em->remove($user);
        $this->_em->flush();
    }

}
