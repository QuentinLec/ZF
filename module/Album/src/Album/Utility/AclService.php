<?php
namespace Album\Utility;

use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

class AclService extends Acl{
	
	public function __construct() {
		
		// Ajout des rôles
		$this->addRole(new GenericRole('VISITEUR'))
					->addRole(new GenericRole('FANS'))
					->addRole(new GenericRole('ADMIN'))
					->addRole(new GenericRole('ALL'), array (
							'VISITEUR',
							'FANS',
							'ADMIN'
					));
		
		// Ajout des ressources
		$this->addResource(new GenericResource('Album\Controller\AlbumController::addAction'))
					->addResource(new GenericResource('Album\Controller\AlbumController::editAction'))
					->addResource(new GenericResource('Album\Controller\AlbumController::deleteAction'));

		
		// Refus des ressources à tous
		$this->deny('ALL', array(
				'Album\Controller\AlbumController::addAction',
				'Album\Controller\AlbumController::editAction',
				'Album\Controller\AlbumController::deleteAction'
			)
		);
		
		// Les permissions
		$this->allow('FANS', array(
				'Album\Controller\AlbumController::addAction',
				'Album\Controller\AlbumController::editAction'
			)
		);
		$this->allow('ADMIN', array(
				'Album\Controller\AlbumController::addAction',
				'Album\Controller\AlbumController::editAction',
				'Album\Controller\AlbumController::deleteAction'
			)
		);
	}
}