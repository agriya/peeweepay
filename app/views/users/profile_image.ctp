<div class="dashboard-block">
<h2><?php echo __l('Profile Image'); ?></h2>
<p class="manage-block clearfix">
	<?php
	   echo $html->link(__l('Manage'), array('controller' => 'users', 'action' => 'dashboard', $this->data['User']['id']), array('escape' => false));
	?>
	<?php
	   echo $html->link(__l('Connect'), array('controller' => 'users', 'action' => 'connect', $this->data['User']['id']), array('escape' => false));
	?>
	<span>
	<?php 
	   echo __l('Profile Image');
	?>
    </span>
 </p>
<h3><?php echo __l('Choose your profile image'); ?></h3>

<?php echo $form->create('User', array('action' => 'profile_image', 'class' => 'normal',  'enctype' => 'multipart/form-data'));
      
	  echo $form->input('User.id', array('type' => 'hidden'));
?>
<div class="photo-upload-block">
<div class="photo-options">
<?php echo $form->input('User.profile_image_id', array('type' => 'radio', 'options' => $profileimages, 'legend' => false)); ?>
</div>
<div class="clearfix avatar-options">
<div class="dashboard-inner-block">
        <h4><?php echo __l('Gravatar'); ?></h4>		
		
		<?php
		echo $gravatar->image($this->data['User']['email'], array('size' => '55', 'default' => 'identicon')); ?>
		  <p class="change">
		  <?php
		echo $html->link(__l('change'), 'http://en.gravatar.com/site/login', array('title' => __l('change gravatar'), 'target' => '_blank'));
		?>
		</p>
</div>
<div class="dashboard-inner-block">
        <h4><?php echo __l('Twitter'); ?></h4>		
		<?php if(!empty($this->data['User']['twitter_avatar_url'])):
			echo $html->image($this->data['User']['twitter_avatar_url'], array('title' => __l('Twitter Profile Image')));		
		else: ?>
		<div class="connect-link-block clearfix">
			<?php
        	   echo $html->link(__l('Connect'), array('controller' => 'users', 'action' => 'connect', $this->data['User']['id'], 'type' => 'twitter'), array('escape' => false));
        	?>
		</div>
		<?php endif;?>			  
</div>
<div class="dashboard-inner-block">
        <h4><?php echo __l('Facebook'); ?></h4>		
		<?php if(!empty($this->data['User']['fb_user_id'])):
			echo $html->image('http://graph.facebook.com/'.$this->data['User']['fb_user_id'].'/picture?type=small', array('title' => __l('Facebook Profile Image')));
		else: ?>
		<div class="connect-link-block clearfix">
			<?php
			   echo $html->link(__l('Connect'), $fb_login_url);
			?>
		</div>
		<?php endif;?>				  
</div>
<div class="dashboard-inner-block dashboard-inner-block1 clearfix">
        <h4><?php echo __l('Upload'); ?></h4>
		<?php if(!empty($this->data['UserAvatar']) && !empty($this->data['UserAvatar']['id'])){
			echo $html->showImage('UserAvatar', $this->data['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $html->cText($this->data['User']['fullname'], false)),'class' => 'upload-avatar', 'title' => $html->cText($this->data['User']['fullname'], false)), null, null, false);
			}
		?>
		<?php echo $form->input('UserAvatar.filename', array('type' => 'file','size' => '33', 'label' => false,'class' =>'browse-field')); ?>
		
</div>

</div>
<?php echo $form->submit(__l('Update'));?></div>
<?php echo $form->end(); ?>
</div>