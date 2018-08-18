<?php if(!empty($success)) : ?>
<h2><?php echo __l('Deleted');?></h2>
<p>
<?php echo __l('Your product page has been deleted. Make sure you remove all references to your product page');
?>
</p>
<p>
<?php
echo $html->link(__l('continue'),array('controller'=>'users','action'=>'dashboard',$product['Product']['user_id']),array('title' => __l('click here')));
?>
</p>
<?php
else: ?>
<h2><?php echo __l('Delete');?></h2>
<p class="delete-information">
<?php echo __l('Are you sure that you want to delete your product page?');
?>
</p>
<?php
     echo $form->create('Product', array('action'=>'delete','class' => 'normal'));
     echo $form->input('id',array('type'=>'hidden')); ?>
     <ol class="list  dashboard-list clearfix">
      <li class="clearfix">
           <div class="product-description-left-block">
                 <?php
                 if(!empty($product['ProductPhoto'])): ?>
                     <div class="product-img">
                         <?php
                         echo $html->link($html->showImage('ProductPhoto', $product['ProductPhoto'][0]['Attachment'], array('dimension' => 'normal_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['Product']['title'], false)), 'title' => $html->cText($product['Product']['title'], false))), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false));
                            ?>
                    </div>
                    <?php endif;
                    ?>
            </div>
    	 <div class="product-description-right-block">
            <h4>
                 <?php echo $html->link($html->cText($product['Product']['title']), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'], 'view_type' => ConstViewType::NormalView), array('escape' => false)); ?>
            </h4>
            <div class="product-description">
                 <?php echo $html->cText($product['Product']['description']);
                  ?>
            </div>
        </div>
    </li>
</ol>
<div class="delete-block clearfix">
<?php echo $form->submit(__l('Delete')); ?>
</div>
<?php echo $form->end(); ?>
<?php endif; ?>