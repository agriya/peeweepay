<?php /* SVN: $Id: $ */ ?>
<div class="reportAbuses form js-responses colorbox-content">
<h2><?php echo __l('Abuse Report'); ?></h2>
<?php echo $form->create('AbuseReport', array('class' => 'normal absuse-form  js-ajax-form'));?>
	<fieldset>
	<p class="report-content"><?php echo sprintf(__l('If you are offended by content on this product page please report your remarks to').' %s '.__l('using this form.').' ',Configure::read('site.name'));?></p>
	<?php
		echo $form->input('product_id', array('type'=>'hidden')); ?>
		<div class="clearfix">
	<?php echo $form->input('name', array(  'title' =>__l('Your name (required)'))); ?>
	<?php echo $form->input('email', array( 'title' =>__l('Your e-mail address (required)'))); ?>
		<?php echo $form->input('message', array( 'title' =>__l('Enter your message (required)'))); ?>
        <?php
        	echo $form->input('geobyte_info', array('type' => 'hidden', 'id' => 'geobyte_info'));
            echo $form->input('maxmind_info', array('type' => 'hidden', 'id' => 'maxmind_info'));
            echo $form->input('browser_info', array('type' => 'hidden', 'id' => 'browser_info'));
        ?>
		</div>
	</fieldset>
<?php echo $form->end(__l('send now'));?>
</div>
