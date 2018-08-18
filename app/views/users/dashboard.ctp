<div class="clearfix">
<h2><?php echo __l('Dashboard'); ?></h2>
<p class="manage-block clearfix">
<span>
	<?php 
	   echo __l('Manage');
	?>
	</span>
	<?php
	   echo $html->link(__l('Connect'), array('controller' => 'users', 'action' => 'connect', $user['User']['id']), array('escape' => false));
	?>
	<?php
	   echo $html->link(__l('Profile Image'), array('controller' => 'users', 'action' => 'profile_image', $user['User']['id']), array('escape' => false));
	?>
 </p>
<h3><?php echo __l('Manage all your products'); echo ' ('. $user['User']['product_verified_count'] . ')'; ?></h3>
<?php echo $this->element('products-index', array('user_id' => $user['User']['id'], 'cache' => array('time' => '30'))); ?>
</div>