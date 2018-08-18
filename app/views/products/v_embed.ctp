<div style="background:url(<?php echo $this->webroot; ?>img/seller-background1.png) no-repeat scroll 0 0 transparent;height:78px;margin:0 0px 0px 5px;padding:5px 0 0;position:relative;text-align:center;	width:90px;">
<?php
if(!empty($product['ProductPhoto'])):
 echo $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'border' => 0, 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false, 'title' => $html->cText($product['Product']['title'], false)));
 else:
 echo $html->link('', array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false, 'target' =>'parent', 'title' => $html->cText($product['Product']['title'], false), 'style' =>'width:90px;height:78px; float:left'));
 endif;
?> 
</div>

<span style="background:url('<?php echo $this->webroot; ?>img/seller-caption-bg.png') no-repeat scroll 0 0 transparent;float:left;height:31px;line-height:26px;margin:-6px 0 0;text-align:center;width:101px;font-size:13px;font-family:Arial, Helvetica, sans-serif;color:#555;font-weight:bold"><?php echo $html->cText($product['Currency']['symbol'], false); echo $html->cText($product['Product']['price'], false); ?></span>

			