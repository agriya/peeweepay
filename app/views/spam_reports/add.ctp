<?php /* SVN: $Id: $ */ ?>
<div class="reportSpams form js-responses colorbox-content">
<h2><?php echo __l('Spam Report'); ?></h2>
<?php echo $form->create('SpamReport', array('class' => 'normal absuse-form js-ajax-form'));?>
	<fieldset>
	<p class="report-content"><?php echo sprintf(__l('If you have found spam on this product page please report this to').' %s '.__l('using this form.'). ' ',Configure::read('site.name'));?></p>
	<?php
		echo $form->input('product_id', array('type'=>'hidden')); ?>
		<div class="clearfix">
		<?php echo $form->input('name'); ?>
		<?php echo $form->input('email'); ?>
	<?php echo $form->input('message'); ?>
		</div>
	    <?php
        	echo $form->input('geobyte_info', array('type' => 'hidden', 'id' => 'geobyte_info'));
            echo $form->input('maxmind_info', array('type' => 'hidden', 'id' => 'maxmind_info'));
            echo $form->input('browser_info', array('type' => 'hidden', 'id' => 'browser_info'));
        ?>
	</fieldset>
<?php echo $form->end(__l('send now'));?>
</div>