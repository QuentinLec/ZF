<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Album\Model\Album;
use Album\Form\AlbumForm;
use Zend\Authentication\AuthenticationService;
use Zend\Authentication\Storage\Session;

class AlbumController extends AbstractActionController
{
	protected $albumTable;
	
	private $translator = null;
	
	public function translate($str = '')
	{
		if (!$this->translator)
		{
			$this->translator = $this->getServiceLocator()->get('translator');
		}
		return $this->translator->translate($str);
	}
	
	public function indexAction()
	{
		return new ViewModel(array(
				'albums' => $this->getAlbumTable()->fetchAll(),
		));
	}

	public function addAction()
  {  	 	
  	// Code correspondant à l'authentification
  	$serviceManager = $this->getServiceLocator();
  	$auth = $serviceManager->get('AlbumAuth');
  	
  	if ($auth->hasIdentity()) {
  		$identity = $auth->getIdentity();
  		$role = $identity['role'];
  	} else {
  		return $this->redirect()->toRoute('auth', array(
  				'action' => 'index'
  		));
  	}
  	
  	$acl = $serviceManager->get('AclService');
  	$ressource = __METHOD__;
  	if (!$acl->isAllowed($role, $ressource)) {
  		return $this->redirect()->toRoute('album', array(
  				'action' => 'index'
  		));
  	}
  		
  		$form = new AlbumForm();
  		$form->get('submit')->setValue('Add');
  		
  		$request = $this->getRequest();
  		if ($request->isPost()) {
  			$album = new Album();
  			$form->setInputFilter($album->getInputFilter());
  			$form->setData($request->getPost());
  		
  			if ($form->isValid()) {
  				$album->exchangeArray($form->getData());
  				$this->getAlbumTable()->saveAlbum($album);
  		
  				// Redirect to list of albums
  				return $this->redirect()->toRoute('album');
  			}
  		}
  		
  		return array('form' => $form);
  }

	public function editAction()
  {
  	// Code correspondant à l'authentification
  	$serviceManager = $this->getServiceLocator();
  	$auth = $serviceManager->get('AlbumAuth');
  	
  	if ($auth->hasIdentity()) {
  		$identity = $auth->getIdentity();
  		$role = $identity['role'];
  	} else {
  		return $this->redirect()->toRoute('auth', array(
  				'action' => 'index'
  		));
  	}
  	
  	$acl = $serviceManager->get('AclService');
  	$ressource = __METHOD__;
  	if (!$acl->isAllowed($role, $ressource)) {
  		return $this->redirect()->toRoute('album', array(
  				'action' => 'index'
  		));
  	}
  		
  		$title = (String) $this->params()->fromRoute('title', 0);
	     
	    $title = str_replace ( '_', ' ', $title);
	     
	    if (!$title) {
	    	return $this->redirect()->toRoute('album');
	    }
	
	    // Get the Album with the specified id.  An exception is thrown
	    // if it cannot be found, in which case go to the index page.
	    try {
	      $album = $this->getAlbumTable()->getAlbumTitle($title);
	    }
	    catch (\Exception $ex) {
	      return $this->redirect()->toRoute('album', array(
	      'action' => 'index'
	      ));
	    }
	
	    $form  = new AlbumForm();
	    $form->bind($album);
	    $form->get('submit')->setAttribute('value', 'Edit');
	
	    $request = $this->getRequest();
	    if ($request->isPost()) {
	      $form->setInputFilter($album->getInputFilter());
	      $form->setData($request->getPost());
	
	   		if ($form->isValid()) {
	        $this->getAlbumTable()->saveAlbum($album);
	
	        // Redirect to list of albums
	        return $this->redirect()->toRoute('album');
	      }
	    }
	
	    return array(
	    	'title' => str_replace ( ' ', '_', $title),
	      'form' => $form,
	     	);
  }

	public function deleteAction()
  {
  	// Code correspondant à l'authentification
  	$serviceManager = $this->getServiceLocator();
  	$auth = $serviceManager->get('AlbumAuth');
  	
  	if ($auth->hasIdentity()) {
  		$identity = $auth->getIdentity();
  		$role = $identity['role'];
  	} else {
  		return $this->redirect()->toRoute('auth', array(
  				'action' => 'index'
  		));
  	}
  	
  	$acl = $serviceManager->get('AclService');
  	$ressource = __METHOD__;
  	if (!$acl->isAllowed($role, $ressource)) {
  		return $this->redirect()->toRoute('album', array(
  				'action' => 'index'
  		));
  	}
  		
  	$title = (String) $this->params()->fromRoute('title', 0);
  	
  	$title = str_replace ( '_', ' ', $title);
  	
  	if (!$title) {
  		return $this->redirect()->toRoute('album');
  	}
	
	    $request = $this->getRequest();
	    if ($request->isPost()) {
	      $del = $request->getPost('del', 'No');
	
	    if ($del == 'Yes') {
	      $title = (String) $request->getPost('title');
	      $this->getAlbumTable()->deleteAlbumTitle($title);
	    }
	
	    // Redirect to list of albums
	      return $this->redirect()->toRoute('album');
	    }
	
	    return array(
	      'title'    => $title,
	      'album' => $this->getAlbumTable()->getAlbumTitle($title)
	    );
  }
  
  public function infosAction () {
  	$title = (String) $this->params()->fromRoute('title', 0);
  	
  	$title = str_replace ( '_', ' ', $title);
  	
  	if (!$title) {
  		return $this->redirect()->toRoute('album');
  	}
  	
  	// Get the Album with the specified id.  An exception is thrown
  	// if it cannot be found, in which case go to the index page.
  	try {
  		$album = $this->getAlbumTable()->getAlbumTitle($title);
  	}
  	catch (\Exception $ex) {
  		return $this->redirect()->toRoute('album', array(
  				'action' => 'index'
  		));
  	}

  	return array(
  			'title'    => $title,
  			'album' => $album
  	);
  }
	
  public function noPrivilegeAction() {
  	return array ();
  }
  
  public function descriptionAction()
  {
  	// Code correspondant à l'authentification
  	$serviceManager = $this->getServiceLocator();
  	$auth = $serviceManager->get('AlbumAuth');
  	 
  	if ($auth->hasIdentity()) {
  		$identity = $auth->getIdentity();
  		$role = $identity['role'];
  	} else {
  		return $this->redirect()->toRoute('auth', array(
  				'action' => 'index'
  		));
  	}
  	 
  	$acl = $serviceManager->get('AclService');
  	$ressource = __METHOD__;
  	if (!$acl->isAllowed($role, $ressource)) {
  		return $this->redirect()->toRoute('album', array(
  				'action' => 'index'
  		));
  	}
  
  	$title = (String) $this->params()->fromRoute('title', 0);
  	
  	$title = str_replace ( '_', ' ', $title);
  	
  	if (!$title) {
  		return $this->redirect()->toRoute('album');
  	}
  
  	// Get the Album with the specified id.  An exception is thrown
  	// if it cannot be found, in which case go to the index page.
  	try {
  		$album = $this->getAlbumTable()->getAlbumTitle($title);
  	}
  	catch (\Exception $ex) {
  		return $this->redirect()->toRoute('album', array(
  				'action' => 'index'
  		));
  	}
  
  	$form  = new AlbumForm();
  	$form->bind($album);
  	$form->get('submit')->setAttribute('value', 'Add');
  
  	$request = $this->getRequest();
  	if ($request->isPost()) {
  		$form->setInputFilter($album->getInputFilter());
  		$form->setData($request->getPost());
  
  		if ($form->isValid()) {
  			$this->getAlbumTable()->saveAlbum($album);
  
  			$endTitle = str_replace ( ' ', '_', $title);
  			
  			// Redirect to list of albums
  			return $this->redirect()->toRoute('album', array(
  				'action' => 'infos',
  				'title' => $endTitle
  			));
  		}
  	}
  
  	return array(
  			'title' => str_replace ( ' ', '_', $title),
  			'form' => $form,
  	);
  }
  
	public function getAlbumTable()
	{
		if (!$this->albumTable) {
			$sm = $this->getServiceLocator();
			$this->albumTable = $sm->get('Album\Model\AlbumTable');
		}
		return $this->albumTable;
	}
}