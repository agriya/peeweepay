<div class="transactions form js-product-buy">
<h2><?php echo __l('Buy now'); ?></h2>
<?php echo $form->create('Product', array('action' =>'buy', 'class' => 'normal'));
      echo $form->input('price', array('value'=>$product['Product']['price'], 'id' =>'product_price', 'type' => 'hidden'));	  
	  $remain_quantity = ( $product['Product']['quantity'] > 0) ? $product['Product']['quantity'] - $product['Product']['sold_quantity'] : 'unlimited';
	  echo $form->input('remain_quantity', array('value'=> $remain_quantity, 'id' =>'remain_quantity', 'type' => 'hidden'));
	  echo $form->input('is_shipment_cost_per_item_or_order', array('value'=> $product['Product']['is_shipment_cost_per_item_or_order'], 'id' =>'is_shipment_cost_per_item_or_order', 'type' => 'hidden'));
	  echo $form->input('is_shipment_cost_required', array('value'=> $product['Product']['is_shipment_cost_required'], 'id' =>'is_shipment_cost_required', 'type' => 'hidden'));
	  echo $form->input('total_shipp_amount', array('value'=> '0', 'id' =>'total_shipp_amount', 'type' => 'hidden'));
	  
	?>
	<fieldset>
 		<div class="clearfix product-buy-block">
		<div class="clearfix">
	   <?php
		echo $form->input('Transaction.quantity', array('class' => 'js-buy-quantity', 'title' =>__l('Quantity, set the number of items you wish to purchase'))); ?>
        <p class="cross">
        <?php
		echo __l('X'); ?>
		</p>
		<p class="buy-product-title"><?php echo $html->cText($product['Product']['title']);?></p>
		<p class="symbol">
		<span class="dollar"> <?php
		echo $html->cText($product['Currency']['symbol']);?></span><span id="total_buy_amount"> <?php echo $html->cCurrency($product['Product']['price'])?> </span><?php echo $html->cText($product['Currency']['code']);
        ?>
        </p>
		</div>
		<?php if(!empty($product['Product']['is_shipment_cost_required'])):?>
		<div class="clearfix">
			<p class="buy-product-title buy-product-title1"><?php echo __l('+ Shipment costs:');  ?><span id='shipp_country'></span></p>
			<p class="symbol">
			<span class="dollar">
			<?php echo $html->cText($product['Currency']['symbol']); ?></span><span id='shipp_amount'></span><?php echo $html->cText($product['Currency']['code']); ?>
			</p>
		</div>
		<?php endif; ?>
		<div class="clearfix total-blocks">
			<p class="buy-product-title"><?php echo __l('Total:').' ';  ?></p>
			<p class="symbol">
			
			<span id="total_gross_amount"> <?php echo $html->cText($product['Currency']['symbol']); echo $html->cCurrency($product['Product']['price'])?> </span><?php echo $html->cText($product['Currency']['code']); ?>
			</p>
		</div>
        </div>
		<?php if(!empty($product['Attachment']) && !empty($product['Attachment']['filename'])): ?>
	<p class="product-attachment round-5 buy-product-attachment"><?php		
		  echo $html->cText($product['Attachment']['filename']);
	   
 ?></p><?php endif; ?>        		
	<?php	echo $form->input('Transaction.product_id', array('type' => 'hidden'));
		if(!empty($product['Product']['is_shipment_cost_required'])):?>
<div class="buy-block">
		<div class="js-overlabel"><?php echo $form->input('Transaction.name', array('label' =>__l('Your name'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('Transaction.address1', array('label' =>__l('Address 1'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('Transaction.address2', array('label' =>__l('Address 2'))); ?></div>
		<?php echo $form->input('Transaction.country_id', array('label' => false, 'class' => 'js-shipment-country')); ?>
		<div class="js-overlabel"><?php echo $form->input('Transaction.postal_code', array('label' =>__l('Postal'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('Transaction.city', array('label' =>__l('City'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('Transaction.state', array('label' =>__l('State'))); ?></div>
		</div>
<?php	
		endif;
	?>
	</fieldset>
<?php echo $form->end(__l('pay with PayPal'));?>
</div>
<script type="text/javascript">
	 bind_calculateProductBuyPrice();
</script>