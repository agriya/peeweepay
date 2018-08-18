<?php
class SettingsController extends AppController
{
    var $uses = array(
        'Setting',
        'Language'
    );
	var $components = array(
        'OauthConsumer',
    );
    function admin_index()
    {
        $setting_categories = $this->Setting->SettingCategory->find('all', array(
            'recursive' => -1
        ));
        $this->set('setting_categories', $setting_categories);
    }
    function admin_edit($category_id = 1)
    {
        $this->disableCache();
        if (!empty($this->data)) {
			if (Configure::read('site.is_admin_settings_enabled')) {
            // Save settings
            if (isset($this->data['Setting']['delete_thumb_images'])) {
                $imageSettings = $this->Setting->find('all', array(
                    'conditions' => array(
                        'Setting.setting_category_id' => $this->data['Setting']['setting_category_id'],
                        'SettingCategory.name' => 'images'
                    ) ,
                    'fields' => array(
                        'Setting.id',
                        'Setting.name',
                        'Setting.value'
                    ) ,
                    'recursive' => 0
                ));
                foreach($imageSettings as $imageSetting) {
                    if ($this->data['Setting'][$imageSetting['Setting']['id']]['name'] != trim($imageSetting['Setting']['value'])) {
                        $thumb_size = explode('.', $imageSetting['Setting']['name']);
                        $dir = WWW_ROOT . 'img' . DS . $thumb_size[1];
                        $this->_traverse_directory($dir, 0);
                    }
                }
                unset($this->data['Setting']['delete_thumb_images']);
            }
            $category_id = $this->data['Setting']['setting_category_id'];
            unset($this->data['Setting']['setting_category_id']);
            if (isset($this->data['Setting']['not_allow_beyond_original']) || isset($this->data['Setting']['allow_handle_aspect'])) {
                $settings = $this->Setting->find('all', array(
                    'conditions' => array(
                        'Setting.setting_category_id = ' => $category_id
                    ) ,
                    'recursive' => 0
                ));
                foreach($settings as $setting) {
                    $field_name = explode('.', $setting['Setting']['name']);
                    if (isset($field_name[2]) && ($field_name[2] == 'is_not_allow_resize_beyond_original_size' || $field_name[2] == 'is_handle_aspect')) {
                        if ($field_name[2] == 'is_not_allow_resize_beyond_original_size') {
                            $setting_data['Setting']['id'] = $setting['Setting']['id'];
                            $setting_data['Setting']['value'] = in_array($setting['Setting']['id'], $this->data['Setting']['not_allow_beyond_original']) ? 1 : 0;
                            $this->Setting->save($setting_data['Setting']);
                        } else if ($field_name[2] == 'is_handle_aspect') {
                            $setting_data['Setting']['id'] = $setting['Setting']['id'];
                            $setting_data['Setting']['value'] = in_array($setting['Setting']['id'], $this->data['Setting']['allow_handle_aspect']) ? 1 : 0;
                            $this->Setting->save($setting_data['Setting']);
                        }
                    }
                }
                unset($this->data['Setting']['not_allow_beyond_original']);
                unset($this->data['Setting']['allow_handle_aspect']);
            }
            if (!empty($this->data['Setting']['72'])) {
				$this->Cookie->write('user_language', $this->data['Setting']['72']['name'], false);
            }
            foreach($this->data['Setting'] as $id => $value) {
                $settings['Setting']['id'] = $id;
                $settings['Setting']['value'] = $value['name'];
                $this->Setting->save($settings['Setting']);
            }
            $this->Session->setFlash(__l('Config settings updated') , 'default', null, 'success');
     
	  } else {
                $this->Session->setFlash(__l('Sorry. You Cannot Update the Settings in Demo Mode') , 'default', null, 'error');
            }
	}
        $this->data['Setting']['setting_category_id'] = $category_id;
        $settings = $this->Setting->find('all', array(
            'conditions' => array(
                'Setting.setting_category_id = ' => $category_id
            ) ,
            'order' => array(
                'Setting.order' => 'asc'
            ) ,
            'recursive' => 0
        ));
		$setting_category = $this->Setting->SettingCategory->find('first', array(
            'conditions' => array(
                'SettingCategory.id = ' => $category_id
            ) ,
            'recursive' => -1
        ));
        $this->set('settings_category', $setting_category);
        if (!empty($settings) && $settings[0]['SettingCategory']['name'] == 'Site') {
            $languageOptions = array();
            $languages = $this->Language->Translation->find('all', array(
                'conditions' => array(
                    'Language.is_active' => 1
                ) ,
                'fields' => array(
                    'DISTINCT(Language.id)',
                    'Language.name',
                    'Language.iso2'
                )
            ));
            if (!empty($languages)) {
                foreach($languages as $language) {
                    $languageOptions[$language['Language']['iso2']] = $language['Language']['name'];
                }
            }
            $this->set(compact('languageOptions'));
        }
        $beyondOriginals = array();
        $aspects = array();
        foreach($settings as $setting) {
            $field_name = explode('.', $setting['Setting']['name']);
            if (isset($field_name[2])) {
                if ($field_name[2] == 'is_not_allow_resize_beyond_original_size') {
                    $beyondOriginals[$setting['Setting']['id']] = Inflector::humanize(Inflector::underscore($field_name[1]));
                    $this->data['Setting']['not_allow_beyond_original'][] = ($setting['Setting']['value']) ? $setting['Setting']['id'] : '';
                } else if ($field_name[2] == 'is_handle_aspect') {
                    $aspects[$setting['Setting']['id']] = Inflector::humanize(Inflector::underscore($field_name[1]));
                    $this->data['Setting']['allow_handle_aspect'][] = ($setting['Setting']['value']) ? $setting['Setting']['id'] : '';
                }
            }
        }
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.secrect_key') ,
            'cookie' => true
        ));
		$fb_return_url = Router::url(array(
			'controller' => 'settings',
			'action' => 'fb_update',
			'admin' => false
		) , true);
		$this->Session->write('fb_return_url', $fb_return_url);
		$this->set('fb_login_url',$this->facebook->getLoginUrl(array(                
			'redirect_uri' => Router::url(array(
				'controller' => 'users',
				'action' => 'oauth_facebook',
				'admin' => false
			) , true),
			'scope' => 'email,offline_access,publish_stream'
         )));
        $this->set(compact('settings', 'beyondOriginals', 'aspects'));
    }
    function _traverse_directory($dir, $dir_count)
    {
        $handle = opendir($dir);
        while (false !== ($readdir = readdir($handle))) {
            if ($readdir != '.' && $readdir != '..') {
                $path = $dir . '/' . $readdir;
                if (is_dir($path)) {
                    @chmod($path, 0777);
                    ++$dir_count;
                    $this->_traverse_directory($path, $dir_count);
                }
                if (is_file($path)) {
                    @chmod($path, 0777);
                    @unlink($path);
                    //so that page wouldn't hang
                    flush();
                }
            }
        }
        closedir($handle);
        @rmdir($dir);
        return true;
    }
	function admin_tw_update(){
		$this->autoRender = false;
		if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'http://twitter.com/oauth/request_token');
            $this->Session->write('requestToken', $requestToken);
            $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        }else{
			$this->redirect(array(
				'action' => 'index',
				'admin' => true
			));
		}
	}
    function fb_update()
    {        
        if ($fb_session = $this->Session->read('fbuser')) {
            $settings = $this->Setting->find('all', array(
                'conditions' => array(
                    'Setting.name' => array(
                        'facebook.fb_access_token',
                        'facebook.fb_user_id'
                    )
                ) ,
                'fields' => array(
                    'Setting.id',
                    'Setting.name'
                ) ,
                'recursive' => -1
            ));
            foreach($settings as $setting) {
                $this->data['Setting']['id'] = $setting['Setting']['id'];
                if ($setting['Setting']['name'] == 'facebook.fb_user_id') {
                    $this->data['Setting']['value'] = $fb_session['id'];
                } elseif ($setting['Setting']['name'] == 'facebook.fb_access_token') {
                    $this->data['Setting']['value'] = $fb_session['access_token'];
                }
                if ($this->Setting->save($this->data)) {
                    $this->Session->setFlash(__l('Facebook credentials updated') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Facebook credentials could not be updated. Please, try again.') , 'default', null, 'error');
                }
            }
        }
        $this->redirect(array(
            'action' => 'index',
            'admin' => true
        ));
    }
}
?>