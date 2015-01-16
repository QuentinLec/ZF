<?php

 $title = 'Modify Description';
 $this->headTitle($title);
 ?>
 <h1><?php echo $this->escapeHtml($title); ?></h1>

 <?php
 $form = $this->form;
 $form->setAttribute('action', $this->url(
     'album',
     array(
         'action' => 'descriptionmodify',
         'id'     => $this->id,
     )
 ));
 $form->prepare();

 echo $this->form()->openTag($form);
 echo $this->formHidden($form->get('id'));
 echo $this->formHidden($form->get('title'));
 echo $this->formHidden($form->get('artist'));
 echo $this->formRow($form->get('description'))." ";
 echo $this->formSubmit($form->get('submit'));
 echo $this->form()->closeTag();