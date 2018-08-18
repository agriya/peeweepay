<?php /* SVN: $Id: $ */ ?>
<div class="transactions view">
<h2><?php echo __l('Transaction');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cInt($transaction['Transaction']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cDateTime($transaction['Transaction']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cDateTime($transaction['Transaction']['modified']);?></dd>		
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Product');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->link($html->cText($transaction['Product']['title']), array('controller'=> 'products', 'action'=>'view', $transaction['Product']['slug'], 'admin'=>false), array('escape' => false));?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Buyer Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['name'])? $html->cText($transaction['Transaction']['name']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Address1');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['address1'])? $html->cText($transaction['Transaction']['address1']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Address2');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['address2'])? $html->cText($transaction['Transaction']['address2']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('City');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['city'])? $html->cText($transaction['Transaction']['city']): '-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('State');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['state'])? $html->cText($transaction['Transaction']['state']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Country');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Country']['name'])? $html->cText($transaction['Country']['name']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Postal Code');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['postal_code'])? $html->cText($transaction['Transaction']['postal_code']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currency');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Currency']['code'])? $html->cText($transaction['Currency']['code']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Quantity');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cInt($transaction['Transaction']['quantity']);?></dd>

		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo  $html->cCurrency($transaction['Transaction']['amount'], $transaction['Currency']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Seller Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo  $html->cCurrency($transaction['Transaction']['seller_amount'], $transaction['Currency']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Shipping Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo  $html->cCurrency($transaction['Transaction']['ship_amount'], $transaction['Currency']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Site Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo  $html->cCurrency($transaction['Transaction']['site_amount'], $transaction['Currency']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Status');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['status'])? $html->cText($transaction['Transaction']['status']):'-';?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Sender Email');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['sender_email'])? $html->cText($transaction['Transaction']['sender_email']):'-';?></dd>				
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Pay Key');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo !empty($transaction['Transaction']['pay_key'])? $html->cText($transaction['Transaction']['pay_key']):'-';?></dd>	
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Is Downloaded');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cBool($transaction['Transaction']['is_downloaded']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Download Count');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $html->cInt($transaction['Transaction']['download_count']);?></dd>		
	</dl>
</div>

