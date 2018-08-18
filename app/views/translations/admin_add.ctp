<?php /* SVN: $Id: admin_add.ctp 17696 2010-08-05 12:37:07Z boopathi_026ac09 $ */ ?>
<div class="translations form">
<h2><?php echo __l('Add New Translation');?></h2>
<?php echo $form->create('Translation', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $html->link(__l('Translations'), array('action' => 'index'));?> &raquo; <?php echo __l('Add New Translation');?></legend>
	<?php
		echo $form->input('from_language', array('value' => __l('English'), 'disabled' => true));
		echo $form->input('language_id', array('label' => __l('To Language'))); ?>
		<div class="submit-block clearfix">
		<?php
		echo $form->submit(__l('Manual Translate'), array('name' => 'data[Translation][manualTranslate]'));
	   ?>
	   </div>
    <div class="notice">
	<p><?php echo __l('Manual Translate: It will only populate site labels for selected new language. You need to manually enter all the equivalent translated label');?>
</div>
	<div class="submit-block clearfix">
		<?php
		echo $form->submit(__l('Google Translate'), array('name' => 'data[Translation][googleTranslate]'));
	?>
	</div>
    <div class="notice">
	<p><?php echo __l('Google Translate: It will automatically translate site labels into selected language with Google');?>
</div>
	</fieldset>
<?php echo $form->end();?>
</div>
