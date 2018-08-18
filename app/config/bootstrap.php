<?php
/* SVN FILE: $Id: bootstrap.php 173 2009-01-31 12:51:40Z rajesh_04ag02 $ */
/**
 * Short description for file.
 *
 * Long description for file
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
 * @subpackage    cake.app.config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 *
 * This file is loaded automatically by the app/webroot/index.php file after the core bootstrap.php is loaded
 * This is an application wide file to load any function that is not used within a class define.
 * You can also use this to include or require any files in your application.
 *
 */
/**
 * The settings below can be used to set additional paths to models, views and controllers.
 * This is related to Ticket #470 (https://trac.cakephp.org/ticket/470)
 *
 * $modelPaths = array('full path to models', 'second full path to models', 'etc...');
 * $viewPaths = array('this path to views', 'second full path to views', 'etc...');
 * $controllerPaths = array('this path to controllers', 'second full path to controllers', 'etc...');
 *
 */

Configure::load('config');
define('LICENSE_HASH', 'e9a5561se42c5a4if06c0b8v281cf43a');
if (!defined('STDIN') && !empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['SERVER_ADDR']) && str_replace('www.', '', $_SERVER['HTTP_HOST']) != 'cssilize.com' && $_SERVER['HTTP_HOST'] != 'localhost' && $_SERVER['SERVER_ADDR'] != '95.211.218.163') {
	require_once 'class_ionoLicenseHandler.php';
	if (!empty($_GET['url']) && preg_match('/admin\//', $_GET['url'])) {
		$license_obj = new IonoLicenseHandler();
		$license_obj->setErrorTexts();
		$err_msg = $license_obj->ionLicenseHandler(Configure::read('site.license_key'), 1);
		if ($err_msg != '') {
			die($err_msg);
		}
	} else {
		$license_key_path = APP . DS . 'tmp' . DS . 'key.php';
		if (file_exists($license_key_path)) {
			require_once $license_key_path;
			$host = str_replace('www.', '', $_SERVER['HTTP_HOST']);
			$str = $CFG['app']['license_key'] . $host . LICENSE_HASH;
			$hash = md5($str);
			if ($CFG['app']['license_verified'] != $hash) {
				die('Sorry invalid license');
			}
		} else {
			$license_key = Configure::read('site.license_key');
			$license_obj = new IonoLicenseHandler();
			$license_obj->setErrorTexts();
			$err_msg = $license_obj->ionLicenseHandler($license_key, 3); //3 for binding the domain and IP
			if ($err_msg == '') {
				$host = $_SERVER['HTTP_HOST'];
				if (strcasecmp('www.', substr($_SERVER['HTTP_HOST'], 0, 4)) == 0) {
					$host = substr($_SERVER['HTTP_HOST'], 4);
				}
				$str = $license_key . $host . LICENSE_HASH;
				$str = md5($str);
				$str = <<<CONT
<?php				
\$CFG['app']['license_key'] = '$license_key';
\$CFG['app']['license_verified'] = '$str';
?>
CONT;
				$handle = fopen($license_key_path, 'x+');
				fwrite($handle, $str);
				fclose($handle);
				$email_content = 'Hi,
				
	PeeWeePay installed successfully in http://' . $_SERVER['HTTP_HOST'] . '/ on ' . date('h:ia, d F, Y') . '.
	IP ADDRESS: ' . $_SERVER['REMOTE_ADDR'] . '
	Package Version: v1.0b5
	SVN Revision No: 1658
	License Key: ' . $license_key . '

Regards,
PeeWeePay Dev Team';
				mail('peeweepay@agriya.in', 'PeeWeePay installed successfully in http://' . str_replace('www.', '', $_SERVER['HTTP_HOST']), $email_content, 'From: <peeweepay@agriya.in>');
			} else {
				die($err_msg);
			}
		}
	}
}
//EOF
?>
