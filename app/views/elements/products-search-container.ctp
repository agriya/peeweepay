<?php
	echo $form->create('Product', array('type' => 'get', 'class' => 'search search-block clearfix js-search-map', 'action'=>'index'));
	if($this->params['controller'] == 'products' && $this->params['action'] == 'index'):
		if(!isset($this->params['named']['type'])){
?>
 <div class="map-block" id ="js-map"></div>
 <div class="extra-search-block clearfix">
 <div class=" clearfix">
	<?php
		echo $form->input('q', array('type' => 'hidden'));
		echo $form->input('sw_latitude', array('type' => 'hidden', 'id' =>'sw_latitude'));
		echo $form->input('sw_longitude', array('type' => 'hidden', 'id' =>'sw_longitude'));
		echo $form->input('ne_latitude', array('type' => 'hidden', 'id' =>'ne_latitude'));
		echo $form->input('ne_longitude', array('type' => 'hidden', 'id' =>'ne_longitude'));

		echo $form->input('latitude', array('type' => 'hidden', 'id' =>'product_latitude'));
		echo $form->input('longitude', array('type' => 'hidden', 'id' =>'product_longitude'));
		echo $form->input('zoom_level', array('type' => 'hidden', 'id' =>'product_zoom_level', 'value' =>'1'));
		echo $form->input('product_search', array('type' => 'hidden', 'value' =>'1'));
	  ?>
  <div class="clearfix personal">
 <?php echo $form->input('location', array('label' =>false, 'id' => 'address'));?>
   <p class="extra-search-option">
	 <?php echo $html->link(__l('extra options'), '#', array('class' => 'js-search-extra-option', 'title' => __l('extra options'))); ?>
  </p>
	 <?php echo $form->input('extra_options', array('type' => 'hidden', 'id' =>'extra_options','class'=>'extra-search-option'));
 ?>

 </div>
 </div>
 <div class="extra-search-inner-block clearfix" id="extra_option_block">
  <h3><?php echo __l('Show only products filtered by the following options:');?></h3>
  <div class="on-off-block clearfix">
  <div class="clearfix">
	  <?php echo $form->input('currency_id', array('label' => __l('currency'), 'empty'=>__l('all (*)'))); ?>
	 <span class="js-on-off on-off on-off-top">
		 <?php echo $form->input('is_file', array('type' => 'checkbox', 'label' => __l('must have a file'), 'div' =>false)); ?>
	 </span>
	 <span class="js-on-off on-off">
		   <?php  echo $form->input('is_image', array('type' => 'checkbox', 'label' => __l('must have image(s)'), 'div' =>false)); ?>
		</span>
		<div class="js-price_filter-checkbox">
			<span class="on-off">
				<?php echo $form->input('is_price_filter', array('type' => 'checkbox',  'label' => __l('enable price filter'), 'div' => false, 'id'=>'on_off_on')); ?>
		  </span>
	   </div>
	 </div>
	 <div class="js-price_filter price-filter-block">
		 <div class="clearfix">
		<div class="min-price-block">
			 <?php echo $form->input('min_price', array('id' => 'min_price', 'class' => 'js-min-price-select-slider', 'type' => 'text', 'label' => __l('min price: <span id="select_min_price"> '. $this->data['Product']['min_price'].' </span>'))); ?>
		</div>
		<div class="min-price-block">
			<?php echo $form->input('max_price', array( 'id' => 'max_price', 'class' => 'js-max-price-select-slider', 'type' => 'text', 'label' => __l('max price: <span id="select_max_price"> '.$this->data['Product']['max_price'].' </span>'))); ?>
		</div>
		 </div>
	 </div>
</div>
<div class="search-submit-block clearfix">
 <?php
 echo $form->submit(__l('filter'));
 ?>
 </div>
 </div>
</div>
<script type="text/javascript">
	var extra_options = "<?php echo empty($this->data['Product']['extra_options']); ?>";
	$(document).ready(function(){
		if(extra_options == 1){
		   $('#extra_option_block').addClass('hide');
		}
		else{
		   $('#extra_option_block').removeClass('hide');

		}
	});

</script>
<?php } endif;
?>
<?php  echo $form->end();  ?>