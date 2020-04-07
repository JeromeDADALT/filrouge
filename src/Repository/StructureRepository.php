<?php

namespace App\Repository;

use App\Entity\Structure;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Structure|null find($id, $lockMode = null, $lockVersion = null)
 * @method Structure|null findOneBy(array $criteria, array $orderBy = null)
 * @method Structure[]    findAll()
 * @method Structure[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StructureRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Structure::class);
    }

    //je crée une méthode pour rechercher un mot dans les champs nom et ville avec une variable $word en paramètre
    public function getByWordInStructure ($word)
    {
        //je fais appel au contructeur de requêtes dans la table a
        $queryBuilder = $this->createQueryBuilder('a');
        //je crée la requête pour qui va récupérer les structures qui ont le terme recherché
        $query = $queryBuilder->select('a')
            ->where('a.nameStructure LIKE :word')
            ->orWhere('a.cityStructure LIKE :word')
            //j'ajoute une ligne pour sécuriser ma requête
            ->setParameter('word', '%'.$word.'%')
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }
    // /**
    //  * @return Structure[] Returns an array of Structure objects
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
    public function findOneBySomeField($value): ?Structure
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
