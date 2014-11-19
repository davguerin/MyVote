<?php

namespace DG\VoteBundle\Entity;

use Doctrine\ORM\EntityRepository;

class VoteRepository extends EntityRepository
{
    public function count($id_sheet = null)
    {
        $query = $this->createQueryBuilder('v')
            ->select('COUNT(v.vote)');
        if($id_sheet !== null)
        {
            $query->where('v.sheet = :sheetid')
                    ->setParameter('sheetid', $id_sheet);
        }

        return $query->getQuery()
            ->getSingleScalarResult();
    }
    
    public function average($id_sheet = null)
    {
        $query = $this->createQueryBuilder('v')
            ->select('AVG(v.vote)');
        if($id_sheet !== null)
        {
            $query->where('v.sheet = :sheetid')
                    ->setParameter('sheetid', $id_sheet);
        }

        return $query->getQuery()
            ->getSingleScalarResult();
    }
}
