<?php /* SVN: $Id: $ */ ?>
<div class="users form">
	<h2><?php echo $html->link(__l('Users'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit User');?></h2>
<?php echo $form->create('User', array('class' => 'normal'));?>
	<fieldset>
 	
	<?php
		echo $form->input('id');
		echo $form->input('fullname', array('label' => __l('Full name')));
		echo $form->input('email', array('label' => __l('Email')));
		echo $form->input('country_id', array('label' => __l('Country')));
	?>
	</fieldset>
<?php echo $form->end(__l('Update'));?>
</div>
