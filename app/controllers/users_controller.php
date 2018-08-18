<?php
class UsersController extends AppController
{
    var $name = 'Users';
    var $components = array(
        'Email',
        'OauthConsumer',
    );
    // Photo, PhotoAlbum and PhotoComment are used in admin_stats
    var $uses = array(
        'User',
        'EmailTemplate',
        'Attachment',
    );
    var $helpers = array(
        'Csv',
        'Gravatar'
    );
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'User.send_to_user_id',
            'UserAvatar.filename',
        );
        parent::beforeFilter();
    }
    function dashboard($id)
    {
        $this->pageTitle = __l('Dashboard');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id = ' => $id
            ) ,
            'fields' => array(
                'User.id',
                'User.product_count',
                'User.product_verified_count',
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            $this->cakeError('error404');
        }
        $this->set('user', $user);
    }
    function update()
    {
        $this->autoRender = false;
        if (empty($this->data)) {
            $this->cakeError('error404');
        }
        $this->User->UserSetting->save($this->data['UserSetting']);
    }
    function profile_image($id = null)
    {
        if (!empty($this->data['User']['id'])) {
            $id = $this->data['User']['id'];
        }
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $id
            ) ,
            'contain' => array(
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.filename',
                        'UserAvatar.dir',
                        'UserAvatar.width',
                        'UserAvatar.height'
                    )
                )
            ) ,
            'recursive' => 0
        ));
        if (empty($user)) {
            $this->cakeError('error404');
        }
		$this->pageTitle = $user['User']['fullname'].' - '.__l('Profile Image');
        $this->User->UserAvatar->Behaviors->attach('ImageUpload', Configure::read('avatar.file'));
        if (!empty($this->data)) {
            if (!empty($this->data['UserAvatar']['filename']['name'])) {
                $this->data['UserAvatar']['filename']['type'] = get_mime($this->data['UserAvatar']['filename']['tmp_name']);
            }
            if (!empty($this->data['UserAvatar']['filename']['name']) || (!Configure::read('avatar.file.allowEmpty') && empty($this->data['UserAvatar']['id']))) {
                $this->User->UserAvatar->set($this->data);
            }
            $ini_upload_error = 1;
            if ($this->data['UserAvatar']['filename']['error'] == 1) {
                $ini_upload_error = 0;
            }
            if ($this->User->UserAvatar->validates() && $ini_upload_error) {
                if (!empty($this->data['UserAvatar']['filename']['name'])) {
                    $this->Attachment->del($user['UserAvatar']['id']);
                    $this->Attachment->create();
                    $this->data['UserAvatar']['class'] = 'UserAvatar';
                    $this->data['UserAvatar']['foreign_id'] = $this->data['User']['id'];
                    $this->Attachment->save($this->data['UserAvatar']);
                    $this->data['User']['profile_image_id'] = ConstProfileImage::Upload;
                }
                $this->User->save($this->data, false);
                $this->Session->setFlash(__l('User Profile Image has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'profile_image',
                    $id,
                    'admin' => false,
                ));
            }
        } else {
            $this->data = $user;
        }
        $profileimages = array(
            ConstProfileImage::Gravatar => ConstProfileImage::Gravatar,
            ConstProfileImage::Twitter => ConstProfileImage::Twitter,
            ConstProfileImage::Facebook => ConstProfileImage::Facebook,
            ConstProfileImage::Upload => ConstProfileImage::Upload
        );
        $this->set('profileimages', $profileimages);
		App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.secrect_key') ,
            'cookie' => true
        ));
		$fb_return_url = Router::url(array(
			'controller' => $this->params['named']['city'],
			'action' => 'users',
			'connect',
			$id,
			'admin' => false
		) , true);
		$this->Session->write('fb_return_url', $fb_return_url);
        $this->set('fb_login_url',$this->facebook->getLoginUrl(array(                
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true),
                'scope' => 'email,publish_stream'
         )));
    }
	function oauth_facebook()
	{
		App::import('Vendor', 'facebook/facebook');
		$this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.secrect_key') ,
            'cookie' => true
        ));
		$this->autoRender = false;
		if(!empty($_REQUEST['code'])){	
			$tokens = $this->facebook->setAccessToken(array(                
					'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true),
				'code' => $_REQUEST['code']
			));					
			$fb_return_url = $this->Session->read('fb_return_url');
			$this->Session->write('facebook_connected', '1');
			$this->redirect(
				$fb_return_url
			);
		}else{
			$this->Session->setFlash(__l('Invalid Facebook Connection.'), 'default', null, 'error');			
		}
	}
    function connect($id)
    {
        $this->pageTitle = __l('Connect');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id = ' => $id
            ) ,
            'contain' => array(
                'UserSetting' => array(
                    'fields' => array(
                        'UserSetting.id',
                        'UserSetting.fb_status_new_product',
                        'UserSetting.fb_status_product_sold',
                        'UserSetting.fb_status_product_not_sold',
                        'UserSetting.twitter_status_new_product',
                        'UserSetting.twitter_status_product_sold',
                        'UserSetting.twitter_status_product_not_sold',
                    )
                ) ,
            ) ,
            'recursive' => 0,
        ));
        if (empty($user)) {
            $this->cakeError('error404');
        }
        $this->data = $user;
        $type = '';
        $c_action = '';
        if (!empty($this->params['named']['type'])) {
            $type = $this->params['named']['type'];
        }
        if (!empty($this->params['named']['c_action'])) {
            $c_action = $this->params['named']['c_action'];
        }
        if ($type == 'facebook' && $c_action == 'disconnect') {
            $this->data['User']['fb_user_id'] = '';
            $this->data['User']['fb_access_token'] = '';
            $this->User->Save($this->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with facebook.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'connect',
                $id,
                'admin' => false,
            ));
        }
        if ($type == 'twitter' && $c_action == 'disconnect') {
            $this->data['User']['twitter_user_id'] = '';
            $this->data['User']['twitter_access_key'] = '';
            $this->data['User']['twitter_access_token'] = '';
            $this->User->Save($this->data['User'], false);
            $this->Session->setFlash(__l('You have successfully disconnected with twitter.') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'connect',
                $id,
                'admin' => false,
            ));
        } elseif ($type == 'twitter') {
            $requestToken = $this->OauthConsumer->getRequestToken('Twitter', 'http://twitter.com/oauth/request_token');
            $this->Session->write('requestToken', $requestToken);
            $this->Session->write('auth_user_id', $id);
            $this->redirect('http://twitter.com/oauth/authorize?oauth_token=' . $requestToken->key);
        }
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.secrect_key') ,
            'cookie' => true
        ));
		$fb_return_url = Router::url(array(
			'controller' => 'users',
			'action' => 'connect',			
			$id,
			'admin' => false
		) , true);
		$this->Session->write('fb_return_url', $fb_return_url);
        $this->set('fb_login_url',$this->facebook->getLoginUrl(array(                
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true),
                'scope' => 'email,publish_stream'
        )));
        $facebook_connected = $this->Session->read('facebook_connected');
		if ($facebook_connected) {
            $this->_facebook_login($id);
        }
    }
    function _facebook_login($id = null)
    {
		$this->Session->del('facebook_connected');
        $me = $this->Session->read('fbuser');        
        if(empty($me)) {
            $this->Session->setFlash(__l('Problem in Facebook connect. Please try again') , 'default', null, 'error');
            $this->redirect(Router::url('/', true));
        }
        $this->data['User']['id'] = $id;
        $this->data['User']['fb_user_id'] = $me['id'];        
        $this->data['User']['fb_access_token'] = $me['access_token'];
        if ($this->User->save($this->data, false)) {			
            $this->data = $this->User->UserSetting->find('first', array(
                'conditions' => array(
                    'UserSetting.user_id = ' => $id
                ) ,
                'contain' => array() ,
                'fields' => array(
                    'UserSetting.id',
                )
            ));
            $this->data['UserSetting']['user_id'] = $id;
            $this->data['UserSetting']['fb_status_new_product'] = 1;
            $this->data['UserSetting']['fb_status_product_sold'] = 1;
            $this->data['UserSetting']['fb_status_product_not_sold'] = 1;
            $this->User->UserSetting->save($this->data['UserSetting']);
        }
        $this->Session->setFlash(__l('You have successfully connected with facebook.') , 'default', null, 'success');
        $this->Session->del('Auth.redirectUrl');        
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'connect',
            $id,
            'admin' => false,
        ));
    }
    function oauth_callback()
    {
        $this->autoRender = false;
        // Fix to avoid the mail validtion for  Twitter
        $this->Auth->fields['username'] = 'username';
        $requestToken = $this->Session->read('requestToken');
        $accessToken = $this->OauthConsumer->getAccessToken('Twitter', 'http://twitter.com/oauth/access_token', $requestToken);
        $this->Session->write('accessToken', $accessToken);
        $xml = $this->OauthConsumer->get('Twitter', $accessToken->key, $accessToken->secret, 'https://twitter.com/account/verify_credentials.xml');
        $this->data['User']['twitter_access_token'] = (isset($accessToken->key)) ? $accessToken->key : '';;
        $this->data['User']['twitter_access_key'] = (isset($accessToken->secret)) ? $accessToken->secret : '';
        // So this to check whether it is  admin login to get its twitter access token and key to get updated.
        if ($this->Auth->user('id') and $this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            App::import('Model', 'Setting');
            $setting = new Setting;
            $setting->updateAll(array(
                'Setting.value' => "'" . $this->data['User']['twitter_access_token'] . "'",
            ) , array(
                'Setting.name' => 'twitter.site_user_access_token'
            ));
            $setting->updateAll(array(
                'Setting.value' => "'" . $this->data['User']['twitter_access_key'] . "'"
            ) , array(
                'Setting.name' => 'twitter.site_user_access_key'
            ));
            $this->Session->setFlash(__l('Your Twitter credentials are updated') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'settings',
                'admin' => true
            ));
        }
        App::import('Xml');
        $Xml = new Xml($xml);
        $data = $Xml->toArray();
        $user_id = $this->Session->read('auth_user_id');
        $this->data['User']['twitter_avatar_url'] = $data['User']['profile_image_url'];
        $this->data['User']['id'] = $user_id;
        if ($this->User->save($this->data, false)) {
            $this->data = $this->User->UserSetting->find('first', array(
                'conditions' => array(
                    'UserSetting.user_id = ' => $user_id
                ) ,
                'contain' => array() ,
                'fields' => array(
                    'UserSetting.id',
                )
            ));
            $this->data['UserSetting']['user_id'] = $user_id;
            $this->data['UserSetting']['twitter_status_new_product'] = 1;
            $this->data['UserSetting']['twitter_status_product_sold'] = 1;
            $this->data['UserSetting']['twitter_status_product_not_sold'] = 1;
            $this->User->UserSetting->save($this->data['UserSetting']);
        }
        $this->Session->del('auth_user_id');
        $this->Session->setFlash(__l('You have successfully connected with twitter.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'connect',
            $user_id,
            'admin' => false,
        ));
    }
	function admin_splashboard($refresh ='')
    {
        $this->pageTitle = __l('Dashboard');
		if(!empty($refresh) && $refresh='refresh'){
			$admin_stats_cache = APP . '/tmp/cache/views/element_0_admin_stats_dashboard';
			if (file_exists($admin_stats_cache)) {
				unlink($admin_stats_cache);
			} 
		}
    }

    function admin_login()
    {
        $this->pageTitle = __l('Login');
        if (!empty($this->data)) {
            $this->data['User']['username'] = trim($this->data['User']['username']);
            //Important: For login unique username or email check validation not necessary. Also in login method authentication done before validation.
            $this->User->set($this->data);
            if ($this->User->validates()) {
                $this->data['User']['password'] = $this->Auth->password($this->data['User']['passwd']);
                if ($this->Auth->login($this->data)) {
                    $this->User->UserLogin->insertUserLogin($this->Auth->user('id'));
                    if ($this->Auth->user()) {
                        if (!empty($this->data['User']['is_remember']) and $this->data['User']['is_remember'] == 1) {
                            $this->Cookie->del('User');
                            $cookie = array();
                            $remember_hash = md5($this->data['User']['username'] . $this->data['User']['password'] . Configure::read('Security.salt'));
                            $cookie['cookie_hash'] = $remember_hash;
                            $this->Cookie->write('User', $cookie, true, $this->cookieTerm);
                            $this->User->updateAll(array(
                                'User.cookie_hash' => '\'' . md5($remember_hash) . '\'',
                                'User.cookie_time_modified' => '\'' . date('Y-m-d h:i:s') . '\'',
                            ) , array(
                                'User.id' => $this->Auth->user('id')
                            ));
                        } else {
                            $this->Cookie->del('User');
                        }
                        if (!empty($this->data['User']['f'])) {
                            $this->redirect(Router::url('/', true) . $this->data['User']['f']);
                        } else {
                            $this->redirect(array(
                                'controller' => 'users',
                                'action' => 'splashboard',
                                'admin' => true
                            ));
                        }
                    }
                } else {
                    $this->Session->setFlash(__l('Sorry, login failed.  Your username or password are incorrect') , 'default', null, 'error');
                }
            }
        }
        //When already logged user trying to access the login page we are redirecting to site home page
        if ($this->Auth->user()) {
            $this->redirect('/');
        }
        $this->data['User']['passwd'] = '';
    }
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Users');
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->params['named']['stat']) && $this->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->params['named']['stat']) && $this->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->params['named']['stat']) && $this->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(User.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (isset($this->params['named']['q'])) {
            $this->data['User']['q'] = $this->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->params['named']['q']);
        }
        $this->data['User']['user_type_id'] = empty($this->params['named']['user_type_id']) ? ConstUserTypes::User : $this->params['named']['user_type_id'];
        // condition to list users only
        $conditions['User.user_type_id'] = $this->data['User']['user_type_id'];
        $this->User->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'limit' => 15,
            'order' => array(
                'User.id' => 'desc'
            )
        );
        if (isset($this->data['User']['q'])) {
            $this->paginate['search'] = $this->data['User']['q'];
        }
        $this->set('users', $this->paginate());
        $moreActions = $this->User->moreActions;
        $this->set(compact('moreActions'));
    }
    function admin_add()
    {
        $this->pageTitle = __l('Add New User/Admin');
        if (!empty($this->data)) {
            $this->data['User']['password'] = $this->Auth->password($this->data['User']['passwd']);
            $this->data['User']['is_agree_terms_conditions'] = '1';
            $this->data['User']['is_email_confirmed'] = 1;
            $this->data['User']['is_active'] = 1;
            $this->data['User']['signup_ip'] = $this->RequestHandler->getClientIP();
            $this->User->create();
            if ($this->User->save($this->data)) {
                // Send mail to user to activate the account and send account details
                $emailFindReplace = array(
                    '##USERNAME##' => $this->data['User']['username'],
                    '##LOGINLABEL##' => 'username',
                    '##USEDTOLOGIN##' => $this->data['User']['username'],
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##PASSWORD##' => $this->data['User']['passwd']
                );
                $email = $this->EmailTemplate->selectTemplate('Admin User Add');
                $this->Email->from = $email['from'];
                $this->Email->to = $this->data['User']['email'];
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                $this->Session->setFlash(__l('User has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                unset($this->data['User']['passwd']);
                $this->Session->setFlash(__l('User could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $userTypes = $this->User->UserType->find('list');
        $this->set(compact('userTypes'));
        if (!isset($this->data['User']['user_type_id'])) {
            $this->data['User']['user_type_id'] = ConstUserTypes::User;
        }
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->User->del($id)) {
            $this->Session->setFlash(__l('User has neen deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
    function admin_edit($id)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            $this->User->set($this->data['User']);
            if ($this->User->save()) {
                $this->Session->setFlash(__l('User has been updated successfully') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'users',
                    'action' => 'index',
                    'admin' => true
                ));
            }
        } else {
            $user = $this->User->find('first', array(
                'conditions' => array(
                    'User.id' => $id
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                $this->cakeError('error404');
            }
            $this->data = $user;
        }
        $countries = $this->User->Country->find('list');
        $this->set('countries', $countries);
    }
    function admin_stats()
    {
        $this->pageTitle = __l('Site Stats');
        $periods = array(
            'day' => array(
                'display' => __l('Today'),
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 0,
                )
            ) ,
            'week' => array(
                'display' => __l('This week'),
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 7,
                )
            ) ,
            'month' => array(
                'display' => __l('This month'),
                'conditions' => array(
                    'TO_DAYS(NOW()) - TO_DAYS(created) <= ' => 30,
                )
            ) ,
            'total' => array(
                'display' => __l('Total'),
                'conditions' => array()
            )
        );
        $this->loadModel('Transaction');
        $this->loadModel('Product');
        $this->loadModel('AbuseReport');
        $this->loadModel('SpamReport');
        $this->loadModel('ProductView');
        $user_conditions = array();
        $conditions = array();
        $models[] = array(
            'Product' => array(
                'display' => __l('Products') ,
                'link' => array(
                    'controller' => 'products',
                    'action' => 'index'
                ) ,
                'rowspan' => 6
            )
        );
        $models[] = array(
            'Product' => array(
                'display' => __l('Verified') ,
                'link' => array(
                    'controller' => 'products',
                    'action' => 'index',
                    'is_verified' => 1
                ) ,
                'conditions' => array_merge(array(
                    'Product.is_verified' => 1
                ) , $conditions) ,
                'alias' => 'ProductVerified',
                'isSub' => 'Product',
                'colspan' => 2
            )
        );
        $models[] = array(
            'Product' => array(
                'display' => __l('Unverified') ,
                'link' => array(
                    'controller' => 'products',
                    'action' => 'index',
                    'is_verified' => 0
                ) ,
                'conditions' => array_merge(array(
                    'Product.is_verified' => 0
                ) , $conditions) ,
                'alias' => 'ProductUnverified',
                'isSub' => 'Product',
                'colspan' => 2
            )
        );
        $models[] = array(
            'Product' => array(
                'display' => __l('All') ,
                'link' => array(
                    'controller' => 'products',
                    'action' => 'index'
                ) ,
                'conditions' => $conditions,
                'alias' => 'ProductAll',
                'isSub' => 'Product',
                'colspan' => 2
            )
        );
        $models[] = array(
            'ProductView' => array(
                'display' => __l('Normal Views') ,
                'conditions' => array_merge(array(
                    'ProductView.product_view_type_id' => ConstViewType::NormalView
                ) , $conditions) ,
                'alias' => 'ProductNormalView',
                'isSub' => 'Product',
                'colspan' => 2
            )
        );
        $models[] = array(
            'ProductView' => array(
                'display' => __l('Embed Views') ,
                'conditions' => array_merge(array(
                    'ProductView.product_view_type_id' => ConstViewType::EmbedView
                ) , $conditions) ,
                'alias' => 'ProductEmbedView',
                'isSub' => 'Product',
                'colspan' => 2
            )
        );
        $models[] = array(
            'ProductView' => array(
                'display' => __l('Total Views') ,
                'conditions' => $conditions,
                'alias' => 'ProductView',
                'isSub' => 'Product',
                'colspan' => 2
            )
        );
        $conditions['Transaction.status'] = array(
            ConstPaymentStatus::Completed,
            ConstPaymentStatus::Incomplete
        );
        $trans_currencies = $this->Transaction->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'Currency' => array(
                    'fields' => array(
                        'Currency.code',
                        'Currency.symbol'
                    )
                )
            ) ,
            'recursive' => 1,
            'group' => 'currency_id',
        ));
        if (!empty($trans_currencies)) {
            $total_currency = count($trans_currencies);
            $models[] = array(
                'Transaction' => array(
                    'display' => __l('Transactions') ,
                    'link' => array(
                        'controller' => 'transactions',
                        'action' => 'index'
                    ) ,
                    'rowspan' => $total_currency*2
                )
            );
            $i = 0;
            foreach($trans_currencies as $currency) {
                if ($i == 0) {
                    $models[] = array(
                        'Transaction' => array(
                            'display' => __l('Seller') ,
                            'display1' => sprintf('%s ( %s )', $currency['Currency']['code'], $currency['Currency']['symbol']) ,
                            'link' => array(
                                'controller' => 'transactions',
                                'action' => 'index',
                                'currency_id' => $currency['Transaction']['currency_id']
                            ) ,
                            'sumField' => 'seller_amount',
                            'conditions' => array_merge($conditions, array(
                                'currency_id' => $currency['Transaction']['currency_id']
                            )) ,
                            'alias' => 'TransactionSeller' . $currency['Transaction']['currency_id'],
                            'isSub' => 'Transaction',
                            'colspan' => 1,
                            'type' => 'cCurrency',
                            'subrowspan' => $total_currency
                        )
                    );
                } else {
                    $models[] = array(
                        'Transaction' => array(
                            'display1' => sprintf('%s ( %s )', $currency['Currency']['code'], $currency['Currency']['symbol']) ,
                            'link' => array(
                                'controller' => 'transactions',
                                'action' => 'index',
                                'currency_id' => $currency['Transaction']['currency_id']
                            ) ,
                            'sumField' => 'seller_amount',
                            'conditions' => array_merge($conditions, array(
                                'currency_id' => $currency['Transaction']['currency_id']
                            )) ,
                            'alias' => 'TransactionSeller' . $currency['Transaction']['currency_id'],
                            'isSub' => 'Transaction',
                            'colspan' => 1,
                            'type' => 'cCurrency'
                        )
                    );
                }
                $i++;
            }
            $i = 0;
            foreach($trans_currencies as $currency) {
                if ($i == 0) {
                    $models[] = array(
                        'Transaction' => array(
                            'display' => __l('Site') ,
                            'display1' => sprintf('%s ( %s )', $currency['Currency']['code'], $currency['Currency']['symbol']) ,
                            'link' => array(
                                'controller' => 'transactions',
                                'action' => 'index',
                                'currency_id' => $currency['Transaction']['currency_id']
                            ) ,
                            'sumField' => 'site_amount',
                            'conditions' => array_merge($conditions, array(
                                'currency_id' => $currency['Transaction']['currency_id']
                            )) ,
                            'alias' => 'TransactionSite' . $currency['Transaction']['currency_id'],
                            'isSub' => 'Transaction',
                            'colspan' => 1,
                            'type' => 'cCurrency',
                            'subrowspan' => $total_currency
                        )
                    );
                } else {
                    $models[] = array(
                        'Transaction' => array(
                            'display1' => sprintf('%s ( %s )', $currency['Currency']['code'], $currency['Currency']['symbol']) ,
                            'link' => array(
                                'controller' => 'transactions',
                                'action' => 'index',
                                'currency_id' => $currency['Transaction']['currency_id']
                            ) ,
                            'sumField' => 'site_amount',
                            'conditions' => array_merge($conditions, array(
                                'currency_id' => $currency['Transaction']['currency_id']
                            )) ,
                            'alias' => 'TransactionSeller' . $currency['Transaction']['currency_id'],
                            'isSub' => 'Transaction',
                            'colspan' => 1,
                            'type' => 'cCurrency'
                        )
                    );
                }
                $i++;
            }
        }
        foreach($models as $unique_model) {
            foreach($unique_model as $model => $fields) {
                foreach($periods as $key => $period) {
                    $conditions = $period['conditions'];
                    if (!empty($fields['conditions'])) {
                        $conditions = array_merge($periods[$key]['conditions'], $fields['conditions']);
                    }
                    $aliasName = !empty($fields['alias']) ? $fields['alias'] : $model;
                    if ($model == 'Transaction') {
                        if (empty($fields['sumField'])) {
                            $fields['sumField'] = 'amount';
                        }
                        $TransTotAmount = $this->{$model}->find('first', array(
                            'conditions' => $conditions,
                            'fields' => array(
                                'SUM(Transaction.' . $fields['sumField'] . ') as total_amount'
                            ) ,
                            'recursive' => -1
                        ));
                        $this->set($aliasName . $key, $TransTotAmount['0']['total_amount']);
                    } else {
                        $this->set($aliasName . $key, $this->{$model}->find('count', array(
                            'conditions' => $conditions,
                            'recursive' => -1
                        )));
                    }
                }
            }
        }
        $this->set(compact('recentUsers', 'periods', 'models'));
    }
	function admin_logs(){
        $error_log_path = APP . DS . 'tmp' . DS . 'logs' . DS . 'error.log';
        $error_log = $debug_log = '';
        if (file_exists($error_log_path)) {
            $handle = fopen($error_log_path, "r");
            fseek($handle, -10240, SEEK_END);
            $error_log = fread($handle, 10240);
            fclose($handle);
        }
        $debug_log_path = APP . DS . 'tmp' . DS . 'logs' . DS . 'debug.log';
        if (file_exists($debug_log_path)) {
            $handle = fopen($debug_log_path, "r");
            fseek($handle, -10240, SEEK_END);
            $debug_log = fread($handle, 10240);
            fclose($handle);
        }
        $this->set('error_log', $error_log);
        $this->set('debug_log', $debug_log);
        $this->set('tmpCacheFileSize', bytes_to_higher(dskspace(TMP . 'cache')));
        $this->set('tmpLogsFileSize', bytes_to_higher(dskspace(TMP . 'logs')));
	}
    function admin_clear_logs()
    {
        if (!empty($this->params['named']['type'])) {
            if ($this->params['named']['type'] == 'error_log') {
				$error_log_path = APP . '/tmp/logs/error.log';
				if (file_exists($error_log_path)) {
                	unlink(APP . '/tmp/logs/error.log');
				}	
                $this->Session->setFlash(__l('Error log has been cleared') , 'default', null, 'success');
            } elseif ($this->params['named']['type'] == 'debug_log') {
				$debug_log_path = APP . '/tmp/logs/debug.log';
                if (file_exists($debug_log_path)) {
                	unlink(APP . '/tmp/logs/debug.log');
				}	
                $this->Session->setFlash(__l('Debug log has been cleared') , 'default', null, 'success');
            }
        }
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'admin_logs'
        ));
    }
    function admin_change_password($user_id = null)
    {
        $this->pageTitle = __l('Change Password');
        if (!empty($this->data)) {
            $this->User->set($this->data);
            if ($this->User->validates()) {
                if ($this->User->updateAll(array(
                    'User.password' => '\'' . $this->Auth->password($this->data['User']['passwd']) . '\'',
                ) , array(
                    'User.id' => $this->data['User']['user_id']
                ))) {
                    $this->Session->setFlash(__l('Your password changed successfully') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Password could not be changed') , 'default', null, 'error');
            }
            unset($this->data['User']['old_password']);
            unset($this->data['User']['passwd']);
            unset($this->data['User']['confirm_password']);
        } else {
            if (empty($user_id)) {
                $user_id = $this->Auth->user('id');
            }
        }
        $this->data['User']['user_id'] = (!empty($this->data['User']['user_id'])) ? $this->data['User']['user_id'] : $user_id;
    }
    function admin_logout()
    {
        $this->Auth->logout();
        $this->Cookie->del('User');
        $this->Cookie->del('user_language');
        $this->Session->setFlash(__l('You are now logged out of the site.') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'users',
            'action' => 'login'
        ));
    }
}
?>