<?php /* SVN: $Id: $ */ ?>
<div class="transactions index js-response">
<h2><?php echo $this->pageTitle;?></h2>
<?php echo $form->create('Transaction' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal search-form filter-form clearfix')); ?>
	<div>
	 <?php
		
		echo $form->input('Transaction.q', array('label' => __l('Keyword')));
		
	 ?>
	
	</div>
   <div class="submit-block clearfix">
      	<?php
    	echo $form->submit(__l('Filter'));
    ?>
    </div>
    <?php echo $form->end(); ?>
<?php echo $this->element('paging_counter');?>
<div class="overflow-block">
<table class="list">
    <tr>                   
		<th rowspan="3"><div class="js-pagination"><?php echo $paginator->sort(__l('Date'),'created');?></div></th>
        <th rowspan="3"><div class="js-pagination"><?php echo $paginator->sort(__l('Product'), 'product_id');?></div></th>
        <th rowspan="3"><div class="js-pagination"><?php echo $paginator->sort(__l('Buyer Name'),'name');?></div></th>
        <th rowspan="3"><div class="js-pagination"><?php echo $paginator->sort(__l('Address'), 'address');?></div></th>
        <th rowspan="3"><div class="js-pagination"><?php echo $paginator->sort(__l('Quantity'), 'quantity');?></div></th>
		<th rowspan="3"><div class="js-pagination"><?php echo $paginator->sort(__l('Status'), 'stattus');?></div></th>   
		<th colspan ="5"><?php echo __l('Amount');?></th>		
    </tr>
	<tr>                   		        
        <th colspan="2"><?php echo __l('Seller');?></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('To Site'), 'site_amount');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Total'), 'amount');?></div></th>
    </tr>
	<tr>                   		        
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Products'), 'seller_amount');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Shipping'), 'ship_amount');?></div></th>        
    </tr>
<?php
if (!empty($transactions)):

$i = 0;
foreach ($transactions as $transaction):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
		<div class="actions-block">
			<div class="actions round-5-left">
		<span><?php echo $html->link(__l('View'), array('controller' => 'transactions', 'action' => 'view', $transaction['Transaction']['id']), array('class' => 'view', 'title' => __l('View')));?></span> 
            </div></div>
			<?php echo $html->cDateTimeHighlight($transaction['Transaction']['created']);?></td>

		<td><?php echo $html->link($html->cText($transaction['Product']['title']), array('controller'=> 'products', 'action'=>'view', $transaction['Product']['slug'], 'admin'=>false), array('escape' => false));?></td>
		<td><?php echo $html->cText($transaction['Transaction']['name']);?></td>
		<td>
		<address>
		   <p><?php echo $html->cText($transaction['Transaction']['address1']); ?></p>		      
		   <p><?php echo $html->cText($transaction['Transaction']['city']);?></p>		
			 <p><?php  echo $html->cText($transaction['Transaction']['state']);?></p>		
			 <p><?php  echo $html->cText($transaction['Country']['name']);?></p>		
			<p><?php   echo $html->cText($transaction['Transaction']['postal_code']);?></p>				  
		</address>
		</td>				
		<td><?php echo $html->cInt($transaction['Transaction']['quantity']);?></td>		
		<td><?php echo $html->cText($transaction['Transaction']['status']);?></td>	
		<td><?php echo sprintf('%s', $html->cCurrency($transaction['Transaction']['seller_amount'], $transaction['Currency']));?></td>
				<td><?php echo sprintf('%s', $html->cCurrency($transaction['Transaction']['ship_amount'], $transaction['Currency']));?></td>
		<td><?php echo sprintf('%s', $html->cCurrency($transaction['Transaction']['site_amount'], $transaction['Currency']));?></td>

		<td><?php echo sprintf('%s', $html->cCurrency($transaction['Transaction']['amount'], $transaction['Currency']));?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="28" class="notice"><?php echo __l('No Transactions available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<div class="js-pagination">
<?php
if (!empty($transactions)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>
