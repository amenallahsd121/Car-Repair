<?php

namespace App\Repository;

use App\Entity\FormulaireContact;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FormulaireContact>
 *
 * @method FormulaireContact|null find($id, $lockMode = null, $lockVersion = null)
 * @method FormulaireContact|null findOneBy(array $criteria, array $orderBy = null)
 * @method FormulaireContact[]    findAll()
 * @method FormulaireContact[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FormulaireContactRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FormulaireContact::class);
    }

//    /**
//     * @return FormulaireContact[] Returns an array of FormulaireContact objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FormulaireContact
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
