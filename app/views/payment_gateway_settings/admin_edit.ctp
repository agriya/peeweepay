<?php /* SVN: $Id: $ */ ?>
<h2><?php echo sprintf(__l('Edit').' %s '.__l('Settings'), $paymentGateway['PaymentGateway']['name']);?></h2>
<div id="breadcrumb">
   <?php $html->addCrumb(__l('Payment Gateways'), array('controller' => 'payment_gateways','action' => 'index')); ?>
  <?php $html->addCrumb(__l('Payment Gateway Setting Update')); ?>
  <?php echo $html->getCrumbs(' &raquo; '); ?>
</div>
<?php echo $html->link(__l('Add'), array('controller'=> 'payment_gateway_settings', 'action' => 'add', $paymentGateway['PaymentGateway']['id']), array('class' => 'add'));?>
<?php
if (!empty($paymentGatewaySettings)) {
	echo $form->create('PaymentGatewaySetting', array('action' => 'update', 'class' => 'normal'));
	foreach ($paymentGatewaySettings as $paymentGatewaySetting):
		$name = "PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.key";
		$options = array(
			'type' => $paymentGatewaySetting['PaymentGatewaySetting']['type'],
			'value' => $paymentGatewaySetting['PaymentGatewaySetting']['value'],
			'div' => array('id' => "PaymentGatewaySetting-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}")
		);
		if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description'])):
			$options['after'] = "<p class=\"setting-desc\">{$paymentGatewaySetting['PaymentGatewaySetting']['description']}</p>";
		endif;
		$options['label'] = Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']);
		echo $form->input($name, $options);
	endforeach;
	echo $form->input('payment_gateway_id', array('type' => 'hidden', 'value' => $paymentGatewaySetting['PaymentGatewaySetting']['payment_gateway_id']));
	echo $form->end(__l('Update'));
}else{
?>
	<div class="notice"><?php echo __l('Sorry no settings added.');?></div>
<?php
}
?>