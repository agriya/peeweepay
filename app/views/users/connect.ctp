<div class="dashboard-block">
<h2><?php echo __l('Dashboard'); ?></h2>
<p class="manage-block clearfix">
	<?php
	   echo $html->link(__l('Manage'), array('controller' => 'users', 'action' => 'dashboard', $this->data['User']['id']), array('escape' => false));
	?>
	<span>
	<?php 
	   echo __l('Connect');
	?>
    </span>
	<?php
	   echo $html->link(__l('Profile Image'), array('controller' => 'users', 'action' => 'profile_image', $this->data['User']['id']), array('escape' => false));
	?>
 </p>
<h3><?php echo sprintf(__l('Allow').' %s '.__l(' to share your products'),Configure::read('site.name')); ?></h3>

<?php echo $form->create('User', array('action' => 'update', 'class' => 'normal js-upadte-setting-form'));
      
	  echo $form->input('UserSetting.id', array('type' => 'hidden'));
?>

<?php if(empty($this->data['User']['fb_access_token'])): ?>
<div class="dashboard-inner-block">
<h4><?php echo __l('Connect to Facebook'); ?></h4>
<p>
  <?php echo __l('Connect your Facebook account to automatically push status updates about your product in your news stream.');?>
</p>
<div class="connect-link-block clearfix">
  <?php
	   echo $html->link(__l('Connect'), $fb_login_url);
	?>
	</div>

</div>
<?php else: ?>
    <div class="dashboard-inner-block">
        <h4><?php echo __l('Update my Facebook status when'); ?></h4>
            <?php
            echo $form->input('UserSetting.fb_status_new_product', array('class' =>'js-update-user-settings', 'type' => 'checkbox', 'label' => __l('I have placed a new product.') ) );
            echo $form->input('UserSetting.fb_status_product_sold', array('class' =>'js-update-user-settings', 'type' => 'checkbox', 'label' => __l('A product has been sold.')));
            echo $form->input('UserSetting.fb_status_product_not_sold', array('class' =>'js-update-user-settings', 'type' => 'checkbox', 'label' => sprintf(__l('A product has not been sold after').' %d '.__l('days.'), Configure::read('product.product_not_sold_days'))));
            ?>
            <div class="connect-link-block clearfix">
            <?php
            	   echo $html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->data['User']['id'], 'type' => 'facebook', 'c_action' =>'disconnect'), array('escape' => false, 'class' => 'js-delete'));
        	?>
        	</div>
    </div>
<?php endif; ?>
<?php if(empty($this->data['User']['twitter_access_token'])): ?>
    <div class="dashboard-inner-block">
        <h4><?php echo __l('Connect to Twitter'); ?></h4>
        <p>
          <?php echo __l('Connect your Twitter account to automatically push status updates about your product to your followers.');?>
        </p>
        <div class="connect-link-block clearfix">
          <?php
        	   echo $html->link(__l('Connect'), array('controller' => 'users', 'action' => 'connect', $this->data['User']['id'], 'type' => 'twitter'), array('escape' => false));
        	?>
        </div>
    </div>
<?php else: ?>
    <div class="dashboard-inner-block">
        <h4><?php echo __l('Tweet when'); ?></h4>
            <?php

            echo $form->input('UserSetting.twitter_status_new_product', array('class' =>'js-update-user-settings', 'type' => 'checkbox', 'label' => __l('I have placed a new product.')) );
            echo $form->input('UserSetting.twitter_status_product_sold', array('class' =>'js-update-user-settings', 'type' => 'checkbox', 'label' => __l('A product has been sold.')));
            echo $form->input('UserSetting.twitter_status_product_not_sold', array('class' =>'js-update-user-settings', 'type' => 'checkbox', 'label' =>sprintf(__l('A product has not been sold after').' %d '.__l('days.'), Configure::read('product.product_not_sold_days'))));
            ?>
            <div class="connect-link-block clearfix">
            <?php
            	   echo $html->link(__l('Disconnect'), array('controller' => 'users', 'action' => 'connect', $this->data['User']['id'], 'type' => 'twitter', 'c_action' =>'disconnect'), array('escape' => false, 'class' => 'js-delete'));
        	?>
        	</div>
    	</div>
<?php endif; ?>
<?php echo $form->end();?>
</div>