<?php /* SVN: $Id: $ */ ?>
<div class="subscriptions form js-responses">
<h2><?php echo __l('Newsletter subscription'); ?></h2>
<?php echo $form->create('Subscription', array('class' => 'normal js-ajax-form'));?>
	<fieldset>
	<p class="report-content"><?php echo sprintf(__l('Subscribe to our newsletter to receive the latest').' %s '.__l('information.').' ',Configure::read('site.name'));?></p>
	<div class="js-overlabel"><?php echo $form->input('name', array('label' =>__l('Your name (optional)'), 'class'=>'tool-tip', 'title' =>__l('Your name (optional)'))); ?></div>
	<div class="js-overlabel"><?php echo $form->input('email', array('label' =>__l('Enter your e-mail address *'), 'class'=>'tool-tip', 'title' =>__l('Enter your e-mail address *'))); ?></div>
	</fieldset>
<?php echo $form->end(__l('Subscribe now'));?>
</div>
