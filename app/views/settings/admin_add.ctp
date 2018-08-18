<?php
	echo $form->create('Setting', array('class' => 'normal'));
	echo $form->input('name');
	echo $form->input('value');
	echo $form->input('description');
	echo $form->input('type', array('type' => 'select', 'options' => array('text' => 'text', 'textarea' => 'textarea', 'checkbox' => 'checkbox', 'radio' => 'radio', 'password' => 'password')));
	echo $form->input('label');
	echo $form->end('Add');
	echo $html->link(__l('Cancel'), array('controller' => 'settings', 'action' => 'index'),array('title' => __l('Cancel')));
?>