<?php
   if (!empty($products)):
		?>
 <ol class="upload-list upload-list1 clearfix">
  <?php
           foreach ($products as $product): ?>
          <li class="tool-tip" title="<?php echo $html->cText($product['Product']['title'], false); ?>">
         
              <div class="product-img">
               <?php
			    if(!empty($product['ProductPhoto'])): ?>
				<?php echo $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'small_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('class' => '', 'title' => $html->cText($product['Product']['title'], false), 'escape' => false));
                 else:
		         echo $html->link($html->image('seller-background2.png', array( 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('class' => '', 'title' => $html->cText($product['Product']['title'], false), 'escape' => false));
				 endif;
				?>

              </div>
              <p class="price-amount-block"> <span class="c cr"><?php echo $html->cText($product['Currency']['symbol']);?><?php echo $html->cText($product['Product']['price']); ?></span> </p>
          
          </li>
         <?php
          endforeach;
	  ?>
        </ol>

<?php
		endif;
	  ?>
