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
	<title><?php echo Configure::read('site.name');?> | <?php echo $html->cText($title_for_layout, false);?></title>
	<?php
		echo $html->meta('icon'), "\n";
		echo $html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $html->meta('description', $meta_for_layout['description']), "\n";
		$html->css('reset', null, null, false);
		$html->css('style', null, null, false);
		if (isset($javascript)){
			$javascript->link('libs/jquery', false);
			$javascript->link('common', false);
		}
		echo $asset->scripts_for_layout();
	?>
</head>
<body>
	<div id="<?php echo $html->getUniquePageId();?>" class="content">
		<div id="header">
			<h1><?php echo $html->link(Configure::read('site.name'), '/');?></h1>
		</div>
		<div id="sub-header">
              <ul>
    			<li><?php echo $html->link(__l('Home'), '/');?></li>
    			<li><?php echo $html->link(__l('About'), array('controller' => 'pages', 'action' => 'display', 'about'));?></li>
              </ul>
		</div>
		<div id="main">
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
			<?php echo $content_for_layout;?>
			
		</div>
		<div id="footer">
			<p>&copy;<?php echo date('Y');?> <?php echo $html->link(Configure::read('site.name'), '/');?>. <?php echo __l('All rights reserved');?>.</p>
			<p>
			<?php echo $html->link(
							$html->image('powered-by-agriya.png', array('alt'=> __l('[Image: Agriya]'), 'title' => __l('Powered by Agriya'))),
							'http://www.agriya.com/',
							array('target' => '_blank', 'title' => 'Powered by Agriya'), null, false
						);
			?>
			<?php echo Configure::read('site.version');?>
			</p>
		</div>
	</div>
	<?php echo $this->element('site_tracker');?>
	<?php echo $cakeDebug?>
</body>
</html>
