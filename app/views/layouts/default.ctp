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
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<?php echo $html->charset(), "\n";?>
<title><?php echo Configure::read('site.name');?> | <?php echo $html->cText($title_for_layout, false);?></title>
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
	
	<?php if(!empty($meta_for_layout['product_name'])):?>
	<?php
		// For other than Facebook (facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)), wrap it in comments for XHTML validation...
		if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false):
			echo '<!--', "\n";
		endif;
	?>
		<meta content="<?php echo Configure::read('facebook.app_id');?>" property="og:app_id" />
		<meta content="<?php echo Configure::read('facebook.app_id');?>" property="fb:app_id" />
		<meta property="og:title" content="<?php echo $meta_for_layout['product_name'];?>"/>
		<meta property="og:site_name" content="<?php echo Configure::read('site.name'); ?>"/>
		<meta property="og:url" content="<?php echo $meta_for_layout['product_url'];?>"/>
		<meta property="og:image" content="<?php echo $meta_for_layout['product_image'];?>"/>

		<?php
			if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false):
				echo '-->', "\n";
			endif;
			// <--
		?>
	<?php endif;?>
<?php
	echo $html->meta('rss', array(
		'controller' => 'products',
		'action' => 'index',
		'ext' => 'rss'
	) , array(
		'title' => 'RSS Feeds',
	));
?>
</head>
<body>
<div id="<?php echo $html->getUniquePageId();?>" class="content">
<div id="header">
    <div class="header-content clearfix container_12">
      <div class="header-logo-block grid_4">
        <h1><?php echo $html->link(Configure::read('site.name'), '/', array('title' => Configure::read('site.slogan'), 'class' => 'tool-tip'));?></h1>
            <p class="caption"><?php echo Configure::read('site.slogan'); ?></p>
       
    
      </div>  
	  <?php
			 if($auth->sessionValid() && $auth->user('user_type_id') == ConstUserTypes::Admin):
				?>
				<div class="admin-bar">
					<h3><?php echo __l('You are logged in as '); ?><?php echo $html->link(__l('Admin'), array('controller' => 'users' , 'action' => 'splashboard' , 'admin' => true), array('title' => __l('Admin'))); ?></h3>
					<div><?php echo $html->link(__l('Logout'), array('controller' => 'users' , 'action' => 'logout', 'admin' => true), array('title' => __l('Logout'))); ?></div>
				</div>
			   <?php
			endif;
		?>
      <div class="header-r grid_8 alpha omega">
      <div class="clearfix">
        <?php
                    $languages = $html->getLanguage();
                    if(Configure::read('site.is_allow_user_to_switch_language') && !empty($languages)) :
                        echo $form->create('Language', array('action' => 'change_language', 'class' => 'language-form'));
                        echo $form->input('language_id', array('class' => 'js-autosubmit', 'options' => $languages,'value' => isset($_COOKIE['CakeCookie']['user_language']) ?  $_COOKIE['CakeCookie']['user_language'] : Configure::read('site.language')));
                        echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url']));
                        ?>
                        <div class="hide">
                            <?php echo $form->submit(__l('Submit'));  ?>
                        </div>
                        <?php
                        echo $form->end();
                   endif;
                ?>
        <ul class="menu-items clearfix">
          <li><?php echo $html->link(__l('latest items'), array('controller' => 'products', 'action' => 'index','filter' => 'latest'), array('title' => __l('latest items'))); ?></li>
          <li><?php echo $html->link(__l('trending items'), array('controller' => 'products', 'action' => 'index','filter' => 'trending'), array('title' => __l('trending items'))); ?></li>
        </ul>
        
        </div>
   
      <div class="login-block">
          <?php echo $this->element('products-search'); ?>
        </div>
      </div>
    </div>
  </div>
<div id="main">
    <div class="main-inner container_12">
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
    <?php if(isset($this->params['named']['type'])):?>
    <p class="start clearfix"><span class="seller"><?php echo __l('Start Selling the products within 60 seconds'); ?></span><span class="seller1">
    <?php echo $html->link(__l('start selling'), array('controller' => 'products', 'action' => 'add'), array('class' => "show", 'title' => __l('start selling'))); ?>
    </span></p>
    <?php endif; ?>
    <?php
    if(!($this->params['controller'] == 'products' && ($this->params['action'] == 'edit' || $this->params['action'] == 'add' || $this->params['action'] == 'v' || ($this->params['action'] == 'index' && (isset($this->params['named']['type']) && $this->params['named']['type'] == 'home'))))) : ?>
          <div class="main-tl">
                  <div class="main-tr">
                    <div class="main-tmid"> </div>
                  </div>
            </div>
        <div class="product-info-block product-add-block clearfix">
    <?php endif; ?>
	<?php if($this->params['controller'] == 'products' && $this->params['action'] == 'index' && (!isset($this->params['named']['type']) || $this->params['named']['type'] != 'home')) : ?>
		<?php echo $this->element('products-search-container'); ?>
    <?php endif; ?>

    <?php echo $content_for_layout;?>
    
    <?php if(!($this->params['controller'] == 'products' && ($this->params['action'] == 'edit' || $this->params['action'] == 'add' || $this->params['action'] == 'v' || ($this->params['action'] == 'index' && (isset($this->params['named']['type']) && $this->params['named']['type'] == 'home'))))) : ?>
      </div>
        <div class="main-bl">
            <div class="main-br">
              <div class="main-bm"> </div>
            </div>
          </div>
    <?php endif; ?>
    <?php if(isset($this->params['named']['type'])):?>
    <div class="show-more-block">
    <?php echo $html->link(__l('show more items »'), array('controller' => 'products', 'action' => 'index', 'filter' => 'latest'), array('class' => "show", 'title' => __l('show more items'))); ?>
    </div>

    <?php endif; ?>
    </div>
  </div>
<div id="footer">
    <div class="footer-inner container_12">
  
      <div class="right grid_2 alpha  omega  clearfix">
      
        <ul class="twitter clearfix">
          <li class="twit"><?php echo $html->link(__l('Twitter'), 'http://twitter.com/' . Configure::read('twitter.username'), array('target' => '_blank', 'title' => __l('Follow me on twitter'))); ?></li>
          <li class="face"><?php echo $html->link(__l('Facebook'), 'http://facebook.com/' . Configure::read('facebook.username'), array('target' => '_blank', 'title' => __l('Follow me on facebook'))); ?></li>
        </ul>
      </div>
      <div class="left grid_8 alpha  omega  clearfix">
        <ul class="about">
          <li><?php echo $html->link(__l('About Us'), array('controller' => 'pages', 'action' => 'view', 'about'), array('title' => __l('About Us'))); ?></li>
          <li><?php echo $html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'add'), array('class' =>'js-thickbox', 'title' => __l('Contact Us'))); ?></li>
          <li><?php echo $html->link(__l('Terms of Use'), array('controller' => 'pages', 'action' => 'view', 'terms'), array('title' => __l('Terms of Use'))); ?></li>
        </ul>
	      <div class="clearfix" id="agriya">
				<p>&copy;<?php echo date('Y');?> <?php echo $html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>.  <?php echo __l('All rights reserved');?>.</p>
				<p class="powered clearfix"><span><a class="powered" target="_blank" title="<?php echo __l('Powered by PeeweePay');?>" href="http://peeweepay.dev.agriya.com/"><?php echo __l('Powered by PeeweePay');?></a>,</span> <span><?php echo __l('made in'); ?></span> <?php echo $html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>   <span><?php echo Configure::read('site.version');?></span></p>
				<p><?php echo $html->link(__l('CSSilized by CSSilize'), 'http://www.cssilize.com/', array('target' => '_blank', 'title' => 'CSSilized by CSSilize', 'class' => 'cssilize'));?></p>
		</div>
      </div>
    
    </div>

  </div>
      
    </div>
<?php echo $this->element('site_tracker', array('cache' => 30, 'plugin' => 'site_tracker')); ?> <?php echo $cakeDebug?>
</body>
</html>