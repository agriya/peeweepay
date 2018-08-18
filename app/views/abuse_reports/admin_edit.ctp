<?php /* SVN: $Id: $ */ ?>
<div class="reportAbuses form">
<?php echo $form->create('AbuseReport', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $html->link(__l('Abus Reports'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Abuse Report');?></legend>
	<?php
		echo $form->input('id', array('type'=>'hidden'));
		echo $form->input('name', array('label' => __l('Name')));
		echo $form->input('email', array('label' => __l('Email')));
		echo $form->input('message', array('label' => __l('Message')));
	?>
	</fieldset>
<?php echo $form->end(__l('Update'));?>
</div>
