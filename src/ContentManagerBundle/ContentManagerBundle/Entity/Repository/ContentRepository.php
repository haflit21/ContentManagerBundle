<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ContentRepository
 */
class ContentRepository extends EntityRepository
{
	function getContentByLangId($lang){
	    return $this->_em
	    			->createQueryBuilder()
	    			->select('c')
	       			->from('ContentManagerBundle:CMContent', 'c')
	       			->leftjoin('c.language', 'l')
	       			->where('l.id = :id')
	         		->setParameter('id', $lang)
	    			->getQuery()
	               	->getResult();
	}
}
