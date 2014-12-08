<?php
/**
 * @author David Guerin
 */
namespace DG\VoteBundle\Entity;

use FOS\ElasticaBundle\Repository;

class SheetRepository extends Repository
{
    /**
     * 
     * @param string $filter
     * @param array $order
     */
    public function findOrdered($filter, $order)
    {
        if(!empty($filter))
        {
            $query = new \Elastica\Query\MultiMatch();
            $query->setFields(array('name', 'description'));
            $query->setQuery($filter);
            $finalQuery = new \Elastica\Query($query);
        }
        else
            $finalQuery = new \Elastica\Query();
        
        $finalQuery->setSort($order);
        
        return $this->find($finalQuery);
    }
    
    /**
     * 
     * @param string $name The of the ids to filter
     * @param array $ids The ids
     * @param bool $positive_search If the ids must be on the search or not.
     */
    public function findByIds($name, $ids, $positive_search = true)
    {
        $query = new \Elastica\Query\Filtered();
        $filter = new \Elastica\Filter\Ids();
        
        $filter->setIds($ids);
        if($positive_search == false)
        {
            $not_filter = new \Elastica\Filter\BoolNot($filter);
//            $not_filter->setFilter($filter);
            $query->setFilter($not_filter);
        }
        else
            $query->setFilter($filter);

        return $this->find($query);
    }
}
