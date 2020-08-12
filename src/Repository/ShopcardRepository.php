<?php

namespace App\Repository;

use App\Entity\Shopcard;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use phpDocumentor\Reflection\Types\Integer;
use PhpParser\Node\Expr\Array_;

/**
 * @method Shopcard|null find($id, $lockMode = null, $lockVersion = null)
 * @method Shopcard|null findOneBy(array $criteria, array $orderBy = null)
 * @method Shopcard[]    findAll()
 * @method Shopcard[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShopcardRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Shopcard::class);
    }

    // /**
    //  * @return Shopcard[] Returns an array of Shopcard objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Shopcard
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getUserShopCardTotal($userid): float
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('
            SELECT SUM(p.price * s.quantity) as total
                FROM App\Entity\Shopcard s, App\Entity\Product p
                WHERE s.productid=p.id and s.userid=:userid
        ')->setParameter('userid',$userid);
        $result=$query->getResult();

        if ($result[0]["total"]!=null){
            return $result[0]["total"];
        }else{
            return 0;
        }
    }

    public function getUserShopCardCount($userid): Integer
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('
            SELECT COUNT(s.id) as shopcount
                FROM App\Entity\Shopcard s
                WHERE s.userid=:userid
        ')->setParameter('userid',$userid);
        $result=$query->getResult();

        if ($result[0]["shopcount"]!=null){
            return $result[0]["shopcount"];
        }else{
            return 0;
        }

    }

    public  function getUserShopCard($userid): array
    {
        $em=$this->getEntityManager();
        $query=$em->createQuery('
            SELECT p.description,p.price,s.quantity,s.productid,s.userid,(p.price * s.quantity) as total
                FROM App\Entity\Shopcard s, App\Entity\Product p
                WHERE s.productid=p.id and s.userid=:userid
        ')->setParameter('userid',$userid);

        return $query->getResult();
    }

}
