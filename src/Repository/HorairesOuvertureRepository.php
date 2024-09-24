<?php

namespace App\Repository;

use App\Entity\HorairesOuverture;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HorairesOuverture>
 *
 * @method HorairesOuverture|null find($id, $lockMode = null, $lockVersion = null)
 * @method HorairesOuverture|null findOneBy(array $criteria, array $orderBy = null)
 * @method HorairesOuverture[]    findAll()
 * @method HorairesOuverture[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HorairesOuvertureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HorairesOuverture::class);
    }

//    /**
//     * @return HorairesOuverture[] Returns an array of HorairesOuverture objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('h.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?HorairesOuverture
//    {
//        return $this->createQueryBuilder('h')
//            ->andWhere('h.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
