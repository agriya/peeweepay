<h2><?php echo __l('Change password'); ?></h2>
<?php
	echo $form->create('User', array('action' => 'change_password' ,'class' => 'normal'));
    echo $form->input('user_id', array('type' => 'hidden'));
	echo $form->input('old_password', array('type' => 'password','label' => __l('Old password') ,'id' => 'old-password'));
    echo $form->input('passwd', array('type' => 'password','label' => __l('Enter a new password') , 'id' => 'new-password'));
	echo $form->input('confirm_password', array('type' => 'password', 'label' => __l('Confirm Password')));
	echo $form->end(__l('Change password'));
?>