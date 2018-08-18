<?php
/* SVN FILE: $Id: app_controller.php 173 2009-01-31 12:51:40Z rajesh_04ag02 $ */
/**
 * Short description for file.
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @subpackage    cake.app
 * @since         CakePHP(tm) v 0.2.9
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
/**
 * Short description for class.
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package       cake
 * @subpackage    cake.app
 */
class AppController extends Controller
{
    var $components = array(
        'RequestHandler',
        'Security',
        'Auth',
        'XAjax',
        //'DebugKit.Toolbar',
        'Cookie'
    );
    var $helpers = array(
        'Html',
        'Javascript',
        'AutoLoadPageSpecific',
        'Form',
        'Asset',
        'Auth',
        'Time',
        'Tree',
        'List'
    );
    public $isHome = false;
    public $homePageId;
    var $cookieTerm = '+4 weeks';
    //    var $view = 'Theme';
    var $theme = 'themes';
    function beforeRender()
    {
        $this->set('meta_for_layout', Configure::read('meta'));
        $this->set('js_vars_for_layout', (isset($this->js_vars)) ? $this->js_vars : '');
        parent::beforeRender();
    }
    function __construct()
    {
        parent::__construct();
        // site settings are set in config
        App::import('Model', 'Setting');
        $setting_model_obj = new Setting();
        $settings = $setting_model_obj->getKeyValuePairs();
        Configure::write($settings);
        App::import('Model', 'Translation');
        $translation_model_obj = new Translation();
        $lang_code = !empty($_COOKIE['CakeCookie']['user_language']) ? $_COOKIE['CakeCookie']['user_language'] : Configure::read('site.language');
        Cache::set(array(
            'duration' => '+100 days'
        ));
        $translations = Cache::read($lang_code . '_translations');
        if (empty($translations) and $translations === false) {
            $translations = $translation_model_obj->find('all', array(
                'fields' => array(
                    'Translation.key',
                    'Translation.lang_text'
                ) ,
                'contain' => array(
                    'Language' => array(
                        'fields' => array(
                            'Language.iso2'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            Cache::set(array(
                'duration' => '+100 days'
            ));
            Cache::write($lang_code . '_translations', $translations);
        }
        foreach($translations as $translation) {
            $GLOBALS['_langs'][$translation['Language']['iso2']][$translation['Translation']['key']] = $translation['Translation']['lang_text'];
        }
        $js_trans_array = array(
            'Please select at least one record!',
            'Are you sure you want to do this action?',
            'Are you sure you want to',
        );
        foreach($js_trans_array as $trans) {
            if (!empty($GLOBALS['_langs'][$lang_code][$trans])) {
                $this->js_vars['cfg']['lang'][$trans] = $GLOBALS['_langs'][$lang_code][$trans];
            }
        }
    }
    function beforeFilter()
    {
		// Coding done to disallow demo user to change the admin settings
		if ($this->params['action'] != 'flashupload') {
			$cur_page = $this->params['controller'] . '/' . $this->params['action'];
			$admin_demomode_updation_not_allowed_array = Configure::read('site.admin_demomode_updation_not_allowed_array');
			if ($this->Auth->user('user_type_id') && $this->Auth->user('user_type_id') == ConstUserTypes::Admin && !Configure::read('site.is_admin_settings_enabled') && (!empty($this->data) || $this->params['action'] == 'admin_delete' || $this->params['action'] == 'admin_update') && in_array($cur_page, $admin_demomode_updation_not_allowed_array)) {
				$this->Session->setFlash(__l('Sorry. You cannot update or delete in demo mode') , 'default', null, 'error');
				$this->redirect(array(
					'controller' => $this->params['controller'],
					'action' => 'index',
				));
			}
		}
        // End of Code
		$cur_page = $this->params['controller'] . '/' . $this->params['action'];
                
        // check site is under maintenance mode or not. admin can set in settings page and then we will display maintenance message, but admin side will work.
        if ($cur_page != 'images/view' and $cur_page != 'devs/robots' and Configure::read('site.maintenance_mode') && (($this->Auth->user('user_type_id') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) or ($this->params['prefix'] != 'admin' and $cur_page != 'users/admin_login'))) {
            $this->cakeError('error500');
        }
        //Fix to upload the file through the flash multiple uploader
        if ((isset($_SERVER['HTTP_USER_AGENT']) and ((strtolower($_SERVER['HTTP_USER_AGENT']) == 'shockwave flash') or (strpos(strtolower($_SERVER['HTTP_USER_AGENT']) , 'adobe flash player') !== false))) and isset($this->params['pass'][0]) and ($this->params['action'] == 'flashupload' || $this->params['action'] == 'thumbnail')) {           			
			session_id($this->params['pass'][0]);
            session_start();
        }
        if (strpos($this->here, '/view/') !== false) {
            trigger_error('*** dev1framework: Do not view page through /view/; use singular/slug', E_USER_ERROR);
        }
        // check the method is exist or not in the controller
        $methods = array_flip($this->methods);
        if (!isset($methods[strtolower($this->params['action']) ])) {
            return $this->cakeError('missingAction', array(
                array(
                    'className' => Inflector::camelize($this->params['controller'] . "Controller") ,
                    'action' => $this->params['action'],
                    'webroot' => $this->webroot,
                    'url' => $this->here,
                    'base' => $this->base
                )
            ));
        }
        // Home page ID
		$this->homePageId = intval(Configure::read('Page.home_page_id'));
		//Set site url for cron
		if (!(Cache::read('site.site_url_for_shell'))) {
            Cache::write('site.site_url_for_shell', Router::url('/', true));
        }
        $this->_checkAuth();
        $this->js_vars['cfg']['path_relative'] = Router::url('/');
		$this->js_vars['cfg']['timezone'] = date('Z') / (60*60);
		$this->js_vars['cfg']['date_format'] = 'M d, Y';
        $this->js_vars['cfg']['path_absolute'] = Router::url('/', true);
		$this->js_vars['cfg']['today_date'] = date('Y-m-d');
        parent::beforeFilter();
    }
    function _checkAuth()
    {
        $this->Auth->fields = array(
            'username' => 'username',
            'password' => 'password'
        );
		$exception_array = Configure::read('site.exception_array');
        $cur_page = $this->params['controller'] . '/' . $this->params['action'];
        if (!in_array($cur_page, $exception_array) && $this->params['action'] != 'flashupload') {
            if (!$this->Auth->user('id')) {
                // check cookie is present and it will auto login to account when session expires
                $cookie_hash = $this->Cookie->read('User.cookie_hash');
                if (!empty($cookie_hash)) {
                    if (is_integer($this->cookieTerm) || is_numeric($this->cookieTerm)) {
                        $expires = time() +intval($this->cookieTerm);
                    } else {
                        $expires = strtotime($this->cookieTerm, time());
                    }
                    App::import('Model', 'User');
                    $user_model_obj = new User();
                    $this->data = $user_model_obj->find('first', array(
                        'conditions' => array(
                            'User.cookie_hash =' => md5($cookie_hash) ,
                            'User.cookie_time_modified <= ' => date('Y-m-d h:i:s', $expires) ,
                        ) ,
                        'fields' => array(
                            'User.' . Configure::read('user.using_to_login') ,
                            'User.password'
                        ) ,
                        'recursive' => -1
                    ));
                    // auto login if cookie is present
                    if ($this->Auth->login($this->data)) {
                        $user_model_obj->UserLogin->insertUserLogin($this->Auth->user('id'));
                        $this->redirect(Router::url('/', true) . $this->params['url']['url']);
                    }
                }
                $this->Session->setFlash(__l('Authorisation Required'));
                $is_admin = false;
                if (isset($this->params['prefix']) and $this->params['prefix'] == 'admin') {
                    $is_admin = true;
                }
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'login',
                    'admin' => $is_admin,
                    '?f='.$this->params['url']['url']
                ));
            }
            if (isset($this->params['prefix']) and $this->params['prefix'] == 'admin' and $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->redirect('/');
            }
        } else {
            $this->Auth->allow('*');
        }
        $this->Auth->autoRedirect = false;
        $this->Auth->userScope = array(
            'User.is_active' => 1
        );
        if (isset($this->Auth)) {
            $this->Auth->loginError = __l(sprintf('Sorry, login failed.  Either your %s or password are incorrect or admin deactivated your account.', Configure::read('user.using_to_login')));
        }
        $this->layout = 'default';
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && (isset($this->params['prefix']) and $this->params['prefix'] == 'admin')) {
            $this->layout = 'admin';
        }
    }
    function autocomplete($param_encode = null, $param_hash = null)
    {
        $modelClass = Inflector::singularize($this->name);
        $conditions = false;
        if (isset($this->{$modelClass}->_schema['is_approved'])) {
            $conditions['is_approved = '] = '1';
        }
        $this->XAjax->autocomplete($param_encode, $param_hash, $conditions);
    }
    function _redirectGET2Named($whitelist_param_names = null)
    {
        $query_strings = array();
        if (is_array($whitelist_param_names)) {
            foreach($whitelist_param_names as $param_name) {
                if (!empty($this->params['url'][$param_name])) { // querystring
                    $query_strings[$param_name] = $this->params['url'][$param_name];
                }
            }
        } else {
            $query_strings = $this->params['url'];
            unset($query_strings['url']); // Can't use ?url=foo

        }
        if (!empty($query_strings)) {
            $query_strings = array_merge($this->params['named'], $query_strings);
			
			if(!empty($query_strings['product_search'])){   // for tmp fixes
				$query_strings['controller'] = 'products';
				$query_strings['action'] = 'index';
			}
            $this->redirect($query_strings, null, true);
        }
    }
    function show_captcha()
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new securimage();
        $img->show(); // alternate use:  $img->show('/path/to/background.jpg');
        $this->autoRender = false;
    }
    function captcha_play()
    {        

		App::import('Vendor', 'securimage/securimage');
        $img = new Securimage();
        $this->disableCache();
        $this->RequestHandler->respondAs('mp3', array(
            'attachment' => 'captcha.mp3'
        ));
		$img->audio_format = 'mp3';		
        echo $img->getAudibleCode('mp3');
    }
	function admin_update()
    {
        if (!empty($this->data[$this->modelClass])) {
            $r = $this->data[$this->modelClass]['r'];
            $actionid = $this->data[$this->modelClass]['more_action_id'];
            unset($this->data[$this->modelClass]['r']);
            unset($this->data[$this->modelClass]['more_action_id']);
            $ids = array();
            foreach($this->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id']) {
                    $ids[] = $id;
                }
            }
            if ($actionid && !empty($ids)) {
                switch ($actionid) {
                    case ConstMoreAction::Unverified:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_verified'] = 0;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been Unverified') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Verified:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_verified'] = 1;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been Verified') , 'default', null, 'success');
                        break;
						
                    case ConstMoreAction::Inactive:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_active'] = 0;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been deactivated') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Active:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_active'] = 1;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been activated') , 'default', null, 'success');
                        break;
                    case ConstMoreAction::Disabled:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_enabled'] = 0;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been disabled') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Enabled:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_enabled'] = 1;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been enabled') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Delete:
						foreach($ids as $id){
							$this->{$this->modelClass}->del($id);
						}
                        $this->Session->setFlash(__l('Checked').' '. $this->modelClass .' '. __l('has been deleted') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Suspend:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_admin_suspended'] = 1;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been suspended') , 'default', null, 'success');
                        break;
					case ConstMoreAction::Unsuspend:
                        foreach($ids as $id) {
                            $saveList['id'] = $id;
                            $saveList['is_admin_suspended'] = 0;
                            $this->{$this->modelClass}->save($saveList);
                        }
                        $this->Session->setFlash(__l('Checked product has been unsuspended') , 'default', null, 'success');
                        break;
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
	function getImageUrl($model, $attachment, $options)
    {
        $default_options = array(
            'dimension' => 'big_thumb',
            'class' => '',
            'alt' => 'alt',
            'title' => 'title',
            'type' => 'jpg'
        );
        $options = array_merge($default_options, $options);
        $image_hash = $options['dimension'] . '/' . $model . '/' . $attachment['id'] . '.' . md5(Configure::read('Security.salt') . $model . $attachment['id'] . $options['type'] . $options['dimension'] . Configure::read('site.name')) . '.' . $options['type'];
        return 'img/' . $image_hash;
    }
}
?>
