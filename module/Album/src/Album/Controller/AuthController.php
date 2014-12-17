<?php

namespace Album\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Element;
use Zend\Form\Form;
use Zend\InputFilter\Input;
use Zend\InputFilter\InputFilter;

class AuthController extends AbstractActionController {
	
	public function indexAction() {
		$form = $this->createForm();
		
		$request = $this->getRequest();
		if ( $request->isPost() ) {
			$data = $request->getPost();
			$form->setData($data);
			
			if ($form->isValid()) {
				// Code correspondant à l'authentification
				
				if ( (strcmp( $form->get('login')->getValue(), 'quentin@insset.fr') == 0) &&
							(strcmp( $form->get('password')->getValue(), 'blob') == 0) ) {
					return $this->redirect()->toRoute('album');
				} else { // Mauvais log/pass
					$this->flashMessenger()->addErrorMessage('Erreur d\'identification');
					return array('formAuth'=>$form);
				}
			} else { // Mauvaise saisie
				return array('formAuth'=>$form);
			}
		} else { // Affichage du formulaire
			return array('formAuth'=>$form);
		}
	}
	
	/**
	 * Construction en mode programmatique
	 * @return \Album\Controller\Form
	 */
	protected function createForm() {
		
		// LOGIN
		$login = new Element('login');
		$login->setLabel("Votre identifiant : ");
		$login->setAttributes( array(
				'type' => 'text',
				'placeholder' => "Votre login"
			)
		);
		
		// PASSWORD
		$password = new Element('password');
		$password->setLabel("Votre mot de passe : ");
		$password->setAttributes( array(
				'type' => 'text',
				'placeholder' => "Votre password"
			)
		);
		$password->setAttribute('type', 'Password');
		
		$form = new Form('identification');
		$form->add($login);
		$form->add($password);
		
// 		$inputLogin = new Input('login');
// 		$inputLogin->setRequired(true);
// 		$inputLogin->getValidatorChain()->attachByName('alpha');
// 		$inputLogin->getFilterChain()->attachByName('alpha');

		$inputFilter = new InputFilter();
		
		// LOGIN
		$inputFilter->add( array(
				'name' => 'login',
				'required' => true,
				'validators' => array( array(
						'name' => 'emailaddress')
				),
// 				'filters' => array(
// 						array('name' => 'digits')					
// 				)
			)
		);
		
		// PASSWORD
		$inputFilter->add( array(
				'name' => 'password',
				'required' => true,
				'validators' => array( array(
						'name' => 'alnum')
				),
			)
		);
		
		
		$form->setInputFilter($inputFilter);
		
		return $form;
	}
}