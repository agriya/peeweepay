      <div class="dollar clearfix">
        <div class="dollar-tr">
          <div class="dollar-tm">
            <h2><?php echo $html->cText($product['Product']['title']);?></h2>
            <div class="buy-block clearfix">
            <?php
		      if($product['Product']['quantity'] > 0 && $product['Product']['quantity']==$product['Product']['sold_quantity']):
			 ?>
  	  <span class="sold"><?php echo __l('- SOLD -'); ?></span>
			  <?php
		      else:
   	echo $html->link(__l('Buy Now'), array('controller' => 'products', 'action' => 'buy', $product['Product']['id']), array('class' =>'js-thickbox button', 'escape' => false));
		      endif;
             ?>
             <p class="buy-amount-info"><?php echo sprintf('%s', $html->cCurrency($product['Product']['price'], $product['Currency'])); ?></p>
             </div>
          </div>
        </div>
      </div>
	  <div class="stock-block clearfix">
	  <div class="grid_4 add-info-block omega  clearfix">
	  <div class=" clearfix">
		<div class="addthis_toolbox addthis_default_style addthis_pill_combo abs abs-tl lh-24 o-visible">
			<?php $product_url = Router::url(array(
					'controller' => 'products',
					'action' => 'v',
					'slug' => $product['Product']['slug'],
					'view_type' => ConstViewType::NormalView,
					'admin' => false
				) , true);
			?>
			<!-- AddThis Button BEGIN -->
            <div class="addthis_toolbox addthis_default_style ">
                <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
                <a class="addthis_button_tweet" tw:via="<?php echo Configure::read('twitter.username');?>" tw:text="<?php echo $product['Product']['title'];?>"></a>
                <a class="addthis_counter addthis_pill_style"></a>
            </div>
            <script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pubid=xa-4d7f4a9c2ded1580"></script>
            <!-- AddThis Button END -->
		</div>
	  </div>
	  </div>
	  <div class="link-left grid_7 omega alpha clearfix">

	<?php
	  $tiny_url = Router::url(array(
			'controller' => 'products',
			'action' => 'v',
			'slug' => $product['Product']['slug'],
			'view_type' => ConstViewType::NormalView,
			'admin' => false
		) , true);
	  ?>
	  <div class="code-block1 accordion clearfix">
	  <ul class="code-list">
	  <li class="abus-report"><?php echo $html->link(__l('Abuse Report'), array('controller' => 'abuse_reports', 'action' => 'add', $product['Product']['id']), array('class' =>'js-thickbox','escape' => false)); ?></li>
	  <li><?php echo $html->link(__l('Spam Report'), array('controller' => 'spam_reports', 'action' => 'add', $product['Product']['id']), array('class' =>'js-thickbox','escape' => false));	?></li>
	  <li>
	  <h3><?php echo __l('URL'); ?></h3>
	  <div class="url-block"><?php echo $form->input('tiny_url', array('class' =>'js-tiny-url clipboard', 'type' => 'text', 'label' => false, 'value' =>$tiny_url, 'readonly' => 'yes', 'id' => 'ProductTinyUrl'));?></div>
	  <?php
        $qr_url = 'http://chart.apis.google.com/chart?chs='.Configure::read('qr.width').'x'.Configure::read('qr.height').'&cht=qr&chl='.$tiny_url.'&choe=UTF-8&chld=L|2';
        $qr_preview = $html->image($qr_url);?>
        </li>
         <li>
        <h3><?php echo __l('QR Code')?></h3>
        <div class="url-block"><?php echo $form->input('qr_url', array('class' =>'js-qr-code clipboard', 'type' => 'text', 'label' => false, 'value' =>$qr_url, 'readonly' => 'yes', 'id' => 'qrcodepreview'));?></div>
        <script type="text/javascript">
          $('#qrcodepreview').bt('<?php echo $qr_preview; ?>', {
        	  width: 120,
        	  cornerRadius: 20,
        	  padding: 20,
        	  strokeWidth: 1,
        	  trigger: ['mouseover', 'mouseout'],
        	  fill: '#f7f8b2',
        	  cssStyles: {
        		color: '#555555',
        		width: 'auto'
        	  }
        });
        </script>

        <?php
        $embed_url = Router::url(array(
        	'controller' => 'products',
        	'action' => 'v',
        	'slug' => $product['Product']['slug'],
        	'view_type' => ConstViewType::EmbedView,
        	'admin' => false
        ) , true);
        $embed_code = '<iframe src="'.$embed_url.'" width="105" height="120" frameborder = "0" scrolling="no"></iframe>';?>
        </li>
        <li>
          <h3><?php echo __l('Embed');?></h3>
        <div class="url-block"><?php echo $form->input('embed_url', array('class' =>'js-embed clipboard', 'type' => 'text', 'label' => false, 'value' =>$embed_code, 'readonly' => 'yes', 'id' => 'ProductEmbedUrl'));?></div>
        </li>
        </ul>

	  </div>
		 <div class="code-block">
   		<?php if(!empty($product['Product']['is_display_quantity'])):
		       $remain_quantity = ($product['Product']['quantity'] > 0) ? $html->cText($product['Product']['quantity'] - $product['Product']['sold_quantity'] , false) : __l('unlimited'); ?>
			  <p><?php echo __l('Stock'); ?><span class="tool-tip" title ="<?php echo sprintf(__l('Quantity left:').' %s', $remain_quantity); ?>">
				<?php
				   echo $remain_quantity;
				?>
			  </span>
			  	<?php if(!empty($product['Product']['is_display_page_views'])): ?>
					<span class="count1 tool-tip" title ="<?php echo sprintf(__l('Page views:').' %s', $html->cText($product['Product']['product_normal_view_count'], false)); ?>"><?php echo $html->cText($product['Product']['product_normal_view_count'], false); ?></span>
					<?php endif; ?>
			  </p>
		<?php endif; ?>
		</div>
      </div>
	  </div>
      <div class="product-info-block clearfix">
      <div class="clearfix">
        <div class="product-view-block clearfix">
       <?php echo $form->create('Product', array('class' => 'normal js-product-view-map'));?>
          <div class="club">
          <div class="clearfix">
         <div id='js-gallery' class="svwp">
            	<?php if(!empty($product['ProductPhoto'])): ?>

            <ol class=" upload-list clearfix <?php echo (count($product['ProductPhoto']) == 1 ? 'first-upload-list' : (count($product['ProductPhoto']) == 2 ? 'second-upload-list' : '')); ?>">
            <?php foreach($product['ProductPhoto'] as $productPhoto):
	       if(!empty($productPhoto['Attachment'])):
		   $image_url = $html->getImageUrl('ProductPhoto', $productPhoto['Attachment'], array('dimension' => 'original'));?>
              <li>
               <?php echo $html->link($html->showImage('ProductPhoto', $productPhoto['Attachment'], array('dimension' => 'big_thumb', 'title' => $html->cText($productPhoto['caption'], false), 'alt' => sprintf(__l('[Image: %s]'), $html->cText($productPhoto['caption'], false)))), $image_url, array('title' => $html->cText($productPhoto['caption'], false), 'class' =>'js-thickbox', 'escape' => false, 'rel' =>'product_photo')); ?><?php echo $html->cText($productPhoto['caption']); ?>
              </li>
            <?php
	       endif;
	     endforeach; ?>
            </ol>
           <?php  endif; ?>
            </div>
             </div>
            <?php if(!empty($product['Attachment']) && !empty($product['Attachment']['filename'])): ?>
	<p class="product-attachment"><?php
		if($product['Product']['quantity'] > 0 && $product['Product']['quantity']==$product['Product']['sold_quantity']):
          ?><?php echo __l('- SOLD -'); ?><?php
        else:
		  echo $html->link($html->cText($product['Attachment']['filename']), array('controller' => 'products', 'action' => 'buy', $product['Product']['id']), array('class' =>'js-thickbox', 'escape' => false));
	   endif;
 ?></p>
     <?php endif; ?>
          </div>
          <div class="seller">
          
            <div class="seller-center clearfix">
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
					echo $gravatar->image($product['User']['email'], array('size' => '55', 'default' => 'identicon', 'title' => $html->cText($product['User']['fullname'], false))); ?>
					<?php
				}
		        ?>
              </div>
              <div class="seller-right">
                <p class="clearfix"> <span class="user"><?php echo $html->cText($product['User']['fullname'], false);?></span>
                <?php
                echo $html->link('RSS', array('controller' => 'products', 'action' => 'index', 'user' => $product['User']['id'], 'ext' => 'rss'), array('target' => '_blank', 'class' => 'rss'));
		         ?>
                 </p>
                <p class="spain"><?php echo $html->cText($product['User']['Country']['name']); ?></p>
              </div>
              <div class="seller-details-right1 clearfix">
                <div class="code-block1 clearfix">
            	  <ul class="code-list">
            	  <li class="abus-report">
                        <?php if($product['User']['product_count'] > 5):
        				  echo $html->link(sprintf(__l('show all').' %s '.__l('products'), $product['User']['product_count']), array('controller' => 'products', 'action' => 'index', 'user' => $product['User']['id']), array('escape' => false));
        			   endif; ?>
                  </li>
            	  <li>
                      <?php
           			   echo $html->link(__l('contact me'), array('controller' => 'contact_sellers', 'action' => 'add', $product['Product']['id']), array('class' =>'js-thickbox','escape' => false));
                       ?>
                   </li>
            	  </ul>
	        </div>
            </div>
             </div>
            <div class="seller-bl">
              <div class="seller-br">
                <div class="seller-bm"> </div>
              </div>
            </div>

             </div>
          <div class="map-block">
          <?php
			echo $form->input('latitude', array('type' => 'hidden', 'id' =>'product_latitude', 'value' =>$product['Product']['latitude']));
			echo $form->input('longitude', array('type' => 'hidden', 'id' =>'product_longitude', 'value' =>$product['Product']['longitude']));
			echo $form->input('zoom_level', array('type' => 'hidden', 'id' =>'product_zoom_level', 'value' =>$product['Product']['zoom_level']));
			?>
            <?php if(Configure::read('product.map_type') == 'static'):?>
			  <div class="view-product-map">
				<?php
					echo $html->image($html->formGooglemap($product,'420x243'));
				?>
			  </div>
		  <?php else:?>
				<div class="view-product-map" id="js-map"></div>
		  <?php endif;?>
		  </div>
        <?php echo $form->end();?>
        </div>
        <div class="product-desc-block">
          <p><?php echo $html->cHtml($product['Product']['description']);?> </p>
          <div class="product-detail clearfix">
            <p class="last-update"> <? echo __l('last update'). ': ' .$time->timeAgoInWords($product['Product']['created']); ?></p>
            <div class="clearfix clubwear-block">
              <ul class="clubwear clearfix">
              <?php
				if (!empty($product['ProductTag'])) :
					foreach($product['ProductTag'] as $product_tag) :
						?>
						<li><?php echo $html->link($html->cText($product_tag['name']), array('controller' => 'products', 'action' => 'index', 'tag' => $product_tag['slug']), array('escape' => false));?></li>
						<?php
					endforeach;
				else :
					?>
					<li class="notice"><?php echo __l('No tags available');?></li>
					<?php
				endif;
				?>



          </ul>
            </div>
          </div>
          <?php if(!empty($product['ProductShipmentCost'])): ?>
	  <div>
	      <?php
		  echo __l('This item only ships to: ');
		  $pre_ship = 0;
		  for($i =0;  $i < 2; $i++):
	       if(!empty($product['ProductShipmentCost'][$i])):
				if(!empty($pre_ship)):
					echo __l(', ');
				endif;
				echo sprintf('%s (%s)',$html->cText($product['ProductShipmentCost'][$i]['GroupedCountry']['name']),$html->cCurrency($product['ProductShipmentCost'][$i]['shipment_cost'], $product['Currency']));
				$pre_ship = 1;

		   endif;
          endfor;
		   ?>
		   <?php echo $html->link(__l('more details'), array('controller' => 'products', 'action' => 'shipment_map', $product['Product']['id']), array('class' =>'js-iframe-thickbox','escape' => false)); ?>
	  </div>
	  <?php endif ;?>

            <div class="seller">
                 <div class="seller-image">
		        <?php echo $this->element('products-index_simple', array('cache' => array('key' => $product['User']['id'].'-'.$product['Product']['id'], 'time' => '120'))); ?>
                </div>
             </div>
        <div class="comments" style="overflow:hidden;">
            <div class="head1 ">
              <h3><?php  echo __l('Comments'); ?></h3>
            </div>
            <div id="disqus_thread">
            </div>
            <?php 
                echo str_replace('##PRODUCT_URL', Router::url('/', true).$this->params['url']['url'], Configure::read('product.disqus_comment_code')); ?>
          </div>


        </div>
        </div>


<?php
    if($auth->user('user_type_id') == ConstUserTypes::Admin):
        ?>
        <div class="new">
        <div class="js-tabs">
            <ul class="clearfix admin-tab-link">
                <li><?php echo $html->link(__l('Abuse Reports'), array('controller' => 'abuse_reports', 'action' => 'index', 'product_id' => $product['Product']['id'], 'simple_view' => 1, 'admin' => true), array('escape' => false)); ?></li>
                <li><?php echo $html->link(__l('Spam Reports'),  array('controller' => 'spam_reports', 'action' => 'index', 'product_id' => $product['Product']['id'], 'simple_view' => 1,'admin' => true), array('escape' => false)); ?></li>
                <li><?php echo $html->link(__l('Seller Contacts'),  array('controller' => 'contact_sellers', 'action' => 'index', 'product_id' => $product['Product']['id'], 'simple_view' => 1,'admin' => true), array('escape' => false)); ?></li>
            </ul>
        </div>
        </div>
        <?php
    endif;

?>
 </div>
<div class="main-bl">
<div class="main-br">
  <div class="main-bm"> </div>
</div>
</div>
<!--
<p class="view-page-info">
	<?php //echo __l('View this page in ').$html->link(__l('XML'), array('controller' => 'products', 'action' => 'v', 'slug' => $product['Product']['slug'],'view_type' => ConstViewType::NormalView, 'ext' => 'xml'), array('target' => '_blank','escape' => false)); ?>
</p> -->