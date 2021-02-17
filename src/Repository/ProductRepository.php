<?php
namespace App\Repository;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;


class ProductRepository extends ServiceEntityRepository {
    
    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Product::class);
    }
    

    public function findByTitle($title, $sort, $sortBy, $page = 0) {
        $db = $this->createQueryBuilder('p')
            ->where("p.title like :title")
            ->setParameter('title', $title)
            ->orderBy('p.'.$sort, $sortBy)
            ->setFirstResult($page * 10)
            ->setMaxResults(10);
        
        $query = $db->getQuery();
        return $query->execute();
    }

    public function getItemsCount($title) {
        $dql = "select count(p.title) as cnt from App\Entity\Product p";
        if ($title)
            $dql .= " where p.title like '$title'";
        
        $query = $this->getEntityManager()->createQuery($dql);
        
        $res = $query->getResult();
        return $res[0]['cnt'];
    }
}
