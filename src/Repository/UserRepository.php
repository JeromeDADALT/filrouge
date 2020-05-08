<?php

namespace App\Repository;

use App\Data\SearchData;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    //je crée une méthode pour rechercher un mot dans les champs prénom et email avec une variable $word en paramètre
    public function getByWordInUser ($word)
    {
        //je fais appel au contructeur de requêtes dans la table a
        $queryBuilder = $this->createQueryBuilder('a');
        //je crée la requête pour qui va récupérer les utilisateurs qui ont le terme recherché
        $query = $queryBuilder->select('a')
            ->where('a.firstName LIKE :word')
            ->orWhere('a.email LIKE :word')
            //j'ajoute une ligne pour sécuriser ma requête
            ->setParameter('word', '%'.$word.'%')
            ->orderBy('a.firstName', 'ASC')
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }

    //je crée une méthode pour rechercher les joueurs en fonction des critères de niveau, jour, heure et lieu
    public function searchByFilterInUser (SearchData $search)
    {
        $queryBuilder = $this->createQueryBuilder('u');
        $query = $queryBuilder
            //je sélectionne parmi les entités level, day, hour, structure et users
            ->select('l', 'd', 'h', 's', 'u')
            //en faisant un inner join entre users et level
            ->join('u.level', 'l')
            //je filtre par les niveaux en récupérant les id des niveaux cochés
            ->orWhere('l.id IN (:level)')
            ->setParameter('level', $search->level)
            ->join('u.day', 'd')
            //je filtre par les jours en récupérant les id des jours cochés
            ->orWhere('d.id IN (:day)')
            ->setParameter('day', $search->day)
            ->join('u.hour', 'h')
            //je filtre par les heures en récupérant les id des heures cochées
            ->orWhere('h.id IN (:hour)')
            ->setParameter('hour', $search->hour)
            ->join('u.structure', 's')
            //je filtre par les lieux en récupérant les id des lieux cochés
            ->orWhere('s.id IN (:structure)')
            ->setParameter('structure', $search->structure)
            //j'affiche les résultats par ordre alphabétique des joueurs
            ->orderBy('u.firstName', 'ASC')
            ->getQuery();
        $results = $query->getResult();

        return $results;
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
