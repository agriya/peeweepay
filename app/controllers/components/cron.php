<?php
class CronComponent extends Object
{
    var $controller;
    var $components = array(
        'OauthConsumer',
    );
	function run_crons() 
    {
        $this->update_product_status();        
    }
    function update_product_status()
    {
        App::import('Model', 'Product');
		$siteurl = Cache::read('site.site_url_for_shell');

        $conditions = array();
        $this->Product = &new Product();
        $conditions['TO_DAYS(CURDATE()) - TO_DAYS(Product.created) '] = Configure::read('product.product_not_sold_days');
        $contain = array(
            'User' => array(
                'fields' => array(
                    'User.fullname',
                    'User.fb_access_token',
                    'User.fb_user_id',
                    'User.twitter_access_token',
                    'User.twitter_access_key',
                ) ,
                'UserSetting' => array(
                    'fields' => array(
                        'UserSetting.id',
                        'UserSetting.fb_status_product_not_sold',
                        'UserSetting.twitter_status_product_not_sold',
                    )
                ) ,			   
            ),
			'Currency' => array(
                    'fields' => array(
                        'Currency.code',
                        'Currency.symbol',
                    )
                ) ,
        );
        $products = $this->Product->find('all', array(
            'conditions' => $conditions,
            'contain' => $contain,
            'recursive' => 2
        ));
        if (!empty($products)) {
            foreach($products as $product) {
                $url = $siteurl. 'product/'. $product['Product']['slug'];
                if (!empty($product['User']['fb_access_token']) && !empty($product['User']['UserSetting']['fb_status_product_not_sold'])) {
                    App::import('Vendor', 'facebook/facebook');
                    $this->facebook = new Facebook(array(
                        'appId' => Configure::read('facebook.api_key') ,
                        'secret' => Configure::read('facebook.secrect_key') ,
                        'cookie' => true
                    ));
                    $facebook_dest_user_id = $product['User']['fb_user_id'];
                    $facebook_dest_access_token = $product['User']['fb_access_token'];
					$messageFindReplace = array(
						'##PRODUCT_TITLE##' => $product['Product']['title'],
						'##SELLER_NAME##' => $user['User']['fullname'],
						'##PRODUCT_PRICE##' => $product['Product']['price'].$product['Currency']['symbol']
					);
					$message = strtr(Configure::read('product.not_sold_product_status_message'), $messageFindReplace);                    
                    try {
                        $this->facebook->api('/' . $facebook_dest_user_id . '/feed', 'POST', array(
                            'access_token' => $facebook_dest_access_token,
                            'message' => $message,
                            'link' => $url,
                            'description' => $product['Product']['description']
                        ));
                    }
                    catch(Exception $e) {
                        $this->log('Post like on facebook error');
                    }
                }
                if (!empty($product['User']['twitter_access_token']) && !empty($product['User']['UserSetting']['twitter_status_product_not_sold']) && !empty($product['User']['twitter_access_key'])) {
                    $messageFindReplace = array(
						'##PRODUCT_TITLE##' => $product['Product']['title'],
						'##SELLER_NAME##' => $user['User']['fullname'],
						'##PRODUCT_PRICE##' => $product['Product']['price'].$product['Currency']['symbol']
					);
					$message = strtr(Configure::read('product.not_sold_product_status_message'), $messageFindReplace);
					$message = 'via' . ' ' . '@' . Configure::read('twitter.username') . ': ' . $url . ' ' . $message;

					$message = $message . $url;
                    $xml = $this->OauthConsumer->post('Twitter', $product['User']['twitter_access_token'], $product['User']['twitter_access_key'], 'https://twitter.com/statuses/update.xml', array(
                        'status' => $message
                    ));
                }
            }
        }
    }
}
?>