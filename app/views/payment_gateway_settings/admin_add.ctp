<?php /* SVN: $Id: $ */ ?>
<div class="paymentGatewaySettings form">
<h2><?php echo __l('Add Payment Gateway Settings');?></h2>
<div id="breadcrumb">
   <?php $html->addCrumb(__l'Payment Gateways'), array('controller' => 'payment_gateways','action' => 'index')); ?>
  <?php $html->addCrumb(__l('Add Payment Gateway Setting')); ?>
  <?php echo $html->getCrumbs(' &raquo; '); ?>
</div>
<?php echo $form->create('PaymentGatewaySetting', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $form->input('payment_gateway_id');
		echo $form->input('key', array('label' => __l('Key')));
		echo $form->input('type', array('type' => 'select', 'options' => array('text' => 'text', 'textarea' => 'textarea', 'checkbox' => 'checkbox', 'radio' => 'radio', 'password' => 'password')));
		echo $form->input('value', array('label' => __l('Value')));
		echo $form->input('description', array('label' => __l('Description')));
	?>
	</fieldset>
<?php echo $form->end(__l('Add'));?>
</div>