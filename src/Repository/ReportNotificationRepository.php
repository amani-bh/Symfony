<?php

namespace App\Repository;

use App\Entity\ReportNotification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportNotification[]    findAll()
 * @method ReportNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportNotification::class);
    }

    // /**
    //  * @return ReportNotification[] Returns an array of ReportNotification objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportNotification
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function markAllAsSeen(){
        $qb = $this->createQueryBuilder('n');
            $qb->update('App\Entity\ReportNotification','n')
            ->set('n.seenByAdmin',true)
            ->getQuery()
            ->execute();
    }

    public function markAllAsSeenUser(User $user){
        $qb = $this->createQueryBuilder('n');
        $qb->update('App\Entity\ReportNotification','n')
            ->set('n.seenByUser',true)
            ->where('n.user = :user')
            ->setParameter('user',$user)
            ->getQuery()
            ->execute();
    }
}
