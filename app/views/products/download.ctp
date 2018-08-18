<?php /* SVN: $Id: $ */ ?>
	<div class="check-information-block">
<?php 
    if(!empty($error)):
	  ?>
      <h2><?php echo __l('Oops'); ?></h2>
       <p><?php echo __l('something went wrong.'); ?></p>
	   <p><?php echo __l('Are you sure you have enterted the right e-mail address? Try again:'); ?></p>
	<?php
	elseif(!empty($success)):
	  ?><h2><?php echo __l('Thanks'); ?></h2>
		<p><?php echo sprintf(__l('We have sent a new download link to').' <b>%s</b>.', $html->cText($this->data['Transaction']['sender_email'])); ?></p>
	<?php
	else:		
		?>
        <h2><?php echo __l('This download is expired'); ?></h2>
		<p><?php echo __l('It is seems that you have used this download link already. Our download links only work one time for safety reasons.'); ?></p>
		<p><?php echo __l('But hey, that is no problem, enter your e-mail address below to get a new download link '); ?></p>
	<?php
	endif;
 ?>
 </div>
<?php if(empty($success)):
      echo $form->create('Product', array('action' =>'download', 'class' => 'normal'));
      echo $form->input('Transaction.id', array('type'=>'hidden'));
	  echo $form->input('Transaction.download_count', array('type'=>'hidden'));	  
	  echo $form->input('Transaction.sender_email', array('label'=>__l('E-mail address:')));
	  echo $form->end(__l('Submit'));
      endif; 
?>