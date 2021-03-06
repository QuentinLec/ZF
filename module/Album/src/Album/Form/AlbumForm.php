<?php

namespace Album\Form;

use Zend\Form\Form;

class AlbumForm extends Form
{
	public function __construct($name = null)
	{
		// we want to ignore the name passed
		parent::__construct('album');

		$this->add(array(
				'name' => 'id',
				'type' => 'Hidden',
		));
		$this->add(array(
				'name' => 'title',
				'type' => 'Text',
				'options' => array(
						'label' => translate("Title"),
				),
		));
		$this->add(array(
				'name' => 'artist',
				'type' => 'Text',
				'options' => array(
						'label' => translate("Artist"),
				),
		));
		$this->add(array(
				'name' => 'description',
				'type' => 'Text',
				'options' => array(
						'label' => '',
				),
		));
		$this->add(array(
				'name' => 'submit',
				'type' => 'Submit',
				'attributes' => array(
						'value' => translate("Go"),
						'id' => 'submitbutton',
				),
		));
	}
}