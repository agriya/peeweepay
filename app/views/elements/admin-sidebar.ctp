<h5 class="hidden-info"><?php echo __l('Admin side links'); ?></h5>
<ul class="admin-links">
     <?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_splashboard') ? ' class="active"' : null; ?>
	<li <?php echo $class;?>><?php echo $html->link(__l('Site Stats'), array('controller' => 'users', 'action' => 'splashboard'),array('title' => __l('Site Stats'))); ?></li>	
	<?php $class = ($this->params['controller'] == 'users' && ($this->params['action'] == 'admin_index' || $this->params['action'] == 'admin_edit')) ? ' class="active"' : null; ?>
	<li <?php echo $class;?>>
		<?php echo $html->link(__l('Users'), array('controller' => 'users', 'action' => 'index'),array('title' => __l('Users'))); ?>
	</li>
	<li>
		<span><?php echo __l('Products'); ?></span>
		<?php $class = ($this->params['controller'] == 'products') ? ' class="active"' : null; ?>
		<ul class="admin-sub-links">
			<li <?php echo $class;?>>
				<?php echo $html->link(__l('Products'), array('controller' => 'products', 'action' => 'index'),array('title' => __l('Products'))); ?>
			</li>	
			<?php $class = ($this->params['controller'] == 'abuse_reports') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Abuse Reports'), array('controller' => 'abuse_reports', 'action' => 'index'),array('title' => __l('Abuse Reports'))); ?></li>
			<?php $class = ($this->params['controller'] == 'spam_reports') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Spam Reports'), array('controller' => 'spam_reports', 'action' => 'index'),array('title' => __l('Spam Reports'))); ?></li>
        	<?php $class = ($this->params['controller'] == 'contact_sellers') ? ' class="active"' : null; ?>
        	<li <?php echo $class;?>><?php echo $html->link(__l('Seller Contacts'), array('controller' => 'contact_sellers', 'action' => 'index'),array('title' => __l('Seller Contacts'))); ?></li>
        </ul>
    </li>
	<li>
		<span><?php echo __l('Payment'); ?></span>		
		<ul class="admin-sub-links">
			<?php $class = ($this->params['controller'] == 'payment_gateways') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Payment Gateways'), array('controller' => 'payment_gateways', 'action' => 'index'), array('title' => __l('Payment Gateways')));?></li>
			<?php $class = ($this->params['controller'] == 'transactions') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Transactions'), array('controller' => 'transactions', 'action' => 'index'),array('title' => __l('Transactions'))); ?></li>
		</ul>
   </li>
   <li <?php echo $class;?>>
        <h4><?php echo __l('Tools');?></h4>
        <ul class="admin-sub-links">
            <?php $class = ($this->params['controller'] == 'pages') ? ' class="active"' : null; ?>
            <li <?php echo $class;?>><?php echo $html->link(__l('Tools'), array('controller' => 'pages', 'action' => 'display', 'tools', 'admin' => 1), array('title' => __l('Tools'), 'class' => 'admin-sidmenu')); ?></li>
        </ul>
	</li>
	<li>
		<span><?php echo __l('Masters'); ?></span>		
		<ul class="admin-sub-links">
			<?php $class = ($this->params['controller'] == 'settings') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'index'),array('title' => __l('Settings'))); ?></li>
			<?php $class = ($this->params['controller'] == 'email_templates') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Email Templates'), array('controller' => 'email_templates', 'action' => 'index'),array('title' => __l('Email Templates'))); ?></li>
			<?php $class = ($this->params['controller'] == 'pages') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l(' Manage Static Pages'), array('controller' => 'pages', 'action' => 'index', 'plugin' => NULL),array('title' => __l('Manage Static Pages')));?></li>
			<?php $class = ($this->params['controller'] == 'translations') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Translations'), array('controller' => 'translations', 'action' => 'index'),array('title' => __l('Translations'))); ?></li>
			<?php $class = ($this->params['controller'] == 'languages') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Languages'), array('controller' => 'languages', 'action' => 'index'),array('title' => __l('Languages'))); ?></li>
			<?php $class = ($this->params['controller'] == 'countries') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Countries'), array('controller' => 'countries', 'action' => 'index'),array('title' => __l('Countries'))); ?></li>					
			<?php $class = ($this->params['controller'] == 'currencies') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Currencies'), array('controller' => 'currencies', 'action' => 'index'),array('title' => __l('Currencies'))); ?></li>
		</ul>
	</li>
	<li>
		<span><?php echo __l('Diagnostics (Developer purpose only)');?></span>
		<ul class="admin-sub-links">
  			<?php $class = ($this->params['controller'] == 'users' && $this->params['action'] == 'admin_logs') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Debug & Error Log'), array('controller' => 'users', 'action' => 'logs'),array('title' => __l('Debug & Error Log'))); ?></li>
			<?php $class = ($this->params['controller'] == 'adaptive_transaction_logs') ? ' class="active"' : null; ?>
			<li <?php echo $class;?>><?php echo $html->link(__l('Adaptive Transaction Log'), array('controller' => 'adaptive_transaction_logs', 'action' => 'index'),array('title' => __l('Adaptive Transaction Log'))); ?></li>
		</ul>
	</li>
</ul>