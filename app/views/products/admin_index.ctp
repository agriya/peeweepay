<?php /* SVN: $Id: $ */ ?>
<div class="products index js-response">
<h2><?php echo $this->pageTitle;?></h2>
<?php
	echo $form->create('Product' , array('action' => 'admin_index', 'type' => 'get', 'class' => 'normal filter-form clearfix ')); //js-ajax-form
	echo $form->input('Product.q', array('label' => __l('Keyword')));
?>
<div class="submit-block clearfix">
	<?php
    	echo $form->submit(__l('Filter'));
	?>
</div>
<?php echo $form->end(); ?>
<p>
   <ul class="verification clearfix">
       <li><span class="product-verified round-5"><?php echo $html->link(sprintf(__l('Verified:').' '.'%s',$products_count['verified']), array('action' => 'index', 'is_verified' => 1), array('title' => __l('Verified Products')));?></span></li>
       <li><span class="product-unverified round-5"><?php echo $html->link(sprintf(__l('Unverified:').' '.'%s',$products_count['unverified']), array('action' => 'index', 'is_verified' => 0), array('title' => __l('Unverified Products')));?></span></li>
       <li><span class="product-spam round-5"><?php echo $html->link(sprintf(__l('Spams:').' '.'%s',$products_count['spammed']), array('action' => 'index', 'is_spam' => 1), array('title' => __l('Spammed Products')));?></span></li>
       <li><span class="product-abuse round-5"><?php echo $html->link(sprintf(__l('Abuses:').' '.'%s',$products_count['abused']), array('action' => 'index', 'is_abuse' => 1), array('title' => __l('Abused Products')));?></span></li>
	</ul>
</p>
<div class="add-block">
	<?php echo $html->link(__l('Add'),array('controller'=>'products','action'=>'add'),array('title' => __l('Add New Product'),'class' =>'admin-add'));?>
	<?php echo $this->element('paging_counter');?>
</div>
<?php echo $form->create('Product' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
<div class="overflow-block set-scroll">
<table class="list">
    <tr>
		<th rowspan="2"><?php echo __l('Select');?></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Owner'),'user_id');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Product'),'title');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Description'), 'description');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Address'), 'address');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Price'), 'price');?></div></th>
		<th colspan="2"><div class="js-pagination"><?php echo __l('Quantity');?></div></th>
        <th colspan="2"><?php echo __l('Reports');?></th>
        <th colspan="3"><?php echo __l('Views');?></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Photos'),'product_photo_count');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Tags'),'product_tag_count');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Seller Contacts'), 'contact_seller_count');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Shipment Countries'), 'product_shipment_cost_count');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Display Page Views?'),'is_display_page_views');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Display Quantity?'),'is_display_quantity');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Include Search?'),'is_include_search');?></div></th>
        <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Require Shippment Cost?'),'is_shipment_cost_required');?></div></th>
		<th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Added On'),'created');?></div></th>        
    </tr>
	<tr>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Sold'),'sold_quantity');?></div></th>
		<th><div class="js-pagination"><?php echo $paginator->sort(__l('Total'),'quantity');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Spam'),'spam_report_count');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Abuse'),'abuse_report_count');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Normal'),'product_normal_view_count');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Embed'),'product_embed_view_count');?></div></th>
        <th><div class="js-pagination"><?php echo $paginator->sort(__l('Total'),'product_view_count');?></div></th>
	</tr>
<?php
if (!empty($products)):
$i = 0;
foreach ($products as $product):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if($product['Product']['is_verified']):
		$status_class= 'js-checkbox-active';
	else:
		$status_class= 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
		<td>
			<div class="actions-block">
				<div class="actions round-5-left">
					<span><?php echo $html->link(__l('Edit'), array('action' => 'edit', $product['Product']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span>
					<span><?php echo $html->link(__l('Delete'), array('action' => 'delete', $product['Product']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>
				 </div>
			</div>
			<?php echo $form->input('Product.'.$product['Product']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$product['Product']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?>
		</td>
		<td><?php echo (!empty($product['User']['fullname'])) ? $html->cText($product['User']['fullname']) : $html->cText($product['Product']['fullname']); ?></td>
		<td><?php echo $html->link($product['Product']['title'], array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView, 'admin' => false));?>
		     <?php if(!empty($product['Product']['spam_report_count'])): ?>
			 <span class="product-spam round-5"><?php echo $html->link(sprintf(__l('Spams (%s)'),$html->cInt($product['Product']['spam_report_count'], false)), array('controller' => 'spam_reports', 'action' => 'index', 'product_id' => $product['Product']['id']), array('title' => $html->cInt($product['Product']['spam_report_count'], false))); ?></span>
			 <?php endif; ?>
			 <?php if(!empty($product['Product']['abuse_report_count'])): ?>
			 <span class="product-abuse round-5"><?php echo $html->link(sprintf(__l('Abuses (%s)'),$html->cInt($product['Product']['abuse_report_count'], false)), array('controller' => 'abuse_reports', 'action' => 'index', 'product_id' => $product['Product']['id']), array('title' => $html->cInt($product['Product']['abuse_report_count'], false))); ?></span>
			 <?php endif; ?>
				<?php if(!empty($product['Product']['is_verified'])): ?>
				<span class="product-verified round-5"><?php echo __l('Verified') ?></span>
				<?php else: ?>
				 <span class="product-unverified round-5"><?php echo __l('Unverified') ?></span>
				<?php endif; ?>
			 <?php if(!empty($product['Product']['is_admin_suspended'])): ?>
				<span class="product-admin-suspend round-5"><?php echo __l('Suspended') ?></span>
			 <?php endif; ?>
		</td>
		<td><?php echo $html->cText($html->truncate($product['Product']['description'], 60));?></td>
		<td><address><?php echo $html->cText($product['Product']['address']);?></address></td>
		<td><?php echo sprintf('%s', $html->cCurrency($product['Product']['price'], $product['Currency']));?></td><td>
		<?php echo $html->link($html->cInt($product['Product']['sold_quantity'], false), array('controller' => 'transactions', 'action' => 'index', 'product_id' => $product['Product']['id']), array('title' => $html->cInt($product['Product']['sold_quantity'], false))); ?></td>
		<td><?php echo ($product['Product']['quantity'] > 0) ? $html->cText($product['Product']['quantity']) : __l('Unlimited'); ?></td>
		<td><?php echo $html->link($html->cInt($product['Product']['spam_report_count']), array('controller' => 'spam_reports', 'action' => 'index', 'product_id' => $product['Product']['id']), array('escape' => false)); ?></td>
		<td><?php echo $html->link($html->cInt($product['Product']['abuse_report_count']), array('controller' => 'abuse_reports', 'action' => 'index', 'product_id' => $product['Product']['id']), array('escape' => false));?></td>
		<td><?php echo $html->cInt($product['Product']['product_normal_view_count']);?></td>
		<td><?php echo $html->cInt($product['Product']['product_embed_view_count']);?></td>
		<td><?php echo $html->cInt($product['Product']['product_view_count']);?></td>
        <td><?php echo $html->cInt($product['Product']['product_photo_count']);?></td>
		<td><?php echo $html->cInt($product['Product']['product_tag_count']);?></td>
		<td><?php echo $html->link($html->cInt($product['Product']['contact_seller_count']), array('controller' => 'contact_sellers', 'action' => 'index', 'product_id' => $product['Product']['id']), array('escape' => false));?></td>
		<td><?php echo $html->cInt($product['Product']['product_shipment_cost_count']);?></td>
		<td><?php echo $html->cBool($product['Product']['is_display_page_views']);?></td>
		<td><?php echo $html->cBool($product['Product']['is_display_quantity']);?></td>
		<td><?php echo $html->cBool($product['Product']['is_include_search']);?></td>
		<td><?php echo $html->cBool($product['Product']['is_shipment_cost_required']);?></td>
		<td><?php echo $html->cDateTimeHighlight($product['Product']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="30" class="notice"><?php echo __l('No Products available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>

<?php if (!empty($products)) {?>
	<div class="admin-select-block">
		<div>
			<?php echo __l('Select:'); ?>
			<?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
			<?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
			<?php echo $html->link(__l('Verified'), '#', array('class' => 'js-admin-select-approved','title' => __l('Verified'))); ?>
			<?php echo $html->link(__l('Unverified'), '#', array('class' => 'js-admin-select-pending','title' => __l('Unverified'))); ?>
		</div>
		<div class="admin-checkbox-button">
            <?php echo $form->input('more_action_id', array('options' => $moreActions, 'class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
	</div>
	<div class="js-pagination">
		<?php echo $this->element('paging_links');?>
	</div>
	<div class="hide">
		<?php echo $form->submit(__l('Submit'));  ?>
	</div>
<?php     echo $form->end();
}?>
</div>