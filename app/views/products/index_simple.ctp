<?php
   if (!empty($products)):
		?>
<h3><?php  echo $html->cText($products[0]['User']['fullname']).__l('\'s other product:'); ?></h3>
<ol class="product-list clearfix">
	     <?php
	  
           foreach ($products as $product): ?>
           <li class="clearfix">
	         <div class="seller-img">
				<?php echo $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('class' => 'tool-tip', 'title' => $html->cText($product['Product']['title'], false),  'escape' => false));?> 
			  </div>
	          <div class="input text required">
				<label for="caption"></label>
				<input type="text"  value="<?php echo $html->cText($product['Currency']['symbol'], false); echo $html->cText($product['Product']['price'], false); ?>" id="caption" />
			 </div>
			<p class="seller-desc"><?php echo $html->truncate($product['Product']['title'], 15); ?></p>
			</li>
	   <?php
          endforeach;
	  ?>
	
</ol>
<?php
		endif; 
	  ?>