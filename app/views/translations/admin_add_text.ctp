<?php /* SVN: $Id: admin_add_text.ctp 749 2010-09-09 09:32:25Z boopathi_026ac09 $ */ ?>
<div class="translations form">
	<h2><?php echo __l('Add New Language Variable');?></h2>
	<?php echo $form->create('Translation', array('class' => 'normal', 'action' => 'add_text'));
		echo $form->input('Translation.'.$lang_id.'.key');
		foreach ($languages as $lang_id => $lang_name) :
	?>
	<h3 class="language-name"><?php echo $lang_name;?></h3>
	<?php	
		echo $form->input('Translation.'.$lang_id.'.lang_text');
		endforeach; ?>
<div class="clearfix submit-block">
<?php
		echo $form->submit(__l('Add'));
	?>
</div>
<?php
		echo $form->end();
	?>
</div>
