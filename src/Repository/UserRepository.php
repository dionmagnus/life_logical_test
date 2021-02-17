<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getItemsCount() {
        $dql = "select count(u.id) as cnt from App\Entity\User u";
        
        $query = $this->getEntityManager()->createQuery($dql);
        
        $res = $query->getResult();
        return $res[0]['cnt'];
    }
}
