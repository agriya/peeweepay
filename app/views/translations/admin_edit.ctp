<?php /* SVN: $Id: admin_edit.ctp 5567 2010-05-25 14:50:11Z senthilkumar_017ac09 $ */ ?>
<div class="translations form">
<?php echo $form->create('Translation', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $html->link(__l('Translations'), array('action' => 'index'),array('title' => __l('Translations')));?> &raquo; <?php echo __l('Edit Translation');?></legend>
	<?php
		echo $form->input('id');
		echo $form->input('language_id', array('label' => __l('Language')));
		echo $form->input('key', array('label' => __l('Key')));
		echo $form->input('lang_text', array('label' => __l('Language Text')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $form->submit(__l('Update'));?>
</div>
<?php echo $form->end();?>
</div>
