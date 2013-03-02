<?php

namespace ContentManagerBundle\ContentManagerBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryRepository
 */
class CategoryRepository extends EntityRepository
{
	function getCategoriesByLangId($lang){
	    return $this->_em
	    			->createQueryBuilder()
	    			->select('c')
	       			->from('ContentManagerBundle:CMCategory', 'c')
	       			->leftjoin('c.language', 'l')
	       			->where('l.id = :id')
	         		->setParameter('id', $lang)
	    			->getQuery()
	               	->getResult();
	}

	function getCategoriesByLangIso($lang=null){
		if($lang==null){
			return $this->_em
	    			->createQueryBuilder()
	    			->select('c')
	       			->from('ContentManagerBundle:CMCategory', 'c');
		}
	    return $this->_em
	    			->createQueryBuilder()
	    			->select('c')
	       			->from('ContentManagerBundle:CMCategory', 'c')
	       			->leftjoin('c.language', 'l')
	       			->where('l.iso = :iso')
	         		->setParameter('iso', $lang);
	}
}
