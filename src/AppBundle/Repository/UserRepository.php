<?php
/*********************************************************************************
 * Developed by Maxx Ng'ang'a
 * (C) 2017 Crysoft Dynamics Ltd
 * Karbon V 2.1
 * User: Maxx
 * Date: 7/3/2017
 * Time: 12:40 AM
 ********************************************************************************/

namespace AppBundle\Repository;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    /**
     * @return User[]
     */
    public function findPendingUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.roles = :isUser')
            ->setParameter(':isUser','["ROLE_USER"]')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',false)
            ->andWhere('user.isRestored = :isRestored')
            ->setParameter(':isRestored',false)
            ->getQuery()
            ->execute();
    }
    /**
     * @return User[]
     */
    public function findDeactivatedUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.roles = :isUser')
            ->setParameter(':isUser','["ROLE_USER"]')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',false)
            ->andWhere('user.isRestored = :isRestored')
            ->setParameter(':isRestored',false)
            ->getQuery()
            ->execute();
    }
    /**
     * @return User[]
     */
    public function findActiveUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.roles = :isUser')
            ->setParameter(':isUser','["ROLE_USER"]')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',true)
            ->getQuery()
            ->execute();
    }
    /**
     * @return User[]
     */
    public function findAdministratorUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.roles = :isUser')
            ->setParameter(':isUser','["ROLE_ADMIN"]')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',true)
            ->getQuery()
            ->execute();
    }
    /**
     * @return User[]
     */
    public function findRestoredUsers(){

        return $this->createQueryBuilder('user')
            ->andWhere('user.roles = :isUser')
            ->setParameter(':isUser','["ROLE_USER"]')
            ->andWhere('user.isActive = :isActive')
            ->setParameter(':isActive',true)
            ->andWhere('user.isRestored = :isRestored')
            ->setParameter(':isRestored',true)
            ->getQuery()
            ->execute();
    }
}