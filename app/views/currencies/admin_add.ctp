<?php /* SVN: $Id: $ */ ?>
<div class="currencies form">
<?php echo $form->create('Currency', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $html->link(__l('Currencies'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Currency');?></legend>
	<?php
		echo $form->input('name', array('label' => __l('Name')));
		echo $form->input('code', array('label' => __l('Code')));
		echo $form->input('symbol', array('label' => __l('Symbol')));
		echo $form->input('prefix', array('label' => __l('Prefix')));
		echo $form->input('suffix', array('label' => __l('Suffix')));
		echo $form->input('decimals', array('label' => __l('Decimals')));
		echo $form->input('dec_point', array('label' => __l('Dec Point')));
		echo $form->input('thousands_sep', array('label' => __l('Thousands Sep')));
		echo $form->input('locale', array('label' => __l('Local')));
		echo $form->input('format_string', array('label' => __l('Format String')));
		echo $form->input('grouping_algorithm_callback', array('label' => __l('Grouping Algorithm Callback')));
		echo $form->input('is_use_graphic_symbol',array('label' =>__l('User graphic symbol?')));
		echo $form->input('is_enabled',array('label' =>__l('Enabled?')));
	?>
	</fieldset>
<?php echo $form->end(__l('Add'));?>
</div>
