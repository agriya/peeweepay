<?php
/* SVN FILE: $Id: default.ctp 7805 2008-10-30 17:30:26Z AD7six $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<?php echo $html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo sprintf(__l('Admin - %s'), $html->cText($title_for_layout, false)); ?></title>
	<?php
		echo $html->meta('icon'), "\n";
		echo $html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $html->meta('description', $meta_for_layout['description']), "\n";
		require_once('_head.inc.ctp');
		echo $asset->scripts_for_layout();
	?>
		
	<!--[if IE]><?php echo $javascript->link('libs/excanvas'); ?><![endif]-->    
	<?php echo $javascript->link('libs/jquery.bt.min');  ?>
	<?php echo $javascript->link('libs/jquery.hoverIntent.minified');  ?>
	
</head>
<body class="admin">
	<div id="<?php echo $html->getUniquePageId();?>" class="content admin-content">
		<div id="header" class="clearfix">
		   <div class="header-content clearfix container_12">
			<h1><?php echo $html->link(Configure::read('site.name'), '/');?></h1>
              <p class="caption"><?php echo __l('Quickly build store, quickly sell through social media'); ?></p>
			 <div class="admin-bar clearfix">
                <div class="clearfix"><h3><?php echo __l('Current time').': '; ?></h3><?php echo $html->cTime(date(Configure::read('site.datetime.format'))); ?></div>
        		<div class="clearfix"><h3><?php echo __l('Last login').': '; ?></h3><?php echo $html->cDateTimeHighlight($auth->user('last_logged_in_time')); ?></div>
    		</div>
			<div id="sub-header">
			 <p><?php echo __l('Welcome').', '.$html->link($auth->user('username'), array('controller' => 'users', 'action' => 'splashboard', 'admin' => true),array('title' => $auth->user('username'))); ?></p>
				<ul>
				    <li><?php echo $html->link(__l('Home'), '/', array('title' => __l('Home')));?></li>
					<li><?php echo $html->link(__l('Change Password'), array('controller' => 'users', 'action' => 'change_password'), array('title' => __l('Change Password')));?></li>
					<li><?php echo $html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></li>
				</ul>
			</div>
			</div>
		</div>
		<div id="main" class="clearfix">
		    <div class="main-inner container_12">
		       <div class="main-tl">
                  <div class="main-tr">
                    <div class="main-tmid"> </div>
                  </div>
            </div>
          
			<?php
        		if ($session->check('Message.error')):
        				$session->flash('error');
        		endif;
        		if ($session->check('Message.success')):
        				$session->flash('success');
        		endif;
				if ($session->check('Message.flash')):
						$session->flash();
				endif;
			?>
			  <div class="main-content-form clearfix">
                <div class="admin-sideone round-5">
                    <?php
                        echo $this->element('admin-sidebar', array('cache' => array('time' => '120')));
                    ?>
                </div>
                <div class="admin-sidetwo round-5">
                	<?php echo $content_for_layout;?>
    			</div>
			</div>
			 <div class="main-bl">
                <div class="main-br">
                  <div class="main-bm"> </div>
                </div>
          </div>
			</div>
		</div>
		<div id="footer">
            <div class="footer-inner container_12 clearfix">
    			<div id="agriya" class="clearfix">
    				<p>&copy;<?php echo date('Y');?> <?php echo $html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
    				<p class="powered clearfix"><span><a href="http://peeweepay.dev.agriya.com/" title="<?php echo __l('Powered by PeeweePay');?>" target="_blank" class="powered"><?php echo __l('Powered by PeeweePay');?></a>,</span> <span><?php echo __l('made in'); ?></span> <?php echo $html->link(__l('Agriya Web Development'), 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
    				<p><?php echo $html->link(__l('CSSilized by CSSilize'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
    	       	</div>
		</div>
	</div>
	</div>
	<?php echo $this->element('site_tracker');?>
	<?php echo $cakeDebug?>
</body>
</html>
