<div class="js-response">
<?php echo $this->element('paging_counter');?>
<ol class="list dashboard-list clearfix">
<?php
        if (!empty($products)):
            $i = 0;
            foreach ($products as $product): ?>
            <li class="clearfix">
              <div class="product-description-left-block">
            <div class="product-img">
			<?php
			    if(!empty($product['ProductPhoto'])): ?>

			    <?php
				 echo $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false));
                ?>                
                  <?php  endif; ?>
				  </div>
                    <p class="price-amount-block">
                <?php
				 echo $html->cText($product['Currency']['symbol']);
				 echo $html->cText($product['Product']['price']);
				?>
				</p>
				</div>
			 <div class="product-description-right-block grid_9 omega alpha">
                <h4><?php echo $html->link($html->cText($product['Product']['title']), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false)); ?></h4>
				<div class="product-description"><?php echo $html->cText($product['Product']['description']); ?></div>
				<div class="product-description"><?php echo sprintf('Page views: %s', $html->cText($product['Product']['product_view_count'], false)); ?></div>
				<div class="product-description"><?php echo sprintf('Sold quantity: %s', $html->cText($product['Product']['sold_quantity'], false)); ?></div>
				<div class="action-block">
				<?php echo $html->link(__l('Edit'), array('controller' => 'products', 'action'=>'edit', $product['Product']['id']), array( 'title' => __l('Edit')));?>
                <?php echo $html->link(__l('Delete'), array('controller' => 'products', 'action'=>'delete', $product['Product']['id']), array( 'title' => __l('Delete')));?>
                </div>
                </div>
				</li>
				<?php
            endforeach;
        else: ?>
        <li class="notice">
        <?php
         echo __l('No Products available');
		?>
		</li>
		<?php
        endif;	
?>
</ol>
<div class="js-pagination">
<?php if (!empty($products)):
	echo $this->element('paging_links'); ?>	
<?php endif;
?>
</div>
</div>