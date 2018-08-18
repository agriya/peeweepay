<?php /* SVN: $Id: $ */ ?>
<div class="contactSellers form js-responses colorbox-content">
<h2><?php echo __l('Contact sellers'); ?></h2>
<?php if(!empty($success)) : ?>	
	<h2><?php echo __l('Thank you'); ?></h2>
	<?php echo __l('Your message has been delivered successfully.'); ?>
<?php else: ?>
<div class="contactSellers form">
<?php echo $form->create('ContactSeller', array('class' => 'normal js-ajax-form'));?>
	<fieldset>
	<p class="report-content"><?php echo __l('Use this form to send a message to the seller.'); ?></p>
	<div class="seller-details-content clearfix">
          <div class="seller-left">
		  <?php 
				if($product['User']['profile_image_id'] == ConstProfileImage::Twitter){ 
					if(!empty($product['User']['twitter_avatar_url'])){
						echo $html->image($product['User']['twitter_avatar_url'], array('title' => $html->cText($product['User']['fullname'], false)));
					}
				}		
				elseif($product['User']['profile_image_id'] == ConstProfileImage::Facebook){
					if(!empty($product['User']['fb_user_id'])){ 
						echo $html->image('http://graph.facebook.com/'.$product['User']['fb_user_id'].'/picture?type=small', array('title' => $html->cText($product['User']['fullname'], false)));
					}
				}
				elseif($product['User']['profile_image_id'] == ConstProfileImage::Upload){
					echo $html->showImage('UserAvatar', $product['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product['User']['fullname'], false)), 'title' => $html->cText($product['User']['fullname'], false)), null, null, false);
				}
				else{
					echo $gravatar->image($product['User']['email'], array('size' => '55', 'default' => 'identicon', 'title' => $html->cText($product['User']['fullname'], false)));
				    }
		  ?>  
          </div>
          <div class="seller-right">
          <p class="clearfix">
            <span class="user"><?php echo $html->cText($product['User']['fullname'], false);?></a></span>            
		  </p>
            <p class="spain"><?php echo $html->cText($product['User']['Country']['name']); ?></p>
            </div>
          </div>
	<?php
		echo $form->input('product_id', array('type' => 'hidden')); ?>
		<div class="js-overlabel"><?php echo $form->input('name', array('label' =>__l('Enter your name *'),'title' =>__l('Your name (required)'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('email', array('label' =>__l('Enter your e-mail address *'),'title' =>__l('Your e-mail address (required)'))); ?></div>
		<div class="js-overlabel subject"><?php echo $form->input('subject', array('label' =>__l('Enter a subject *'), 'title' =>__l('Enter a subject (required)'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('message', array('label' =>__l('Enter your message *'), 'title' =>__l('Enter your message (required)'))); ?></div>
        <?php
        	echo $form->input('geobyte_info', array('type' => 'hidden', 'id' => 'geobyte_info'));
            echo $form->input('maxmind_info', array('type' => 'hidden', 'id' => 'maxmind_info'));
            echo $form->input('browser_info', array('type' => 'hidden', 'id' => 'browser_info'));
        ?>
	</fieldset>
<?php echo $form->end(__l('Send now'));?>
</div>
<?php endif; ?>
</div>
