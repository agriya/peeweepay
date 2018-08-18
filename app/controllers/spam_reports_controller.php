<?php
class SpamReportsController extends AppController
{
    var $name = 'SpamReports';
    var $components = array(
        'Email'
    );
    var $uses = array(
        'SpamReport',
        'EmailTemplate'
    );
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'SpamReport.geobyte_info',
            'SpamReport.maxmind_info',
            'SpamReport.browser_info',
            'SpamReport.product_id',
        );
        parent::beforeFilter();
    }
    function add($product_id = null)
    {
        if (!empty($this->data)) {
            $product_id = $this->data['SpamReport']['product_id'];
        }
        if (is_null($product_id)) {
            $this->cakeError('error404');
        }
        $this->pageTitle = __l('Add Spam Report');
        if (!empty($this->data)) {
            $this->SpamReport->create();
            //auto user detecting user info
            //Timezone and geo related data update coding begins
            if (!empty($this->data['SpamReport']['geobyte_info'])) {
                $geobyte_info = json_decode($this->data['SpamReport']['geobyte_info']);
            }
            if (!empty($this->data['SpamReport']['maxmind_info'])) {
                $maxmind_info = json_decode($this->data['SpamReport']['maxmind_info']);
            }
            if (!empty($maxmind_info) && !empty($geobyte_info) && $geobyte_info->country_code != $maxmind_info->country_code) {
                if (!empty($maxmind_info->country_code)) {
                    $country = $this->SpamReport->Product->User->Country->find('first', array(
                        'conditions' => array(
                            'Country.iso2' => $maxmind_info->country_code
                        ) ,
                        'fields' => array(
                            'Country.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($country)) {
                        $this->data['SpamReport']['auto_detected_country_id'] = $country['Country']['id'];
                    }
                }
                if (!empty($maxmind_info->region_name)) {
                    $this->data['SpamReport']['auto_detected_state_id'] = $this->SpamReport->Product->User->State->findOrSaveAndGetId($maxmind_info->region_name);
                }
                if (!empty($maxmind_info->city)) {
                    $this->data['SpamReport']['auto_detected_city_id'] = $this->SpamReport->Product->User->City->findOrSaveAndGetId($maxmind_info->city);
                }
                if (!empty($maxmind_info->latitude)) {
                    $this->data['SpamReport']['auto_detected_latitude'] = $maxmind_info->latitude;
                }
                if (!empty($maxmind_info->longitude)) {
                    $this->data['SpamReport']['auto_detected_longitude'] = $maxmind_info->longitude;
                }
                if (!empty($maxmind_info->mx_timezoneId)) {
                    $timezone = $this->SpamReport->Product->User->Timezone->find('first', array(
                        'conditions' => array(
                            'Timezone.code' => $maxmind_info->mx_timezoneId
                        ) ,
                        'fields' => array(
                            'Timezone.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($timezone)) {
                        $this->data['SpamReport']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                    }
                }
            } else if (!empty($geobyte_info)) {
                if (!empty($geobyte_info->country_code)) {
                    $country = $this->SpamReport->Product->User->Country->find('first', array(
                        'conditions' => array(
                            'Country.iso2' => $geobyte_info->country_code
                        ) ,
                        'fields' => array(
                            'Country.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($country)) {
                        $this->data['SpamReport']['auto_detected_country_id'] = $country['Country']['id'];
                    }
                }
                if (!empty($geobyte_info->region_name)) {
                    $this->data['SpamReport']['auto_detected_state_id'] = $this->SpamReport->Product->User->State->findOrSaveAndGetId($geobyte_info->region_name);
                }
                if (!empty($geobyte_info->city)) {
                    $this->data['SpamReport']['auto_detected_city_id'] = $this->SpamReport->Product->User->City->findOrSaveAndGetId($geobyte_info->city);
                }
                if (!empty($geobyte_info->latitude)) {
                    $this->data['SpamReport']['auto_detected_latitude'] = $geobyte_info->latitude;
                }
                if (!empty($geobyte_info->longitude)) {
                    $this->data['SpamReport']['auto_detected_longitude'] = $geobyte_info->longitude;
                }
                if (!empty($geobyte_info->gn_timezoneId)) {
                    $timezone = $this->SpamReport->Product->User->Timezone->find('first', array(
                        'conditions' => array(
                            'Timezone.code' => $geobyte_info->gn_timezoneId
                        ) ,
                        'fields' => array(
                            'Timezone.id'
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($timezone)) {
                        $this->data['SpamReport']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                    }
                }
            }
            if (empty($this->data['SpamReport']['auto_detected_timezone_id']) and !empty($this->data['SpamReport']['browser_info'])) {
                $browser_info = json_decode($this->data['SpamReport']['browser_info']);
                if (!empty($browser_info->gmt_offset) && !empty($browser_info->dst_offset) && !empty($browser_info->dst)) {
                    $timezone = $this->SpamReport->Product->User->Timezone->find('first', array(
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
                        $this->data['SpamReport']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                    }
                }
            }
            //auto user detecting user info
            $this->data['SpamReport']['ip'] = $this->RequestHandler->getClientIP();
            $this->data['SpamReport']['host'] = gethostbyaddr($this->RequestHandler->getClientIP());
            if ($this->SpamReport->save($this->data)) {
                $this->Session->setFlash(__l('Report Spam has been added') , 'default', null, 'success');
                $product = $this->SpamReport->Product->find('first', array(
                    'conditions' => array(
                        'Product.id = ' => $product_id
                    ) ,
                    'contain' => array() ,
                    'fields' => array(
                        'Product.slug',
                        'Product.title',
                    )
                ));
                $this->_sendSpamReportMail($product);
                $ajax_url = Router::url(array(
                    'controller' => 'products',
                    'action' => 'v',
                    'slug' => $product['Product']['slug'],
                    'view_type' => ConstViewType::NormalView
                ) , true);
                $success_msg = 'redirect*' . $ajax_url;
                echo $success_msg;
                exit;
            } else {
                $this->Session->setFlash(__l('Spam Report could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data['SpamReport']['product_id'] = $product_id;
        }
        $this->set(compact('products'));
    }
    function _sendSpamReportMail($product)
    {
        $email = $this->EmailTemplate->selectTemplate('Spam Report');
        $product_url = Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $product['Product']['slug'],
            'view_type' => ConstViewType::NormalView, 
            'admin' => false
        ) , true);
        $ip = $this->RequestHandler->getClientIP();
        $site_url = Router::url('/', true);
        $contact_url = $site_url . '#contact';
        $site_logo_url = $site_url . 'img/logo.png';		
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##YOUR_NAME##' => $this->data['SpamReport']['name'],
            '##FROM_EMAIL##' => $this->SpamReport->changeFromEmail($this->data['SpamReport']['email']) ,
            '##SITE_ADDR##' => gethostbyaddr($ip) ,
            '##IP##' => $ip,
            '##MESSAGE##' => $this->data['SpamReport']['message'],
            '##PRODUCT_NAME##' => '<a href="' . $product_url . '">' . $product['Product']['title'] . '</a>',
            '##CONTACT_FROM_EMAIL##' => $this->data['SpamReport']['email'],
            '##SITE_LOGO_URL##' => $site_logo_url,
            '##SITE_URL##' => $site_url,
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'admin' => false
            ) , true) ,
            '##PRESS_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'press',
                'admin' => false
            ) , true) ,
            '##TWITTER_URL##' => 'http://twitter.com/' . Configure::read('twitter.username') ,
            '##FACEBOOK_URL##' => 'http://facebook.com/' . Configure::read('facebook.username') ,
            '##NEWSLETTER_URL##' => Router::url(array(
                'controller' => 'subscriptions',
                'action' => 'add',
                'admin' => false
            ) , true) ,
            '##TERMS_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'terms',
                'admin' => false
            ) , true) ,
            '##CHARITY_URL##' => '#',
            '##PAYMENT_URL##' => '#',
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to report spam email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = Configure::read('site.contact_email');
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
        $this->pageTitle = __l('Spam Reports');
        if (!empty($this->params['named']['product_id'])) {
            $conditions['SpamReport.product_id'] = $this->params['named']['product_id'];
            $product = $this->SpamReport->Product->find('first', array(
                'conditions' => array(
                    'Product.id' => $this->params['named']['product_id']
                ) ,
                'fields' => array(
                    'Product.id',
                    'Product.title',
                ) ,
                'recursive' => -1
            ));
            if (!empty($product['Product']['title'])) {
                $this->pageTitle.= ' - ' . $product['Product']['title'];
            }
        }
		$conditions['Product.id > '] = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'SpamReport.id' => 'desc'
            )
        );
        if (isset($this->params['named']['q']) && !empty($this->params['named']['q'])) {
            $this->paginate['search'] = $this->params['named']['q'];
            $this->data['SpamReport']['q'] = $this->params['named']['q'];
        }
        $this->SpamReport->recursive = 0;
        $moreActions = $this->SpamReport->moreActions;
        $this->set(compact('moreActions'));
        $this->set('spamReports', $this->paginate());
        if (!empty($this->params['named']['simple_view'])) {
            $this->render('admin_simple_index');
        }
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Spam Report');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->SpamReport->save($this->data)) {
                $this->Session->setFlash(__l('Spam Report has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Spam Report could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->SpamReport->read(null, $id);
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
        }
        $this->pageTitle.= ' - ' . $this->data['SpamReport']['name'];
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->SpamReport->del($id)) {
            $this->Session->setFlash(__l('Spam Report deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>