<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

class StationsRepository extends EntityRepository
{
    public function getAll()
    {
        return $this->getAllStationsPlaceQb()->getQuery()
            ->getResult();
    }

    public function getAllStationsPlaceQb(){
        $qb = $this->createQueryBuilder('s')
            ->select('s')
            ->addSelect('p')
            ->leftJoin('s.pceSeqno','p')
            ->addOrderBy('s.areaType', 'ASC')
            ->addOrderBy('s.description', 'ASC')
            ->addOrderBy('p.name', 'ASC');
        return $qb;
    }

    public function getAllStationsTypes()
    {
        $qb = $this->createQueryBuilder('s')
            ->select('s.areaType')
            ->distinct()
            ->addOrderBy('s.areaType', 'ASC');

        return $qb->getQuery()
            ->getResult();
    }
}
