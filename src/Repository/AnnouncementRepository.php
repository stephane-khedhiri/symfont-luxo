<?php

namespace App\Repository;

use App\Entity\Announcement;
use App\Entity\Image;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Announcement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Announcement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Announcement[]    findAll()
 * @method Announcement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnnouncementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Announcement::class);
    }

    /**
     * @param string $category
     * @return Announcement[] Returns an array of Announcement objects
     */
    public function findByCategoryWithImage(string $category)
    {
        $qb = $this->createQueryBuilder('announcement');

        return
            $qb
                ->setMaxResults(16)
                ->leftJoin('announcement.images', 'images')
                ->addSelect('images')
                ->orderBy('announcement.date', 'ASC')
                ->andWhere($qb->expr()->eq('announcement.category', $category))
                ->getQuery()
                ->getResult();
    }

    /**
     * @param User|null $user
     * @return int|mixed|string
     */
    public function findByUser(User $user)
    {
        if ($user instanceof User) {
            $user = $user->getId();
        }

        if (!is_int($user)) {
            throw new \InvalidArgumentException('');
        }

        $qb = $this->createQueryBuilder('a');

        return $qb
            ->addSelect('u')
            ->where($qb->expr()->eq('a.postedBy', $user))
            ->leftJoin('a.postedBy', 'u')
            ->getQuery()
            ->getResult();

    }

    public function persist($announcement)
    {
        $this->_em->persist($announcement);
        $this->_em->flush($announcement);
    }

    public function delete(Announcement $announcement)
    {

        $this->_em->remove($announcement);
        $this->_em->flush();
    }

    /**
     * @param $id
     * @return Announcement
     */
    public function findByAnnouncementWithImage($id)
    {
        $qb = $this->createQueryBuilder('announcement');
        return
            $qb
                ->leftJoin('announcement.images', 'images')
                ->addSelect('images')
                ->andWhere($qb->expr()->eq('announcement.id', $id))
                ->getQuery()
                ->getOneOrNullResult()
            ;

    }


}
