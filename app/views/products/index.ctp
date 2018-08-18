<div class="js-responses">
<?php echo $this->element('paging_counter');?>
<ol class="list clearfix">

<?php
        if (!empty($products)):
            $i = 0;
            foreach ($products as $product): ?>
            <li class="clearfix">
            <div class="product-description-left-block">
                <div class="product-img">
				<?php
    			    if(!empty($product['ProductPhoto'])): ?>
    				 <?php echo $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false));
                    ?>
                    <?php endif;?>
					</div>
                    <p class="price-amount-block">
						<?php echo $html->cCurrency($product['Product']['price'], $product['Currency']);?>
					</p>
				</div>
				
			 <div class="product-description-right-block grid_9 omega alpha">
			  <div class="author-info clearfix">
			 <span class="info"><?php
				echo $time->timeAgoInWords($product['Product']['created']);
				echo __l(' by ');
				echo $html->link($html->cText($product['User']['fullname']), array('controller' => 'products', 'action' => 'index', 'user' => $product['User']['id']), array('escape' => false)); ?>
				</span>
             </div>
                <h3><?php echo $html->link($html->cText($product['Product']['title']), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false)); ?></h3>
				<div class="product-description"><?php echo $html->cText($product['Product']['description']); ?></div>
                   
            </div>
            </li>
           <?php  endforeach;
        else: ?>
        <li class="notice">
            <?php  echo __l('No Products available'); ?>
		</li>
      <?php endif;  ?>
</ol>

<?php if (!empty($products)):
	echo $this->element('paging_links'); ?>
	<p class="view-page-info">
	<?php echo __l('View this page in'); ?>
	<?php
    echo $html->link(__l('RSS'), array_merge($this->params['named'], array('controller' => 'products', 'action' => 'index', 'ext' => 'rss')), array('target' => '_blank', 'escape' => false));
?>

<?php
   // echo $html->link(__l('XML'), array_merge($this->params['named'], array('controller' => 'products', 'action' => 'index', 'ext' => 'xml')), array('target' => '_blank', 'escape' => false));
    ?>
    </p>
<?php endif;
?>
</div>
