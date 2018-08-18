<?php /* SVN: $Id: $ */ ?>
<div class="reportSpams form">
<?php echo $form->create('SpamReport', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $html->link(__l('Spam Reports'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Spam Report');?></legend>
	<?php
		echo $form->input('id');		
		echo $form->input('name', array('label' => __l('Name')));
		echo $form->input('email', array('label' => __l('Email')));
		echo $form->input('message', array('label' => __l('Message')));
	?>
	</fieldset>
<?php echo $form->end(__l('Update'));?>
</div>
