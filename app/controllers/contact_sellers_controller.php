<?php
class ContactSellersController extends AppController
{
    var $name = 'ContactSellers';
    var $components = array(
        'Email'
    );
    var $uses = array(
        'ContactSeller',
        'EmailTemplate'
    );
    var $helpers = array(
        'Gravatar'
    );
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'ContactSeller.geobyte_info',
            'ContactSeller.maxmind_info',
            'ContactSeller.browser_info',
            'ContactSeller.product_id',
        );
        parent::beforeFilter();
    }
    function add($product_id = null)
    {
        if (!empty($this->data)) {
            $product_id = $this->data['ContactSeller']['product_id'];
        }
        if (is_null($product_id)) {
            $this->cakeError('error404');
        }
        $product = $this->ContactSeller->Product->find('first', array(
            'conditions' => array(
                'Product.id = ' => $product_id
            ) ,
            'contain' => array(
                'User' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                        )
                    ) ,
                    'fields' => array(
                        'User.fullname',
                        'User.email',
                        'User.country_id',
						'User.twitter_avatar_url',
                        'User.profile_image_id',
                        'User.fb_user_id'
                    )
                )
            ) ,
            'recursive' => 2
        ));
        $this->pageTitle = __l('Contact Seller');
        if (!empty($this->data)) {
            $this->ContactSeller->create();
            //auto user detecting user info
            //Timezone and geo related data update coding begins
            if (!empty($this->data['ContactSeller']['geobyte_info'])) {
                $geobyte_info = json_decode($this->data['ContactSeller']['geobyte_info']);
            }
            if (!empty($this->data['ContactSeller']['maxmind_info'])) {
                $maxmind_info = json_decode($this->data['ContactSeller']['maxmind_info']);
            }
            if (!empty($maxmind_info) && !empty($geobyte_info) && $geobyte_info->country_code != $maxmind_info->country_code) {
                if (!empty($maxmind_info->country_code)) {
                    $country = $this->ContactSeller->Product->User->Country->find('first', array(
                        'conditions' => array(
                            'Country.iso2' => $maxmind_info->country_code
                        ) ,
                        'fields' => array(
                            'Country.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($country)) {
                        $this->data['ContactSeller']['auto_detected_country_id'] = $country['Country']['id'];
                    }
                }
                if (!empty($maxmind_info->region_name)) {
                    $this->data['ContactSeller']['auto_detected_state_id'] = $this->ContactSeller->Product->User->State->findOrSaveAndGetId($maxmind_info->region_name);
                }
                if (!empty($maxmind_info->city)) {
                    $this->data['ContactSeller']['auto_detected_city_id'] = $this->ContactSeller->Product->User->City->findOrSaveAndGetId($maxmind_info->city);
                }
                if (!empty($maxmind_info->latitude)) {
                    $this->data['ContactSeller']['auto_detected_latitude'] = $maxmind_info->latitude;
                }
                if (!empty($maxmind_info->longitude)) {
                    $this->data['ContactSeller']['auto_detected_longitude'] = $maxmind_info->longitude;
                }
                if (!empty($maxmind_info->mx_timezoneId)) {
                    $timezone = $this->ContactSeller->Product->User->Timezone->find('first', array(
                        'conditions' => array(
                            'Timezone.code' => $maxmind_info->mx_timezoneId
                        ) ,
                        'fields' => array(
                            'Timezone.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($timezone)) {
                        $this->data['ContactSeller']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                    }
                }
            } else if (!empty($geobyte_info)) {
                if (!empty($geobyte_info->country_code)) {
                    $country = $this->ContactSeller->Product->User->Country->find('first', array(
                        'conditions' => array(
                            'Country.iso2' => $geobyte_info->country_code
                        ) ,
                        'fields' => array(
                            'Country.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($country)) {
                        $this->data['ContactSeller']['auto_detected_country_id'] = $country['Country']['id'];
                    }
                }
                if (!empty($geobyte_info->region_name)) {
                    $this->data['ContactSeller']['auto_detected_state_id'] = $this->ContactSeller->Product->User->State->findOrSaveAndGetId($geobyte_info->region_name);
                }
                if (!empty($geobyte_info->city)) {
                    $this->data['ContactSeller']['auto_detected_city_id'] = $this->ContactSeller->Product->User->City->findOrSaveAndGetId($geobyte_info->city);
                }
                if (!empty($geobyte_info->latitude)) {
                    $this->data['ContactSeller']['auto_detected_latitude'] = $geobyte_info->latitude;
                }
                if (!empty($geobyte_info->longitude)) {
                    $this->data['ContactSeller']['auto_detected_longitude'] = $geobyte_info->longitude;
                }
                if (!empty($geobyte_info->gn_timezoneId)) {
                    $timezone = $this->ContactSeller->Product->User->Timezone->find('first', array(
                        'conditions' => array(
                            'Timezone.code' => $geobyte_info->gn_timezoneId
                        ) ,
                        'fields' => array(
                            'Timezone.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($timezone)) {
                        $this->data['ContactSeller']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                    }
                }
            }
            if (empty($this->data['ContactSeller']['auto_detected_timezone_id']) and !empty($this->data['ContactSeller']['browser_info'])) {
                $browser_info = json_decode($this->data['ContactSeller']['browser_info']);
                if (!empty($browser_info->gmt_offset) && !empty($browser_info->dst_offset) && !empty($browser_info->dst)) {
                    $timezone = $this->ContactSeller->Product->User->Timezone->find('first', array(
                        'conditions' => array(
                            'Timezone.gmt_offset' => $browser_info->gmt_offset,
                            'Timezone.dst_offset' => $browser_info->dst_offset,
                            'Timezone.hasdst' => $browser_info->dst
                        ) ,
                        'fields' => array(
                            'Timezone.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($timezone)) {
                        $this->data['ContactSeller']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                    }
                }
            }
            //auto user detecting user info
            $this->data['ContactSeller']['ip'] = $this->RequestHandler->getClientIP();
            $this->data['ContactSeller']['host'] = gethostbyaddr($this->RequestHandler->getClientIP());
            if ($this->ContactSeller->save($this->data)) {
                $this->_sendContactSellerMail($product);
                $this->set('success', 1);
            } else {
                $this->Session->setFlash(__l('Contact Seller could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data['ContactSeller']['product_id'] = $product_id;
        }
        $this->set('product', $product);
    }
    function _sendContactSellerMail($product)
    {
        $email = $this->EmailTemplate->selectTemplate('Contact Seller');
        $product_url = Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $product['Product']['slug'],
            'view_type' => ConstViewType::NormalView,
            'admin' => false
        ) , true);
        $site_url = Router::url('/', true);
        $contact_url = $site_url . '#contact';
        $site_logo_url = $site_url . 'img/logo.png';
		$terms_url = Router::url(array(
            'controller' => 'pages',
            'action' => 'view',
            'terms'            
        ) , true);
		$press_url = Router::url(array(
            'controller' => 'pages',
            'action' => 'view',
            'press'            
        ) , true);
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
			'##SUBJECT##' => $this->data['ContactSeller']['subject'],
            '##YOUR_NAME##' => $this->data['ContactSeller']['name'],
            '##FROM_EMAIL##' => $this->ContactSeller->changeFromEmail($this->data['ContactSeller']['email']) ,
            '##MESSAGE##' => $this->data['ContactSeller']['message'],
            '##PRODUCT_URL##' => $product_url,
            '##PRODUCT_TITLE##' => $product['Product']['title'],
            '##CONTACT_FROM_EMAIL##' => $this->data['ContactSeller']['email'],
            '##SITE_LOGO_URL##' => $site_logo_url,
            '##SITE_URL##' => $site_url,
            '##CONTACT_URL##' => $contact_url,
            '##PRESS_URL##' => $press_url,
            '##TWITTER_URL##' => 'http://twitter.com/' . Configure::read('twitter.username'),
            '##FACEBOOK_URL##' => 'http://facebook.com/' . Configure::read('facebook.username'),
            '##NEWSLETTER_URL##' => '#',
            '##TERMS_URL##' => $terms_url,
            '##CHARITY_URL##' => '#',
            '##PAYMENT_URL##' => '#',
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to contact seller email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = $this->ContactSeller->changeFromEmail($product['User']['email']);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q',
        ));
        $conditions = array();
        if (!empty($this->params['named']['product_id'])) {
            $conditions['ContactSeller.product_id'] = $this->params['named']['product_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'ContactSeller.id' => 'desc'
            )
        );
        if (isset($this->params['named']['q']) && !empty($this->params['named']['q'])) {
            $this->paginate['search'] = $this->params['named']['q'];
            $this->data['ContactSeller']['q'] = $this->params['named']['q'];
        }
        $this->pageTitle = __l('Seller Contacts');
        $this->ContactSeller->recursive = 0;
        $moreActions = $this->ContactSeller->moreActions;
        $this->set(compact('moreActions'));
        $this->set('contactSellers', $this->paginate());
        if (!empty($this->params['named']['simple_view'])) {
            $this->render('admin_simple_index');
        }
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->ContactSeller->del($id)) {
            $this->Session->setFlash(__l('Contact Seller deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>