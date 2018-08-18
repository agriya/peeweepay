<?php echo $form->create('Product', array('type' => 'get', 'class' => 'search clearfix', 'id' => 'SearchForm', 'action'=>'index'));?>
 <div class="clearfix search-block">
 <div class="js-overlabel search-text-block">
 <?php
  echo $form->input('q', array('label' => __l('Start searching...'), 'id' => 'SearchQ'));?>
 </div>
 <?php  echo $form->submit(__l('Search'));?>
 </div>
<?php  echo $form->end();  ?>