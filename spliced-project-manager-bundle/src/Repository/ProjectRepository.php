<?php

namespace Spliced\Bundle\ProjectManagerBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * ProjectRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProjectRepository extends EntityRepository
{
	
	public function getListQuery(){
		$query = $this->getEntityManager()->createQueryBuilder()
		  ->select('p')
		  ->from('SplicedProjectManagerBundle:Project','p');
		 
		return $query;
	}
	
	/**
	 * load
	 * Load a Project with all relational data
	 * @param int $id
	 * @return Project
	 * @throws NoResultException
	 */
	public function load($id){
		return $this->getEntityManager()
		  ->createQuery('
		  	SELECT 
		  		project, client, invoices, invoiceLineItems, projectTags,
		  		tag, projectStaff, staff, media,
		  		files, notes
		  	FROM SplicedProjectManagerBundle:Project project
		  	LEFT JOIN project.client client
		  	LEFT JOIN project.invoices invoices
		  	LEFT JOIN invoices.lineItems invoiceLineItems
		  	LEFT JOIN project.tags projectTags
		  	LEFT JOIN projectTags.tag tag
		  	LEFT JOIN project.staff projectStaff
		  	LEFT JOIN projectStaff.staff staff
		  	LEFT JOIN project.media media
		  	LEFT JOIN project.files files
		  	LEFT JOIN project.notes notes
		  	WHERE project.id = :id')
		  ->setParameter('id',$id)
		  ->getSingleResult();
	}
	
	/**
	 * 
	 */
	public function findOneByIdWithService($project,$service){
		return $this->getEntityManager()
		  ->createQuery('
		  	SELECT p, s FROM SplicedProjectManagerBundle:Project p 
		  		INNER JOIN p.services s
		  	WHERE p.id = :id AND s.id = :serviceId')
		  ->setParameter('id',$project)
		  ->setParameter('serviceId',$service)
		  ->getSingleResult();
	}
	
	/**
	 * 
	 */
	public function findOneByIdWithTag($project,$tag){
		return $this->getEntityManager()
		  ->createQuery('
		  	SELECT p, t FROM SplicedProjectManagerBundle:Project p 
		  		INNER JOIN p.tags t
		  	WHERE p.id = :id AND t.id = :tagId')
		  ->setParameter('id',$project)
		  ->setParameter('tagId',$tag)
		  ->getSingleResult();
	}
	
	/**
	 * 
	 */
	public function findOneByIdWithMedia($project,$media){
		return $this->getEntityManager()
		->createQuery('
				SELECT p, m FROM SplicedProjectManagerBundle:Project p
				INNER JOIN p.media m
				WHERE p.id = :id AND m.id = :mediaId')
		->setParameter('id',$project)
		->setParameter('mediaId',$media)
		->getSingleResult();
	}
	
	
	/**
	 *
	 */
	public function findOneByIdWithStaff($project,$staff){
		return $this->getEntityManager()
		->createQuery('
				SELECT p, s, s2 FROM SplicedProjectManagerBundle:Project p
				INNER JOIN p.staff s
				INNER JOIN s.staff s2
				WHERE p.id = :id AND s.id = :staffId')
		->setParameter('id',$project)
		->setParameter('staffId',$staff)
		->getSingleResult();
	}
	
	/**
	 *
	 */
	public function findOneByIdWithFile($project,$file){
		return $this->getEntityManager()
		->createQuery('
				SELECT p, f FROM SplicedProjectManagerBundle:Project p
				INNER JOIN p.files f 
				WHERE p.id = :id AND f.id = :fileId')
		->setParameter('id',$project)
		->setParameter('fileId',$file)
		->getSingleResult();
	}
	
	/**
	 *
	 */
	public function findOneByIdWithQuote($project,$quote){
		return $this->getEntityManager()
		->createQuery('
			SELECT p, q FROM SplicedProjectManagerBundle:Project p
			INNER JOIN p.quotes q
			WHERE p.id = :id AND q.id = :quoteId')
			->setParameter('id',$project)
			->setParameter('quoteId',$quote)
			->getSingleResult();
	}
	
	/**
	 *
	 */
	public function findOneByIdWithMessage($project,$message){
		return $this->getEntityManager()
		->createQuery('
			SELECT p, m FROM SplicedProjectManagerBundle:Project p
			INNER JOIN p.messages m
			WHERE p.id = :id AND m.id = :messageId')
			->setParameter('id',$project)
			->setParameter('messageId',$message)
			->getSingleResult();
	}
	
	/**
	 *
	 */
	public function findOneByIdWithAttributes($project){
		return $this->getEntityManager()
		->createQuery('
				SELECT p, a FROM SplicedProjectManagerBundle:Project p
				INNER JOIN p.attributes a
				WHERE p.id = :id')
			->setParameter('id',$project)
			->getSingleResult();
	}
	
}
