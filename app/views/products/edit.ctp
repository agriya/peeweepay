<div class="dollar clearfix">
        <div class="dollar-tr">
          <div class="dollar-tm">
            <h2><?php echo __l('Edit your product page'); ?></h2>
            <div class="buy-block">
              <p class="price price1">
                <?php echo $html->link(__l('How it works?'), array('controller' => 'pages', 'action' => 'view', 'how_it_works'), array('class' => 'js-how-it-works-colorbox', 'title' =>__l('How it works'))); ?>
              </p>
            </div>
          </div>
        </div>
      </div>
<div class="product-info-block product-add-block clearfix">
<?php echo $form->create('Product', array('class' => 'normal js-product-map','enctype' => 'multipart/form-data'));
     echo $form->input('id', array('type'=>'hidden'));
     echo $form->input('User.id', array('type'=>'hidden'));
?>
  <div class="clearfix">
    <div class="side1-add">
      <div class="quantity-block clearfix"> <span class="quantity-info"><?php echo __l('Quantity'); ?></span>
        <div class="link-block clearfix">
         <?php echo $form->input('quantity_type', array('label' => __l('Qty:'), 'class'=>'js-editable-combo js-quantity-type  tool-tip', 'title'=>__l('Select a type of quantity')));
		       echo $form->input('quantity', array('label' => false, 'class' => 'tool-tip','div'=>'js-quantity input text hide', 'id' => 'quantity', 'title'=>__l('Enter the stock amount (only numbers)')));
		 ?>
         </div>
      </div>
        <div class="description">
      <div class="head round-5 clearfix">
	      <h2><?php echo __l('Description');?></h2>
         <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('Enter a description').' <br/>'.__l('* Required. Use the toolbar buttons to markup parts of your text.') ));?></span>
        </div>
      <div class="js-overlabel"><?php echo $form->input('description', array('class' =>'js-markitup', 'label'=>__l('Enter a description for your product *'), 'div' => false)); ?></div>
      </div>
        <div class="title tags clearfix">
	<div class="head round-5 clearfix">
	  <h2 class="tool-tip" title="<?php echo __l('Tags (keywords)').' <br/>'.__l('Add up to 20 tags. Use at least 3 characters. Tags cannot contain spaces or non-word characters, only [a-z][A-Z[0-9] are allowed.');?>"><?php echo __l('Tags'); ?></h2>
	  <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('Tags (keywords)').' <br/>'.__l('Add up to 20 tags. Use at least 3 characters. Tags cannot contain spaces or non-word characters, only [a-z][A-Z[0-9] are allowed.') ));?></span> </div>
	<div class="clearfix">
	   <ul id="mytags" class="js-tags"><li></li></ul>
	  <?php echo $form->input('tag', array('type' =>'hidden', 'id' => 'prefill_tag')); ?>
	</div>
    </div>
        <div class="head round-5 clearfix">
	  <h2 class="tool-tip" title="<?php echo __l('More options').' <br/>'.__l('Specify more options.');?>"><?php echo __l('More options'); ?></h2>
	  <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('More options').' <br/>'.__l('Specify more options.') ));?></span> </div>
	    <div class="clearfix">
	  <ol class="option-list">
		<li><?php echo __l('Display page views'); ?> <span class="js-on-off"><?php echo $form->input('is_display_page_views', array('label' =>false, 'div'=>false));?></span></li>
		<li><?php echo __l('Display product quantity'); ?> <span class="js-on-off"><?php echo $form->input('is_display_quantity', array('label' =>false, 'div'=>false));?></span></li>
		<li><?php echo __l('Include in search results'); ?> <span class="js-on-off"><?php echo $form->input('is_include_search', array('label' =>false, 'div'=>false));?></span></li>
	  </ol>
	</div>
        <ol class="product-desc-list clearfix">
        <li>
          <div class="requirement-block clearfix">
            <div class="seller-tl">
              <div class="seller-tr">
                <div class="seller-tm"> </div>
              </div>
            </div>
            <div class="seller-center add-center clearfix">
              <div class="requirement-left">
              <div class="clearfix">
                <p class="requirement-left-block">
                <?php echo $html->link(__l('Require shipment address from buyer'),'#', array('class'=>'js-shipment tool-tip ', 'title' =>__l('click here to specify the shipment cost for each contries'))); ?></p>
		        <span class="js-shipment-checkbox req-options"><?php echo $form->input('is_shipment_cost_required', array('label' => false, 'div' => false, 'id'=>'on_off_on')); ?></span>
            </div>
                <div class="js-shipment-container clearfix shipment-container round-10 <?php echo (empty($this->data['Product']['is_shipment_cost_required'])) ? 'hide' :'' ?>">
	      <p><?php echo __l('Your buyers will now be required to enter their shipment address when they purchase your item.'); ?></p>
		  <p><?php echo __l('Specify shipment details (optional)'); ?></p>
		  <div class="new-block-add clearfix">
                <div class="js-clone add-form-block clearfix">
                <?php
                    $count = (!empty($this->data['ProductShipmentCost'])) ? count($this->data['ProductShipmentCost']) : 1;
                    for($i = 0; $i < $count; $i++) :
                        ?>
                        <div class="js-field-list clearfix">
                            <?php echo $form->input('ProductShipmentCost.'.$i.'.grouped_country_id', array('id' => 'product_ship_country'.$i, 'label' =>false, 'options' => $shipCountries, 'empty' => __l('Select a region or country'), 'class' =>'js-ship-country')); ?>

                            <div class=" input js-overlabel"><?php echo $form->input('ProductShipmentCost.'.$i.'.shipment_cost', array('class' => 'js-ship-cost', 'id' => 'product_ship_cost'.$i)); ?></div>
							<?php if($i >0): ?>
							<p class="press-link delete"> <?php echo $html->link(__l('Remove'), '#', array('class' => 'js-remove-clone delete'));?></p>
`							<?php endif; ?>
                        </div>
                        <?php
                    endfor;
            	?>
                </div>
                <p class="add"> <?php echo $html->link(__l('Add more'), '#', array('class' => 'js-addmore add'));?></p>
			</div>

            <div class ="clearfix">
          <div class="ship-charge"><?php echo __l('Charge shipping costs per').': '; echo $form->input('is_shipment_cost_per_item_or_order', array('legend' => false, 'type' => 'radio', 'options' => $shipment_costs,'div' =>false)); ?></div>
</div>
          <p class= 'js-ship-info'></p>
	</div>
                  </div>
                  </div>
            <div class="seller-bl">
              <div class="seller-br">
                <div class="seller-bm"> </div>
              </div>
            </div>
          </div>
        </li>
        <li>
          <div class="requirement-block clearfix">
            <div class="seller-tl">
              <div class="seller-tr">
                <div class="seller-tm"> </div>
              </div>
            </div>
            <div class="seller-center add-center clearfix">
              <div class="clearfix">
                <div class="requirement-left">
                  <p>
                    <?php echo $html->link(__l('Attach a file for your buyers to download'),'#', array('class'=>'js-file-upload tool-tip', 'title' =>__l('click here to specify a digital download for your item'))); ?>
            	    <span class="js-file-checkbox req-options"><?php echo $form->input('is_file', array('label' => false, 'div' => false, 'id'=>'on_off_on')); ?></span>
                  </p>
                </div>
                </div>
              <div class="js-file-container shipment-container round-10 clearfix <?php echo (empty($this->data['Product']['is_file'])) ? 'hide' :'' ?>">
	     <p><?php echo __l('Please upload only if:'); ?></p>
		<ul>
		<li><?php echo sprintf(__l('- The file size is less than').' %s %s', Configure::read('product.file.allowedSize'),  Configure::read('product.file.allowedSizeUnits'));?></li>
		<li><?php echo __l('- The file complies with our').' '; echo $html->link(__l('acceptable use policy'), array('controller' => 'pages', 'action' => 'view' , 'aup'), array('title' =>__l('acceptable use policy'), 'target' => '_blank')); ?></li>
		<li><?php echo __l('- You own full copyright and rights to distribute and sell this file'); ?></li>
		</ul>
		<div class="clearfix">
		<?php if(!empty($this->data['Attachment'])):     
			if(!empty($this->data['Attachment']['id'])):
				echo $form->input('OldAttachment.id', array('type' => 'checkbox', 'label' => __l('Delete?')));
				echo $form->input('Attachment.id', array('type'=>'hidden', 'value'=>$this->data['Attachment']['id']));
			endif;
			if(!empty($this->data['Attachment']['filename'])):
                echo $html->cText('('.$this->data['Attachment']['filename'].')');
            endif;
        endif;?>
		</div>
    	<div class="flashUploader flash clearfix"><?php echo $form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Upload Product File'))); ?></div>
	   </div>
            </div>
            <div class="seller-bl">
              <div class="seller-br">
                <div class="seller-bm"> </div>
              </div>
            </div>
          </div>
        </li>
      </ol>
    </div>
    <div class="side2-add">
      <div class="title-block admin-title-block1">
        <div class="clearfix">
        <div class="title-input-blocks title-input-blocks1 js-overlabel">
        <div class="js-overlabel"><?php echo $form->input('title', array('label' => __l('Enter a title for your product *') ,'class'=>'')); ?></div>
        </div>
        <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('Enter a title for your product').' <br/>'.__l('* Required. Use at least 3 characters. Only [a-z][A-Z[0-9] are allowed.') ));?></span>

        </div>
        <p><span class='tool-tip' title="<?php echo __l('Display of the ').Configure::read('site.name').__l(' fee::(').Configure::read('site.fee').__l('% with a minimum of .').Configure::read('site.site_min_fee').')';?>"><?php echo $form->input('site_min_fee', array('value'=>Configure::read('site.site_min_fee'), 'type'=>'hidden', 'id'=>'site_min_fee')); echo $form->input('site_fee', array('value'=>Configure::read('site.fee'), 'type'=>'hidden', 'id'=>'site_fee')); echo sprintf(__l('our %s sales fee: '), Configure::read('site.name'));?><span id='fee_amount'><?php echo sprintf(__l('%s'), $this->data['Product']['site_fee_amount']);?></span><span id='fee_currency'></span><?php echo '('.Configure::read('site.fee').
		'%)'; ?></span></p>
      </div>


      <div class="flashUploader flash clearfix">
        <div class="seller-tl">
          <div class="seller-tr">
            <div class="seller-tm"> </div>
          </div>
        </div>
        <div class="seller-center add-center clearfix">
        <div class="head clearfix">
        <h2><?php echo __l('Add Images'); ?></h2>
   <span><a title="" class="tool-tip" href="#" bt-xtitle="Pictures or images &lt;br/&gt;Add up to 3 images. The first image will be the main image.">?</a></span>
        </div>


        <div class="picture">
    <ol class=" upload-list clearfix">

	   <?php
	   for($i = 0; $i<Configure::read('product.max_upload_photo'); $i++):
	     ?>
		 <li>
		<div class="product-img">
		<?php
			if(!empty($this->data['ProductPhoto'][$i]['Attachment'])):
				$old_attachment = (!empty($this->data['ProductPhoto'][$i]['filename'])) ? '1' :'';
				echo $form->input('ProductPhoto.'.$i.'.OldAttachment.id', array('value'=>$old_attachment, 'id' =>'old_attachment'.$i, 'type' => 'hidden', 'label' => false));
				echo $form->input('ProductPhoto.'.$i.'.Attachment.id', array('type'=>'hidden', 'value'=>$this->data['ProductPhoto'][$i]['Attachment']['id']));

			endif;
			echo $form->uploader('ProductPhoto.Attachment.'.$i.'.filename', array('id' =>'ProductPhoto.Attachment.'.$i.'.filename', 'type'=>'file', 'uPreview' => '1', 'uFilecount'=>1, 'uController'=> 'products', 'uId' => 'ProductImage'.$i.'',  'uFiletype' => Configure::read('product_image.file.allowedExt')));
			echo $form->input('ProductPhoto.'.$i.'.id', array('type' => 'hidden'));
          ?>
		  <span class="product-image-preview" id="preview_image<?php echo $i?>">
			<?php
				if(!empty($this->data['ProductPhoto'][$i]['Attachment'])):
				   if(!empty($this->data['ProductPhoto'][$i]['filename'])):
					  $thumb_url = Router::url(array(
						'controller' => 'products',
						'action' => 'thumbnail',
						 session_id(),
						 $this->data['ProductPhoto'][$i]['filename'],
						'admin' => false
					) , true);
				   ?>
				   <img src="<?php echo $thumb_url; ?>" /><input type="hidden" name="data[ProductPhoto][<?php echo $i; ?>][filename]" value="<?php echo $this->data['ProductPhoto'][$i]['filename']; ?>" />
				   <?php
				   else:
					 $product_photo_title  = (!empty($this->data['ProductPhoto'][$i]['caption'])) ? $this->data['ProductPhoto'][$i]['caption'] : $this->data['Product']['title'];
					echo $html->showImage('ProductPhoto', $this->data['ProductPhoto'][$i]['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($product_photo_title, false)), 'title' => $html->cText($product_photo_title , false))); ?>
				<?php
					endif;
					?>
					   <a href="#" class="js-preview-close {id:<?php echo $i ?>}">&nbsp;</a>
					<?php
				endif;
				?>
		  </span>
		   </div>
		   <div class="js-overlabel">
		     <?php  echo $form->input('ProductPhoto.'.$i.'.caption', array('label' => __l('Caption'))); ?>
            </div>
            </li>
		  <?php
        endfor;
			?>
	</ol>
	</div>
    </div>
        <div class="seller-bl">
          <div class="seller-br">
            <div class="seller-bm"> </div>
          </div>
        </div>
      </div>

      <div class="add-block location-add-block clearfix">
        <div class="head round-5 clearfix">
	  <h2 class="tool-tip" title="<?php echo __l('Product geo location').' <br/>'.__l('Specify a location for your product on the map. Find an address by entering it in the field or drag the location-icon on the map.'); ?>"><?php echo __l('location'); ?></h2>
	  <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('Product geo location').' <br/>'.__l('Specify a location for your product on the map. Find an address by entering it in the field or drag the location-icon on the map.') ));?></span> </div>
	<div class="clearfix">
	   <div class="js-overlabel"><?php echo $form->input('address', array('class' => 'js-search-product-location' , 'id' => 'product_address','label' =>__l('Type an address or location and press [enter] to find it on the map')));
	  ?></div>
	  <?php
	    echo $form->input('latitude', array('type' => 'hidden', 'id' =>'product_latitude'));
		echo $form->input('longitude', array('type' => 'hidden', 'id' =>'product_longitude'));
		echo $form->input('zoom_level', array('type' => 'hidden', 'id' =>'product_zoom_level'));
	  ?>
	</div>
      </div>
      <div class="map-block" id="js-map"></div>
	<div class="personal clearfix">
	<div class="head round-5 clearfix">
	  <h2 class="tool-tip" title="<?php echo __l('Personal information').' <br/>'.__l('* Required. Enter your name, e-mail address and country.');?>"><?php echo __l('Personal info'); ?></h2>
	  <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('Personal information').' <br/>'.__l('* Required. Enter your name, e-mail address and country.') ));?></span> </div>
	<div class="personal-block clearfix">
	  <div class="avatar clearfix">
	   <?php
			if($this->data['User']['profile_image_id'] == ConstProfileImage::Twitter){
				if(!empty($this->data['User']['twitter_avatar_url'])){
					echo $html->image($this->data['User']['twitter_avatar_url'], array('title' => $html->cText($this->data['User']['fullname'], false)));
				}
			}
			elseif($this->data['User']['profile_image_id'] == ConstProfileImage::Facebook){
				if(!empty($this->data['User']['fb_user_id'])){
					echo $html->image('http://graph.facebook.com/'.$this->data['User']['fb_user_id'].'/picture?type=small', array('title' => $html->cText($this->data['User']['fullname'], false)));
				}
			}
			elseif($this->data['User']['profile_image_id'] == ConstProfileImage::Upload){
				echo $html->showImage('UserAvatar', $this->data['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($this->data['User']['fullname'], false)), 'title' => $html->cText($this->data['User']['fullname'], false)), null, null, false);
			}
			else{
				echo $gravatar->image($this->data['User']['email'], array('size' => '55', 'default' => 'identicon', 'title' => $html->cText($this->data['User']['fullname'], false))); ?>
				<p class="change-link">
				<?php echo $html->link(__l('change'), array('controller' => 'users', 'action' => 'profile_image', $this->data['User']['id'], 'admin' => false), array('title' => __l('change profile image'), 'target' => '_blank')); ?>
				</p>
				<?php
			}
	  ?>
      </div>
	  <div class="clearfix personal-edit-block">
		<div class="js-overlabel"><?php echo $form->input('User.fullname', array('label' =>__l('Enter your name *'))); ?></div>
		<div class="js-overlabel"><?php echo $form->input('User.email', array('label' =>__l('Enter your PayPal e-mail address *'))); ?></div>
		<?php echo $form->input('User.country_id', array('empty' => __l('Please Select'), 'class' =>''));	?>
	  </div>
	</div>
  </div>
<?php	if(!$auth->sessionValid() || $auth->user('user_type_id') != ConstUserTypes::Admin):	?>
  <div class="captcha">
	<div class="head round-5 clearfix">
	  <h2 class="tool-tip" title="<?php echo __l('Captcha').' <br/>'.__l('* Required. Please type the two words in the image to verify that you are human.');?>"><?php echo __l('captcha'); ?></h2>
	  <span><?php echo $html->link(__l('?'), '#', array('class' =>'tool-tip', 'title' =>__l('Captcha').' <br/>'.__l('* Required. Please type the two words in the image to verify that you are human.') ));?></span></div>
	<div class="captcha-img">
		<div class="input captcha-block clearfix js-captcha-container">
    			<div class="captcha-left">
    	           <?php echo $html->image($html->url(array('controller' => 'users', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>
    	        </div>
    	        <div class="captcha-right">
        	        <?php echo $html->link(__l('Reload CAPTCHA'), '#', array('class' => 'js-captcha-reload captcha-reload', 'title' => __l('Reload CAPTCHA')));?>
	               <div>
		              <?php echo $html->link(__l('Click to play'), Router::url('/', true)."flash/securimage/play.swf?audio=". $html->url(array('controller' => 'users', 'action'=>'captcha_play'), true) ."&amp;bgColor1=#777&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5&amp;height=19&amp;width=19", array('class' => 'js-captcha-play')); ?>
			      </div>
    	        </div>
            </div>
        <?php echo $form->input('User.captcha', array('label' => '')); ?>

	</div>
  </div>
  <?php endif; ?>
    </div>
  </div>
<div class="selling-block">
  <div class="clearfix"><div class="start"><?php echo $form->submit(__l('update now'));?></div></div>
</div>
<?php echo $form->end();?>
</div>