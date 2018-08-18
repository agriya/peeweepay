<div class="users form">
    <h2><?php echo __l('Login'); ?></h2>
    <?php
	    echo $form->create('User', array('action' => 'login', 'class' => 'normal login-form round-10'));
    ?>
   
    <?php
		echo $form->input('username');
	?>

	<?php
	    echo $form->input('passwd');
    ?>

    <?php
        echo $form->input('User.is_remember', array('type' => 'checkbox', 'label' => __l('Remember me on this computer.')));
        $f = (!empty($_GET['f'])) ? $_GET['f'] : (!empty($this->data['User']['f']) ? $this->data['User']['f'] : (($this->params['url']['url'] != 'admin/users/login' && $this->params['url']['url'] != 'users/login') ? $this->params['url']['url'] : ''));
		if(!empty($f)) :
            echo $form->input('f', array('type' => 'hidden', 'value' => $f));
        endif;
    	echo $form->end(__l('Login'));
	?>
</div>