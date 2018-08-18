<?php
class ProductsController extends AppController
{
    var $name = 'Products';
    var $components = array(
        'Email',
        'OauthConsumer',
        'PaypalPlatform',
        'Paypal',
    );
    var $uses = array(
        'Product',
        'Attachment',
        'EmailTemplate',
    );
    var $helpers = array(
        'Gravatar'
    );
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Attachment',
            'Product.tmp_tag_array',
            'Product.tag',
            'ProductShipmentCost.grouped_country_id',
            'ProductShipmentCost.shipment_cost',
            'Product.latitude',
            'Product.longitude',
            'Product.geobyte_info',
            'Product.maxmind_info',
            'Product.browser_info',
            'Product.zoom_level',
            'ProductPhoto.filename',
			'Product.is_shipment_cost_per_item_or_order',
        );
		for ($i = 0; $i < Configure::read('product.max_upload_photo'); $i++) {
			$this->Security->disabledFields[] = 'ProductPhoto.'.$i.'.id';
			$this->Security->disabledFields[] = 'ProductPhoto.'.$i.'.filename';
			$this->Security->disabledFields[] = 'ProductPhoto.Attachment.'.$i.'.filename';
		}		
        parent::beforeFilter();
    }
	function admin_update_status()
	{
		App::import('Component', 'cron');
        $this->Cron = &new CronComponent();
        $this->Cron->run_crons();
		$this->Session->setFlash(__l('Cron Triggered Successfully') , 'default', null, 'success');
        $this->redirect(array(
            'controller' => 'pages',
            'action' => 'display',
            'tools'
        ));
	}
    function flashupload()
    {
        $this->Product->Attachment->Behaviors->attach('ImageUpload', Configure::read('product_image.file'));
        $this->XAjax->previewImage();
    }
    function thumbnail()
    {
        $file_id = $this->params['pass'][1]; // show preview uploaded product image, session unique id
        $this->XAjax->thumbnail($file_id);
    }
    function index()
    {
        $this->_redirectGET2Named(array(
            'q',
            'extra_options',
            'currency_id',
            'is_file',
            'is_image',
            'min_price',
            'max_price',
            'latitude',
            'longitude',
            'product_search',
            'sw_latitude',
            'sw_longitude',
            'ne_latitude',
            'ne_longitude',
            'is_price_filter',
        ));
        $this->pageTitle = __l('Products');
		$view = '';
        if (!empty($this->params['named']['view'])) {
            $view = $this->params['named']['view'];
        }
		if (!empty($this->params['named']['type'])) {
            $view = $this->params['named']['type'];
        }
        $conditions = array();
       
        $conditions['Product.is_verified'] = 1;
        $conditions['Product.is_admin_suspended'] = 0;
		
		if($view != 'dashboard'){
			 $conditions['Product.is_include_search'] = 1;
		}
        
        if ($view == 'simple') {
            $conditions['Product.user_id'] = $this->params['named']['user'];
            $conditions['Product.id <>'] = $this->params['named']['product_id'];
        }
        if (!empty($this->params['named']['tag'])) {
            $productTag = $this->Product->ProductTag->find('first', array(
                'conditions' => array(
                    'ProductTag.slug' => $this->params['named']['tag']
                ) ,
                'fields' => array(
                    'ProductTag.name',
                    'ProductTag.slug'
                ) ,
                'contain' => array(
                    'Product' => array(
                        'fields' => array(
                            'Product.id'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            if (empty($productTag)) {
                $this->cakeError('error404');
            }
            $this->pageTitle.= sprintf(__l(' - Tag - %s') , $productTag['ProductTag']['name']);
            $ids = array();
            if (!empty($productTag)) {
                foreach($productTag['Product'] as $product) {
                    $ids[] = $product['id'];
                }
            }
            $conditions['Product.id'] = $ids;
        }
        if (!empty($this->params['named']['user'])) {
            $user = $this->Product->User->find('first', array(
				'conditions' => array(
					'User.id' => $this->params['named']['user']
				),
				'fields' => array(
					'User.fullname'
				),
				'recursive' => -1
			));
			$this->pageTitle.= ' - User - '.$user['User']['fullname'];
			$conditions['Product.user_id'] = $this->params['named']['user'];
        }
        if ($view == 'dashboard') {
            unset($conditions['Product.is_include_search']);
        }
        if (!empty($this->params['named']['q'])) {
            $this->data['Product']['q'] = $this->params['named']['q'];
            $conditions['OR'] = array(
                'Product.title LIKE' => '%' . $this->params['named']['q'] . '%',
                'Product.description LIKE' => '%' . $this->params['named']['q'] . '%',
            );
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->params['named']['q']);
        }
        if (!empty($this->params['named']['extra_options'])) {
            $this->data['Product']['extra_options'] = $this->params['named']['extra_options'];
            if (!empty($this->params['named']['currency_id'])) {
                $conditions['Product.currency_id'] = $this->params['named']['currency_id'];
                $this->data['Product']['currency_id'] = $this->params['named']['currency_id'];
            }
            if (!empty($this->params['named']['is_image'])) {
                $conditions['Product.product_photo_count > '] = 1;
                $this->data['Product']['is_image'] = $this->params['named']['is_image'];
            }
            if (!empty($this->params['named']['is_file'])) {
                $conditions['Product.is_file'] = 1;
                $this->data['Product']['is_file'] = $this->params['named']['is_file'];
            }            
            if (!empty($this->params['named']['min_price']) && !empty($this->params['named']['is_price_filter'])) {
                $conditions['Product.price >= '] = $this->params['named']['min_price'];
                $this->data['Product']['min_price'] = $this->params['named']['min_price'];
            } else {
                $this->data['Product']['min_price'] = 0;
                $conditions['Product.price >= '] = 0;
            }
            if (!empty($this->params['named']['max_price']) && !empty($this->params['named']['is_price_filter'])) {
                $conditions['Product.price <= '] = $this->params['named']['max_price'];
                $this->data['Product']['max_price'] = $this->params['named']['max_price'];
            }
        } else {
            $this->data['Product']['min_price'] = Configure::read('product.search_min_price');
            $this->data['Product']['max_price'] = Configure::read('product.search_max_price');
        }
        if (!empty($this->params['named']['filter']) && $this->params['named']['filter'] == 'latest') {
            $order['Product.id'] = 'desc';
            $this->pageTitle.= __l(' - Latest');
        } elseif (!empty($this->params['named']['filter']) && $this->params['named']['filter'] == 'trending') {
            $order['Product.sold_quantity'] = 'desc';
            $this->pageTitle.= __l(' - Tranding Items');
        } else {
            $order['Product.id'] = 'desc';
        }
        $contain = array(
            'User' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                    )
                ) ,
                'fields' => array(
                    'User.id',
                    'User.fullname',
                    'User.email',
                )
            ) ,
            'ProductPhoto' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
            ) ,
            'Currency',            
        );
		if ($view == 'json') {
			if (isset($this->params['named']['sw_latitude'])) {
				$lon1 = $this->params['named']['sw_longitude'];
				$lon2 = $this->params['named']['ne_longitude'];
				$lat1 = $this->params['named']['sw_latitude'];
				$lat2 = $this->params['named']['ne_latitude'];
				$conditions['Product.latitude BETWEEN ? AND ?'] = array(
					$lat1,
					$lat2
				);
				$conditions['Product.longitude BETWEEN ? AND ?'] = array(
					$lon1,
					$lon2
				);
				$conditions[] = 'Product.latitude IS NOT NULL';
				$conditions[] = 'Product.longitude IS NOT NULL';  
			}	          
            
			 $contain = array(	
			 					'Currency',
			 					'ProductPhoto' => array(
									'Attachment' => array(
											'fields' => array(
												'Attachment.id',
												'Attachment.filename',
												'Attachment.dir',
												'Attachment.width',
												'Attachment.height'
											)
									)
								)
							);
			$fields = array(
				'Product.latitude',
				'Product.longitude',
				'Product.slug',
				'Product.title',
				'Product.currency_id',
				'Product.price'
			);
			$this->paginate = array(
                'conditions' => $conditions,
                'contain' => $contain,
				'fields' => $fields,
                'recursive' => 2,
                'order' => $order
            );
            $products = $this->paginate();
            if (!empty($products)) {				
                $product_count = count($products);
                for ($r = 0; $r < $product_count; $r++) {
                    $product_photo_image = $products[$r]['ProductPhoto']['0']['Attachment'];
					$image_options = array(
						'dimension' => 'normal_thumb',
						'class' => '',
						'alt' => sprintf(__l('[Image: %s]'), !empty($products[$r]['ProductPhoto']['0']['caption']) ? $products[$r]['ProductPhoto']['0']['caption'] : $products[$r]['Product']['title']) ,
						'title' => !empty($products[$r]['ProductPhoto']['0']['caption']) ? $products[$r]['ProductPhoto']['0']['caption'] : $products[$r]['Product']['title'],
						'type' => 'jpg'
					);
					$products[$r]['Product']['symbol'] = $products[$r]['Currency']['symbol'] ;
					unset($products[$r]['ProductPhoto']);
					unset($products[$r]['Currency']);
					$img_path = $this->getImageUrl('ProductPhoto', $product_photo_image, $image_options);
					$filename = APP . $img_path;
					$filename = str_replace('/', '\\', $filename);
                    $products[$r]['Product']['medium_thumb'] = Router::url('/' , true) .$img_path;
					if(!file_exists($filename)){
						getimagesize($products[$r]['Product']['medium_thumb']);
					}
                }
            }
		}elseif ($view == 'simple') {
            $products = $this->Product->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain,
                'recursive' => 2,
                'order' => $order,
                'limit' => 5
            ));
        }
		elseif (($this->RequestHandler->prefers('rss') || $this->RequestHandler->prefers('xml'))) {
            $this->paginate = array(
                'conditions' => $conditions,
                'contain' => $contain,
                'recursive' => 2,
                'order' => $order
            );
            $products = $this->paginate();
        }elseif ($view == 'home') {
            $products = $this->Product->find('all', array(
                'conditions' => $conditions,
                'contain' => $contain,
                'recursive' => 2,
                'order' => 'rand()',
                'limit' => 10
            ));
        } else {
            $this->paginate = array(
                'conditions' => $conditions,
                'contain' => $contain,
                'recursive' => 2,
                'order' => $order
            );
            $products = $this->paginate();
        }
        if ($this->RequestHandler->prefers('rss') && !empty($products)) {
            $this->pageTitle = sprintf(__l('%s\'s Products') , $products[0]['User']['fullname']);
        }
        if ($view == 'json') {
            $this->view = 'Json';
            $this->set('json', $products);
        }
        $this->set('products', $products);
        $this->set('center_latitude', '37.4419');
        $this->set('center_longitude', '-122.1419');
        if ($view == 'simple') {
            $this->render('index_simple');
        } elseif ($view == 'dashboard') {
            $this->render('dashboard');
        } elseif ($view == 'home') {
			$this->pageTitle = Configure::read('site.slogan');
            $this->render('index_home');
        }		
        for ($i = 0; $i <= 100000; $i+= 1000) {
            $min_prices[$i] = $i;
            $max_prices[$i] = $i;
        }
        $all_currencies = $this->Product->Currency->find('all', array(
            'conditions' => array(
                'is_enabled' => 1
            ) ,
            'recursive' => -1
        ));
        foreach($all_currencies as $currency) {
            $this->js_vars['cfg']['currenies'][$currency['Currency']['id']]['code'] = $currency['Currency']['code'];
            $this->js_vars['cfg']['currenies'][$currency['Currency']['id']]['symbol'] = $currency['Currency']['symbol'];
            $currencies[$currency['Currency']['id']] = $currency['Currency']['code'] . '		 ' . $currency['Currency']['name'];
        }
        $this->set(compact('currencies', 'min_prices', 'max_prices'));
    }
    function v()
    {
        $this->pageTitle = __l('Product');
        if (is_null($this->params['named']['slug'])) {
            $this->cakeError('error404');
        }
        $conditions = array();
        $contain = array();
        $conditions['Product.slug'] = $this->params['named']['slug'];
        if (!$this->Auth->user()) {
            $conditions['Product.is_admin_suspended'] = 0;
        }
        if (!empty($this->params['named']['view_type']) && $this->params['named']['view_type'] == ConstViewType::EmbedView) {
            $contain = array(
                'Currency',
                'ProductPhoto' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                    'limit' => 1
                ) ,
            );
        } else {
            $contain = array(
                'User' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                        )
                    ) ,
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.fullname',
                        'User.email',
                        'User.product_count',
                        'User.twitter_avatar_url',
                        'User.profile_image_id',
                        'User.fb_user_id'
                    )
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
                'Currency',                
                'ProductPhoto' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    )
                ) ,
                'ProductTag' => array(
                    'fields' => array(
                        'ProductTag.name',
                        'ProductTag.slug',
                        'ProductTag.id'
                    )
                ) ,
                'ProductShipmentCost' => array(
                    'GroupedCountry' => array(
                        'fields' => array(
                            'GroupedCountry.name',
                        )
                    ) ,
                    'fields' => array(
                        'ProductShipmentCost.shipment_cost',
                    )
                )
            );
        }
        $product = $this->Product->find('first', array(
            'conditions' => $conditions,
            'contain' => $contain,
            'recursive' => 2,
        ));
        if (empty($product)) {
            $this->cakeError('error404');
        }
		$producttags = array();
		if(!empty($product['ProductTag'])){
			foreach($product['ProductTag'] as $product_tag){
				$producttags[] = $product_tag['name'];
			}
			if (!empty($producttags)) {
                $producttags = implode(', ', $producttags);
            }
		}
        // Setting meta tag and descriptions //
        if (!empty($producttags)) {
            Configure::write('meta.keywords', Configure::read('meta.keywords') . ', ' . $producttags);
        }
		if(!empty($product['Product']['description'])){
			$short_desc = $this->myTruncate($product['Product']['description'], 300, " ");
			if (!empty($short_desc)) {
				Configure::write('meta.description', $short_desc);
			}
		}
		// Facebook Like Comment - Used in default.ctp //
		Configure::write('meta.product_name', $product['Product']['title']);
        Configure::write('meta.product_url', Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $product['Product']['slug'],
			'view_type' => ConstViewType::NormalView,
			'admin' => false
        ) , true));
        if (!empty($product['ProductPhoto']['0']['Attachment'])) {
			$product_photo_image = $product['ProductPhoto']['0']['Attachment'];
            $image_options = array(
                'dimension' => 'medium_thumb',
                'class' => '',
                'alt' => sprintf(__l('[Image: %s]'), !empty($product['ProductPhoto']['0']['caption']) ? $product['ProductPhoto']['0']['caption'] : $product['Product']['title']) ,
                'title' => !empty($product['ProductPhoto']['0']['caption']) ? $product['ProductPhoto']['0']['caption'] : $product['Product']['title'],
                'type' => 'png'
            );
            $product_image = Router::url('/' , true) . $this->getImageUrl('Product', $product_photo_image, $image_options);
            Configure::write('meta.product_image', $product_image);
        }
		if(empty($this->params['named']['count'])){
			$this->Product->ProductView->create();
			$this->data['ProductView']['product_id'] = $product['Product']['id'];
			$this->data['ProductView']['product_view_type_id'] = $this->params['named']['view_type'];
			$this->data['ProductView']['ip'] = $this->RequestHandler->getClientIP();
	        $this->data['ProductView']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
			$this->Product->ProductView->save($this->data);
		}
        $this->pageTitle.= ' - ' . $product['Product']['title'];
        $this->set('product', $product);
        if (!empty($this->params['named']['view_type']) && $this->params['named']['view_type'] == ConstViewType::EmbedView) {
            $this->layout = 'ajax';
            $this->render('v_embed');
        }
    }
	// Truncate function for truncating meta descriptions
	function myTruncate($string, $limit, $break=".", $pad="...") 
	{
		// return with no change if string is shorter than $limit  
		if(strlen($string) <= $limit) 
			return $string;
		// is $break present between $limit and the end of the string? 
		if(false !== ($breakpoint = strpos($string, $break, $limit)))
		{ 
			if($breakpoint < strlen($string) - 1) 
			{ 
				$string = substr($string, 0, $breakpoint) . $pad; 
			} 
		} 
		return $string; 
	}
    function shipment_map($id)
    {
        $this->pageTitle = __l('Product Shipment Map');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id = ' => $id
            ) ,
            'contain' => array(
                'ProductShipmentCost' => array(
                    'GroupedCountry' => array(
                        'Union' => array(
                            'Country' => array(
                                'fields' => array(
                                    'Country.id',
                                    'Country.iso2'
                                )
                            ) ,
                            'fields' => array(
                                'Union.id',
                                'Union.name'
                            )
                        ) ,
                        'Continent' => array(
                            'Country' => array(
                                'fields' => array(
                                    'Country.id',
                                    'Country.iso2'
                                )
                            ) ,
                            'fields' => array(
                                'Continent.id',
                                'Continent.name'
                            )
                        )
                    )
                )
            ) ,
            'recursive' => 4,
        ));
        if (empty($product)) {
            $this->cakeError('error404');
        }
        if (!empty($product['ProductShipmentCost'])) {
            $worldwide = $this->Product->ProductShipmentCost->find('all', array(
                'conditions' => array(
                    'product_id' => $id,
                    'ProductShipmentCost.grouped_country_id' => ConsGroupedCountry::Worldwide
                ) ,
                'contain' => array(
                    'GroupedCountry' => array()
                ) ,
                'recursive' => 1
            ));
            $country_array = array();
            $i = 0;
            if (!empty($worldwide)) {
                $all_countries = $this->Product->Transaction->Country->find('all', array(
                    'recursive' => -1
                ));
                foreach($all_countries as $country) {
                    $this->js_vars['cfg']['productshipmentcost'][$i]['shipment_cost'] = $worldwide[0]['ProductShipmentCost']['shipment_cost'];
                    $this->js_vars['cfg']['productshipmentcost'][$i]['country'] = $country['Country']['iso2'];
                    $country_array[$country['Country']['iso2']] = $i;
                    $i++;
                }
            }
            foreach($product['ProductShipmentCost'] as $productShipmentCost) {
                if (!empty($productShipmentCost['GroupedCountry']) && $productShipmentCost['GroupedCountry']['id'] != ConsGroupedCountry::Worldwide) {
                    if (empty($productShipmentCost['GroupedCountry']['Union']) && empty($productShipmentCost['GroupedCountry']['Continent'])) {
                        $country = $this->Product->Transaction->Country->find('first', array(
                            'conditions' => array(
                                'Country.id' => $productShipmentCost['GroupedCountry']['id']
                            ) ,
                            'recursive' => -1
                        ));
                        if (!empty($country)) {
                            if (array_key_exists($country['Country']['iso2'], $country_array)) {
                                $this->js_vars['cfg']['productshipmentcost'][$country_array[$country['Country']['iso2']]]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                $this->js_vars['cfg']['productshipmentcost'][$country_array[$country['Country']['iso2']]]['country'] = $country['Country']['iso2'];
                            } else {
                                $this->js_vars['cfg']['productshipmentcost'][$i]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                $this->js_vars['cfg']['productshipmentcost'][$i]['country'] = $country['Country']['iso2'];
                                $i++;
                            }
                        }
                    }
                    if (!empty($productShipmentCost['GroupedCountry']['Union'])) {
                        if (!empty($productShipmentCost['GroupedCountry']['Union']['Country'])) {
                            foreach($productShipmentCost['GroupedCountry']['Union']['Country'] as $country) {
                                if (array_key_exists($country['iso2'], $country_array)) {
                                    $this->js_vars['cfg']['productshipmentcost'][$country_array[$country['iso2']]]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                    $this->js_vars['cfg']['productshipmentcost'][$country_array[$country['iso2']]]['country'] = $country['iso2'];
                                } else {
                                    $this->js_vars['cfg']['productshipmentcost'][$i]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                    $this->js_vars['cfg']['productshipmentcost'][$i]['country'] = $country['iso2'];
                                    $i++;
                                }
                            }
                        }
                    }
                    if (!empty($productShipmentCost['GroupedCountry']['Continent'])) {
                        if (!empty($productShipmentCost['GroupedCountry']['Continent']['Country'])) {
                            foreach($productShipmentCost['GroupedCountry']['Continent']['Country'] as $country) {
                                if (array_key_exists($country['iso2'], $country_array)) {
                                    $this->js_vars['cfg']['productshipmentcost'][$country_array[$country['iso2']]]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                    $this->js_vars['cfg']['productshipmentcost'][$country_array[$country['iso2']]]['country'] = $country['iso2'];
                                } else {
                                    $this->js_vars['cfg']['productshipmentcost'][$i]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                    $this->js_vars['cfg']['productshipmentcost'][$i]['country'] = $country['iso2'];
                                    $i++;
                                }
                            }
                        }
                    }
                }
            }
        }
        $this->layout = 'lightbox';
    }
    function add()
    {
        $this->pageTitle = __l('Products - Add');
        $site_min_fee = Configure::read('site.site_min_fee');
        if (!empty($this->data)) {
            $this->Product->create();
            $ini_upload_error = $ini_upload_file_error = 1;
            $this->Product->ProductPhoto->Attachment->Behaviors->detach('ImageUpload');
            if (!empty($this->data['Attachment']['filename']['tmp_name'])) {
                if (!empty($this->data['Attachment']['filename']['error'])) {
                    $ini_upload_file_error = 0;
                }
                $this->data['Attachment']['filename']['type'] = get_mime($this->data['Attachment']['filename']['tmp_name']);
                $this->Product->Attachment->Behaviors->attach('ImageUpload', Configure::read('product.file'));
                $this->Product->Attachment->set($this->data['Attachment']);
                if (!$this->Product->Attachment->validates()) {
                    $ini_upload_file_error = 0;
                    $this->Session->setFlash(__l('Product could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Product->Attachment->validationErrors['filename'] = '';
            }
            $this->Product->User->set($this->data['User']);
            $this->Product->set($this->data['Product']);
            if (($this->Product->User->validates() &$this->Product->validates()) && ($ini_upload_error && $ini_upload_file_error)) {
                $user_id = 0;
                $this->data['Product']['fullname'] = $this->data['User']['fullname'];
                $this->data['Product']['email'] = $this->data['User']['email'];
                $this->data['Product']['country_id'] = $this->data['User']['country_id'];
                $this->data['Product']['user_id'] = $user_id;
                //auto user detecting user info
                //Timezone and geo related data update coding begins
                if (!empty($this->data['Product']['geobyte_info'])) {
                    $geobyte_info = json_decode($this->data['Product']['geobyte_info']);
                }
                if (!empty($this->data['Product']['maxmind_info'])) {
                    $maxmind_info = json_decode($this->data['Product']['maxmind_info']);
                }
                if (!empty($maxmind_info) && !empty($geobyte_info) && $geobyte_info->country_code != $maxmind_info->country_code) {
                    if (!empty($maxmind_info->country_code)) {
                        $country = $this->Product->User->Country->find('first', array(
                            'conditions' => array(
                                'Country.iso2' => $maxmind_info->country_code
                            ) ,
                            'fields' => array(
                                'Country.id'
                            ) ,
                            'recursive' => -1
                        ));
                        if (!empty($country)) {
                            $this->data['Product']['auto_detected_country_id'] = $country['Country']['id'];
                        }
                    }
                    if (!empty($maxmind_info->region_name)) {
                        $this->data['Product']['auto_detected_state_id'] = $this->Product->User->State->findOrSaveAndGetId($maxmind_info->region_name);
                    }
                    if (!empty($maxmind_info->city)) {
                        $this->data['Product']['auto_detected_city_id'] = $this->Product->User->City->findOrSaveAndGetId($maxmind_info->city);
                    }
                    if (!empty($maxmind_info->latitude)) {
                        $this->data['Product']['auto_detected_latitude'] = $maxmind_info->latitude;
                    }
                    if (!empty($maxmind_info->longitude)) {
                        $this->data['Product']['auto_detected_longitude'] = $maxmind_info->longitude;
                    }
                    if (!empty($maxmind_info->mx_timezoneId)) {
                        $timezone = $this->Product->User->Timezone->find('first', array(
                            'conditions' => array(
                                'Timezone.code' => $maxmind_info->mx_timezoneId
                            ) ,
                            'fields' => array(
                                'Timezone.id'
                            ) ,
                            'recursive' => -1
                        ));
                        if (!empty($timezone)) {
                            $this->data['Product']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                        }
                    }
                } else if (!empty($geobyte_info)) {
                    if (!empty($geobyte_info->country_code)) {
                        $country = $this->Product->User->Country->find('first', array(
                            'conditions' => array(
                                'Country.iso2' => $geobyte_info->country_code
                            ) ,
                            'fields' => array(
                                'Country.id'
                            ) ,
                            'recursive' => -1
                        ));
                        if (!empty($country)) {
                            $this->data['Product']['auto_detected_country_id'] = $country['Country']['id'];
                        }
                    }
                    if (!empty($geobyte_info->region_name)) {
                        $this->data['Product']['auto_detected_state_id'] = $this->Product->User->State->findOrSaveAndGetId($geobyte_info->region_name);
                    }
                    if (!empty($geobyte_info->city)) {
                        $this->data['Product']['auto_detected_city_id'] = $this->Product->User->City->findOrSaveAndGetId($geobyte_info->city);
                    }
                    if (!empty($geobyte_info->latitude)) {
                        $this->data['Product']['auto_detected_latitude'] = $geobyte_info->latitude;
                    }
                    if (!empty($geobyte_info->longitude)) {
                        $this->data['Product']['auto_detected_longitude'] = $geobyte_info->longitude;
                    }
                    if (!empty($geobyte_info->gn_timezoneId)) {
                        $timezone = $this->Product->User->Timezone->find('first', array(
                            'conditions' => array(
                                'Timezone.code' => $geobyte_info->gn_timezoneId
                            ) ,
                            'fields' => array(
                                'Timezone.id'
                            ) ,
                            'recursive' => -1
                        ));
                        if (!empty($timezone)) {
                            $this->data['Product']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                        }
                    }
                }
                if (empty($this->data['Product']['auto_detected_timezone_id']) and !empty($this->data['Product']['browser_info'])) {
                    $browser_info = json_decode($this->data['Product']['browser_info']);
                    if (!empty($browser_info->gmt_offset) && !empty($browser_info->dst_offset) && !empty($browser_info->dst)) {
                        $timezone = $this->Product->User->Timezone->find('first', array(
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
                            $this->data['Product']['auto_detected_timezone_id'] = $timezone['Timezone']['id'];
                        }
                    }
                }
                //auto user detecting user info
                if ($this->Product->save($this->data['Product'])) {
                    $product_id = $this->Product->id;
                    // short URL
                    include_once APP . 'vendors' . DS . 'BaseIntEncoder.php';
                    $this->data['Product']['id'] = $product_id;
                    $this->data['Product']['slug'] = BaseIntEncoder::encode($product_id);
                    $this->Product->save($this->data['Product'], false);
                    // short URL
                    if (!empty($this->data['Attachment']['filename']['name'])) {
                        $this->data['Attachment']['class'] = 'Product';
                        $this->data['Attachment']['foreign_id'] = $product_id;
                        $this->data['Attachment']['filename'] = $this->data['Attachment']['filename'];
                        $this->Attachment->create();
                        $this->Attachment->save($this->data);
                        $this->Product->Attachment->Behaviors->detach('ImageUpload');
                        $this->data['Product']['is_file'] = 1;
                        $this->Product->save($this->data['Product'], false);
                    }
                    $product_photo['ProductPhoto']['product_id'] = $product_id;
                    // save product photo
                    for ($i = 0; $i < Configure::read('product.max_upload_photo'); $i++) {
                        if (!empty($this->data['ProductPhoto'][$i]['filename'])) {
                            $file_id = $this->data['ProductPhoto'][$i]['filename'];
                            $this->Product->ProductPhoto->create();
                            $product_photo['ProductPhoto']['caption'] = $this->data['ProductPhoto'][$i]['caption'];
                            if ($this->Product->ProductPhoto->save($product_photo['ProductPhoto'])) {
                                $product_photo_id = $this->Product->ProductPhoto->id;
                                $this->Product->ProductPhoto->Attachment->Behaviors->attach('ImageUpload');
                                $this->Product->ProductPhoto->Attachment->enableUpload(false); //don't trigger upload behavior on save
                                $product_photo['Attachment']['class'] = 'ProductPhoto';
                                $product_photo['Attachment']['foreign_id'] = $product_photo_id;
                                $product_photo['Attachment']['mimetype'] = $_SESSION["product_file_info"][$file_id]['type'];
                                $product_photo['Attachment']['dir'] = 'ProductPhoto/' . $product_photo_id;
                                $upload_path = APP . DS . 'media' . DS . 'ProductPhoto' . DS . $product_photo_id;
                                new Folder($upload_path, true);
                                $file_name = $_SESSION["product_file_info"][$file_id]['filename'];
                                $product_photo['Attachment']['filename'] = $file_name;
                                $fp = fopen($upload_path . DS . $file_name, 'w');
                                fwrite($fp, base64_decode($_SESSION["product_file_info"][$file_id]['original']));
                                fclose($fp);
                                $this->Product->ProductPhoto->Attachment->create();
                                $this->Product->ProductPhoto->Attachment->save($product_photo);
                                $this->Product->ProductPhoto->Attachment->Behaviors->detach('ImageUpload');
                                unset($_SESSION["product_file_info"][$file_id]);
                            }
                        }
                    }
                    // save product shipment
                    if (!empty($this->data['Product']['is_shipment_cost_required'])) {
                        foreach($this->data['ProductShipmentCost'] as $productShipmentCost) {
                            $shipment_cost = array();
                            if (!empty($productShipmentCost['grouped_country_id'])) {
                                $shipment_cost['ProductShipmentCost'] = $productShipmentCost;
                                $shipment_cost['ProductShipmentCost']['product_id'] = $this->Product->id;
                                $this->Product->ProductShipmentCost->create();
                                $this->Product->ProductShipmentCost->save($shipment_cost['ProductShipmentCost']);
                            }
                        }
                    }
                    $this->Session->setFlash(__l('Product has been added successfully.') , 'default', null, 'success');
                    $this->set('success', 1);
                    $this->pageTitle = __l('product confirmation');
                    $this->_sendProductVerifyMail($product_id);
                } else {
                    $this->Session->setFlash(__l('Product could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                if (!empty($attachmentValidationError)) {
                    foreach($attachmentValidationError as $key => $error) {
                        $this->Product->ProductPhoto->validationErrors[$key]['filename'] = $error['filename'];
                    }
                }
                $this->Session->setFlash(__l('Product could not be added. Please, try again.') , 'default', null, 'error');
            }
            // set site fee amount
            $site_min_fee = ($this->data['Product']['price']*(Configure::read('site.fee') /100));
            $this->data['Product']['site_fee_amount'] = ($site_min_fee < Configure::read('site.site_min_fee')) ? Configure::read('site.site_min_fee') : $site_min_fee;
        } else {
            $this->data['Product']['quantity'] = -1;
            $this->data['Product']['price'] = 10;
            // set site fee amount
            $site_min_fee = ($this->data['Product']['price']*(Configure::read('site.fee') /100));
            $this->data['Product']['site_fee_amount'] = ($site_min_fee < Configure::read('site.site_min_fee')) ? Configure::read('site.site_min_fee') : $site_min_fee;
			
            $this->data['Product']['is_display_page_views'] = 1;
            $this->data['Product']['is_display_quantity'] = 1;
            $this->data['Product']['is_include_search'] = 1;
            $this->data['Product']['is_shipment_cost_per_item_or_order'] = ConstShipmentCosts::Item;
            $this->data['Product']['latitude'] = Configure::read('product.default_latitude');
            $this->data['Product']['longitude'] = Configure::read('product.default_longitude');
            $this->data['Product']['zoom_level'] = Configure::read('product.default_zoom');
        }
        $quantity_types = array(
            '-1' => __l('unlimited') ,
            '1' => __l('single item') ,
            '2' => __l('a stock of') ,
        );        
        $all_currencies = $this->Product->Currency->find('all', array(
            'conditions' => array(
                'is_enabled' => 1
            ) ,
            'recursive' => -1
        ));
        foreach($all_currencies as $currency) {
            $this->js_vars['cfg']['currenies'][$currency['Currency']['id']]['code'] = $currency['Currency']['code'];
            $this->js_vars['cfg']['currenies'][$currency['Currency']['id']]['symbol'] = $currency['Currency']['symbol'];
            $currencies[$currency['Currency']['id']] = $currency['Currency']['code'] . '		 ' . $currency['Currency']['name'];
        }        
        $countries = $this->Product->User->Country->find('list');
        
        $shipment_costs = $this->Product->shipmentCostsOptions;
        $this->set('shipment_costs', $shipment_costs);
        $this->set(compact('currencies', 'countries', 'quantity_types'));
        // countries and region settings
        $groupCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class IS NULL'
            ) ,
            'recursive' => -1
        ));
        $groupUnionCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class' => 'Union'
            ) ,
            'recursive' => -1
        ));
        $groupContinentCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class' => 'Continent'
            ) ,
            'recursive' => -1
        ));
        $groupWorldwideCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class' => 'Worldwide'
            ) ,
            'recursive' => -1
        ));
        $shipCountries['Regions'] = $groupWorldwideCountries;
        $shipCountries['--------------------'] = $groupUnionCountries;
        $shipCountries['---------------------'] = $groupContinentCountries;
        $shipCountries['Individual countries'] = $groupCountries;
        $this->set('shipCountries', $shipCountries);
        foreach($groupWorldwideCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
        foreach($groupUnionCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
        foreach($groupContinentCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
        foreach($groupCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
    }
    function _sendProductVerifyMail($product_id)
    {
        $email = $this->EmailTemplate->selectTemplate('Verify Product');
        $site_url = Router::url('/', true);
        $site_logo_url = $site_url . 'img/logo.png';
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SUBJECT##' => $this->data['Product']['title'],
            '##YOUR_NAME##' => $this->data['User']['fullname'],
            '##PRODUCT_TITLE##' => $this->data['Product']['title'],
            '##PRODUCT_VERIFY_URL##' => Router::url(array(
                'controller' => 'products',
                'action' => 'verify',
                $product_id,
                'admin' => false
            ) , true) ,
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
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to product verify email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = $this->Product->changeFromEmail($this->data['User']['email']);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    function verify($id)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $this->pageTitle = __l('Verify Product');
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id = ' => $id
            ) ,
            'fields' => array(
                'Product.slug',
                'Product.title',
                'Product.is_verified',
                'Product.fullname',
                'Product.email',
                'Product.country_id',
                'Product.description',
                'Product.price',
                'Product.auto_detected_timezone_id',
                'Product.auto_detected_latitude',
                'Product.auto_detected_longitude',
                'Product.auto_detected_city_id',
                'Product.auto_detected_state_id',
                'Product.auto_detected_country_id',
            ) ,
			'contain' => array(
			   'Currency',
			),
            'recursive' => 1
        ));
        if (empty($product)) {
            $this->Session->setFlash(__l('No Authorisation to verify this product') , 'default', null, 'error');
        }
        $is_verified = 0;
        if (!empty($product['Product']['is_verified'])) {
            $is_verified = 1;
        } else {
            $user = $this->Product->User->find('first', array(
                'conditions' => array(
                    'User.email = ' => $product['Product']['email']
                ) ,
                'contain' => array() ,
                'fields' => array(
                    'User.id',
                ) ,
                'recursive' => -1
            ));
            // user auto detect details
            $this->data['User']['auto_detected_timezone_id'] = $product['Product']['auto_detected_timezone_id'];
            $this->data['User']['auto_detected_latitude'] = $product['Product']['auto_detected_latitude'];
            $this->data['User']['auto_detected_longitude'] = $product['Product']['auto_detected_longitude'];
            $this->data['User']['auto_detected_city_id'] = $product['Product']['auto_detected_city_id'];
            $this->data['User']['auto_detected_state_id'] = $product['Product']['auto_detected_state_id'];
            $this->data['User']['auto_detected_country_id'] = $product['Product']['auto_detected_country_id'];
            $this->data['User']['fullname'] = $product['Product']['fullname'];
            $this->data['User']['country_id'] = $product['Product']['country_id'];
            if (empty($user)) {
                $this->data['User']['is_agree_terms_conditions'] = 1;
                $this->data['User']['user_type_id'] = ConstUserTypes::User;
                $this->data['User']['email'] = $product['Product']['email'];
                $this->Product->User->save($this->data['User']);
                $user_id = $this->Product->User->id;
            } else {
                $user_id = $user['User']['id'];
                $this->data['User']['id'] = $user_id;
                $this->Product->User->save($this->data['User']);
            }
            $this->data['Product']['user_id'] = $user_id;
            $this->data['Product']['id'] = $id;
            $this->data['Product']['is_verified'] = 1;
            $this->Product->save($this->data['Product']);
            $this->_sendProductPlacedMail($product, $user_id);
            // update status
            $user = $this->Product->User->find('first', array(
                'conditions' => array(
                    'User.id = ' => $user_id
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
            $url = Router::url(array(
                'controller' => 'products',
                'action' => 'v',
                'slug' => $product['Product']['slug'],
                'view_type' => ConstViewType::NormalView
            ) , true);
            if (!empty($user['User']['fb_access_token']) && !empty($user['UserSetting']['fb_status_new_product'])) { // post on user facebook
                $this->_postOnFacebook($user, $product, 0, $url);
            }
            if (Configure::read('facebook.post_product_on_facebook')) { // post on site facebook
                $this->_postOnFacebook($user, $product, 1, $url);
            }
            if (!empty($user['User']['twitter_access_token']) && !empty($user['UserSetting']['twitter_status_new_product']) && !empty($user['User']['twitter_access_key'])) { // post on user twitter
                $this->_postOnTwitter($user, $product, 0, $url);
            }
            if (Configure::read('facebook.post_product_on_facebook')) { // post on site twitter
                $this->_postOnTwitter($user, $product, 1, $url);
            }
        }
        $this->set('product', $product);
        $this->set('is_verified', $is_verified);
    }
    function _postOnFacebook($user = null, $product, $is_admin = null, $url)
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.secrect_key') ,
            'cookie' => true
        ));
        if (!empty($is_admin)) {
            $facebook_dest_user_id = Configure::read('facebook.fb_user_id');
            $facebook_dest_access_token = Configure::read('facebook.fb_access_token');
        } else {
            $facebook_dest_user_id = $user['User']['fb_user_id'];
            $facebook_dest_access_token = $user['User']['fb_access_token'];
        }
        $messageFindReplace = array(
            '##PRODUCT_TITLE##' => $product['Product']['title'],
            '##SELLER_NAME##' => $user['User']['fullname'],
			'##PRODUCT_PRICE##' => $product['Product']['price'].$product['Currency']['symbol']
        );
        $message = strtr(Configure::read('product.new_product_status_message') , $messageFindReplace);
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
    function _postOnTwitter($user = null, $product, $is_admin = null, $url)
    {
        $messageFindReplace = array(
            '##PRODUCT_TITLE##' => $product['Product']['title'],
            '##SELLER_NAME##' => $user['User']['fullname'],
			'##PRODUCT_PRICE##' => $product['Product']['price'].$product['Currency']['symbol']
        );
        $message = strtr(Configure::read('product.new_product_status_message') , $messageFindReplace);
        if (!empty($is_admin)) {
            $twitter_access_token = Configure::read('twitter.site_user_access_token');
            $twitter_access_key = Configure::read('twitter.site_user_access_key');
        } else {
            $twitter_access_token = $user['User']['twitter_access_token'];
            $twitter_access_key = $user['User']['twitter_access_key'];
        }
		$message = 'via' . ' ' . '@' . Configure::read('twitter.username') . ': ' . $url . ' ' . $message;
        $xml = $this->OauthConsumer->post('Twitter', $twitter_access_token, $twitter_access_key, 'https://twitter.com/statuses/update.xml', array(
            'status' => $message
        ));
    }
    function _sendProductPlacedMail($product, $user_id)
    {
        $email = $this->EmailTemplate->selectTemplate('Product Placed');
        $product_url = Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $product['Product']['slug'],
            'view_type' => ConstViewType::NormalView,
            'admin' => false
        ) , true);
        $product_manage_url = Router::url(array(
            'controller' => 'users',
            'action' => 'dashboard',
            $user_id,
            'admin' => false
        ) , true);
        $product_connect_url = Router::url(array(
            'controller' => 'users',
            'action' => 'connect',
            $user_id,
            'admin' => false
        ) , true);
        $site_url = Router::url('/', true);
        $site_logo_url = $site_url . 'img/logo.png';
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SUBJECT##' => $product['Product']['title'],
            '##YOUR_NAME##' => $product['Product']['fullname'],
            '##PRODUCT_TITLE##' => $product['Product']['title'],
            '##PRODUCT_URL##' => $product_url,
            '##PRODUCT_MANAGE_URL##' => $product_manage_url,
            '##PRODUCT_CONNECT_URL##' => $product_connect_url,
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
            '##PAYMENT_URL##' => '#',
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to product placed email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = $this->Product->changeFromEmail($product['Product']['email']);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    function edit($id = null)
    {
        $this->pageTitle = __l('Edit Product');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
			$product = $this->Product->find('first', array(
                'conditions' => array(
                    'Product.id = ' => $id
                ) ,
				'recursive' => -1,
            ));			
            $this->Product->create();
            $ini_upload_error = $ini_upload_file_error = 1;
            $this->Product->ProductPhoto->Attachment->Behaviors->detach('ImageUpload');
            if (!empty($this->data['Attachment']['filename']['tmp_name'])) {
                if (!empty($this->data['Attachment']['filename']['error'])) {
                    $ini_upload_file_error = 0;
                }
                $this->data['Attachment']['filename']['type'] = get_mime($this->data['Attachment']['filename']['tmp_name']);
                $this->Product->Attachment->Behaviors->attach('ImageUpload', Configure::read('product.file'));
                $this->Product->Attachment->set($this->data['Attachment']);
                if (!$this->Product->Attachment->validates()) {
                    $ini_upload_file_error = 0;
                    $this->Session->setFlash(__l('Product could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Product->Attachment->validationErrors['filename'] = '';
            }
            $this->Product->User->set($this->data['User']);
            $this->Product->set($this->data['Product']);
            if($product['Product']['sold_quantity'] > 0 && $this->data['Product']['quantity'] != -1 && $product['Product']['sold_quantity'] > $this->data['Product']['quantity']){
				
				$this->Session->setFlash(__l('Product sold quantity is greater than given quantity. Please, try again.') , 'default', null, 'error');
				if($this->data['Product']['quantity'] > 1){
					$this->Product->validationErrors['quantity'] = __l('Invalid');
				} else{
					$this->Product->validationErrors['quantity_type'] = __l('Invalid');
				}				
			}
			else if (($this->Product->User->validates() &$this->Product->validates()) && ($ini_upload_error && $ini_upload_file_error)) {
                $this->Product->User->save($this->data['User'], false);
                $user_id = $this->Product->User->id;
				#update product table too
                $this->data['Product']['fullname'] = $this->data['User']['fullname'];
                $this->data['Product']['email'] = $this->data['User']['email'];
                $this->data['Product']['country_id'] = $this->data['User']['country_id'];
				if ($this->Product->save($this->data['Product'])) {
                    $product_id = $this->Product->id;
                    $product_upload_file = 0;
                    if (!empty($this->data['OldAttachment']['id']) && $this->data['OldAttachment']['id'] == 1) {
                        $this->Product->Attachment->del($this->data['Attachment']['id']);
                        $product_upload_file = 1;
                        $this->data['Product']['is_file'] = 0;
                    } else {
                        if (empty($this->data['Attachment']['id'])) {
                            $product_upload_file = 1;
                        }
                    }
                    if ($product_upload_file && !empty($this->data['Attachment']['filename']['name'])) {
                        $this->data['Attachment']['class'] = 'Product';
                        $this->data['Attachment']['foreign_id'] = $product_id;
                        $this->data['Attachment']['filename'] = $this->data['Attachment']['filename'];
                        $this->Attachment->create();
                        $this->Attachment->save($this->data);
                        $this->Product->Attachment->Behaviors->detach('ImageUpload');
                        $this->data['Product']['is_file'] = 1;
                    }
                    $this->Product->save($this->data['Product'], false);
                    $product_photo['ProductPhoto']['product_id'] = $product_id;
                    for ($i = 0; $i < Configure::read('product.max_upload_photo'); $i++) {
                        $upload_photo = 0;
                        if (!empty($this->data['ProductPhoto'][$i]['OldAttachment']['id']) && $this->data['ProductPhoto'][$i]['OldAttachment']['id'] == 1) {
                            $this->Product->ProductPhoto->Attachment->del($this->data['ProductPhoto'][$i]['Attachment']['id']);
                            $this->Product->ProductPhoto->del($this->data['ProductPhoto'][$i]['id']);
                            $upload_photo = 1;
                        } else {
                            if (empty($this->data['ProductPhoto'][$i]['Attachment']['id'])) {
                                $upload_photo = 1;
                            }
                        }
                        if ($upload_photo && !empty($this->data['ProductPhoto'][$i]['filename'])) {
                            $this->Product->ProductPhoto->create();
                            $product_photo['ProductPhoto']['caption'] = $this->data['ProductPhoto'][$i]['caption'];
                            if ($this->Product->ProductPhoto->save($product_photo['ProductPhoto'])) {
                                $file_id = $this->data['ProductPhoto'][$i]['filename'];
                                $product_photo_id = $this->Product->ProductPhoto->id;
                                $this->Product->ProductPhoto->Attachment->Behaviors->attach('ImageUpload', Configure::read('product_image.file'));
                                $this->Product->ProductPhoto->Attachment->enableUpload(false); //don't trigger upload behavior on save
                                $product_photo['Attachment']['class'] = 'ProductPhoto';
                                $product_photo['Attachment']['foreign_id'] = $product_photo_id;
                                $product_photo['Attachment']['mimetype'] = $_SESSION["product_file_info"][$file_id]['type'];
                                $product_photo['Attachment']['dir'] = 'ProductPhoto/' . $product_photo_id;
                                $upload_path = APP . DS . 'media' . DS . 'ProductPhoto' . DS . $product_photo_id;
                                new Folder($upload_path, true);
                                $file_name = $_SESSION["product_file_info"][$file_id]['filename'];
                                $product_photo['Attachment']['filename'] = $file_name;
                                $fp = fopen($upload_path . DS . $file_name, 'w');
                                fwrite($fp, base64_decode($_SESSION["product_file_info"][$file_id]['original']));
                                fclose($fp);
                                $this->Product->ProductPhoto->Attachment->create();
                                $this->Product->ProductPhoto->Attachment->save($product_photo);
                                $this->Product->ProductPhoto->Attachment->Behaviors->detach('ImageUpload');
                                unset($_SESSION["product_file_info"][$file_id]);
                            }
                        } elseif(!empty($this->data['ProductPhoto'][$i]['id'])) {
                            $_photoData['ProductPhoto']['id'] = $this->data['ProductPhoto'][$i]['id'];
                            $_photoData['ProductPhoto']['caption'] = $this->data['ProductPhoto'][$i]['caption'];
                            $this->Product->ProductPhoto->save($_photoData);
                        }
                    }
                    // save product shipment
                    $this->Product->ProductShipmentCost->deleteAll(array(
                        'ProductShipmentCost.product_id' => $this->Product->id
                    ));
                    if (!empty($this->data['Product']['is_shipment_cost_required'])) {
                        foreach($this->data['ProductShipmentCost'] as $productShipmentCost) {
                            $shipment_cost = array();
                            if (!empty($productShipmentCost['grouped_country_id'])) {
                                $shipment_cost['ProductShipmentCost'] = $productShipmentCost;
                                $shipment_cost['ProductShipmentCost']['product_id'] = $this->Product->id;
                                $this->Product->ProductShipmentCost->create();
                                $this->Product->ProductShipmentCost->save($shipment_cost['ProductShipmentCost']);
                            }
                        }
                    }
                    $this->Session->setFlash(__l('Product has been updated successfully.') , 'default', null, 'success');
                    if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                        $this->redirect(array(
                            'controller' => 'products',
                            'action' => 'index',
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'dashboard',
                            $this->data['User']['id'],
                            'admin' => false,
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Product could not be updated. Please, try again.') , 'default', null, 'error');
                }
            } else {
                if (!empty($attachmentValidationError)) {
                    foreach($attachmentValidationError as $key => $error) {
                        $this->Product->ProductPhoto->validationErrors[$key]['filename'] = $error['filename'];
                    }
                }
                $this->Session->setFlash(__l('Product could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Product->find('first', array(
                'conditions' => array(
                    'Product.id = ' => $id
                ) ,
                'contain' => array(
                    'User' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.name',
                            )
                        ) ,
						 'UserAvatar' => array(
							'fields' => array(
								'UserAvatar.id',
								'UserAvatar.filename',
								'UserAvatar.dir',
								'UserAvatar.width',
								'UserAvatar.height'
							)
						) ,
                        'fields' => array(
                            'User.id',
                            'User.fullname',
                            'User.email',
                            'User.country_id',
							'User.profile_image_id',
							'User.twitter_avatar_url',
							'User.fb_user_id',
							'User.twitter_avatar_url',
                        )
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                    'Currency',
                    'ProductPhoto' => array(
                        'Attachment' => array(
                            'fields' => array(
                                'Attachment.id',
                                'Attachment.filename',
                                'Attachment.dir',
                                'Attachment.width',
                                'Attachment.height'
                            )
                        ) ,
                    ) ,
                    'ProductTag' => array(
                        'fields' => array(
                            'ProductTag.name',
                            'ProductTag.slug',
                            'ProductTag.id'
                        )
                    ) ,
                    'ProductShipmentCost' => array(
                        'GroupedCountry' => array(
                            'fields' => array(
                                'GroupedCountry.name',
                            )
                        ) ,
                        'fields' => array(
                            'ProductShipmentCost.shipment_cost',
                        )
                    )
                ) ,
                'recursive' => 2,
            ));
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
            $this->data['Product']['quantity_type'] = ($this->data['Product']['quantity'] > 1) ? 2 : $this->data['Product']['quantity'];
            $this->data['Product']['tag'] = $this->Product->formatTags($this->data['ProductTag']);
        }
        // set site fee amount
        $site_min_fee = ($this->data['Product']['price']*(Configure::read('site.fee') /100));
        $this->data['Product']['site_fee_amount'] = ($site_min_fee < Configure::read('site.site_min_fee')) ? Configure::read('site.site_min_fee') : $site_min_fee;
        $this->pageTitle.= ' - ' . $this->data['Product']['title'];
        $quantity_types = array(
            '-1' => __l('unlimited') ,
            '1' => __l('single item') ,
            '2' => __l('a stock of') ,
        );       
        $all_currencies = $this->Product->Currency->find('all', array(
            'conditions' => array(
                'is_enabled' => 1
            ) ,
            'recursive' => -1
        ));
        foreach($all_currencies as $currency) {
            $this->js_vars['cfg']['currenies'][$currency['Currency']['id']]['code'] = $currency['Currency']['code'];
            $this->js_vars['cfg']['currenies'][$currency['Currency']['id']]['symbol'] = $currency['Currency']['symbol'];
            $currencies[$currency['Currency']['id']] = $currency['Currency']['code'] . '		 ' . $currency['Currency']['name'];
        }        
        $countries = $this->Product->User->Country->find('list');        
        $shipment_costs = $this->Product->shipmentCostsOptions;
        $this->set('shipment_costs', $shipment_costs);
        $this->set(compact('currencies', 'countries', 'quantity_types'));
        // countries and region settings
        $groupCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class IS NULL'
            ) ,
            'recursive' => -1
        ));
        $groupUnionCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class' => 'Union'
            ) ,
            'recursive' => -1
        ));
        $groupContinentCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class' => 'Continent'
            ) ,
            'recursive' => -1
        ));
        $groupWorldwideCountries = $this->Product->ProductShipmentCost->GroupedCountry->find('list', array(
            'conditions' => array(
                'GroupedCountry.related_class' => 'Worldwide'
            ) ,
            'recursive' => -1
        ));
        $shipCountries['Regions'] = $groupWorldwideCountries;
        $shipCountries['--------------------'] = $groupUnionCountries;
        $shipCountries['---------------------'] = $groupContinentCountries;
        $shipCountries['Individual countries'] = $groupCountries;
        $this->set('shipCountries', $shipCountries);
        foreach($groupWorldwideCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
        foreach($groupUnionCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
        foreach($groupContinentCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
        foreach($groupCountries as $key => $val) {
            $this->js_vars['cfg']['countries'][$key] = $val;
        }
    }
    function delete($id = null)
    {
        if (!empty($this->data['Product']['id'])) {
            $id = $this->data['Product']['id'];
        }
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id = ' => $id
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.fullname',
                        'User.email',
                    )
                ) ,
                'ProductPhoto' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                )
            ) ,
            'recursive' => 2,
        ));
        if (empty($product)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->Product->del($id)) {
                $this->Session->setFlash(__l('Product deleted') , 'default', null, 'success');
                $this->set('success', 1);
            } else {
                $this->cakeError('error404');
            }
        } else {
            $this->data['Product']['id'] = $id;
        }
        $this->set('product', $product);
    }
    function buy($id = null)
    {
        $this->pageTitle = __l('Buy Now');
        if (!empty($this->data['Transaction']['product_id'])) {
            $id = $this->data['Transaction']['product_id'];
        }
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id = ' => $id
            ) ,
            'contain' => array(
                'User' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                        )
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.fullname',
                        'User.email',
                    )
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
                'Currency',                
                'ProductShipmentCost' => array(
                    'GroupedCountry' => array(
                        'Union' => array(
                            'Country' => array(
                                'fields' => array(
                                    'Country.id',
                                    'Country.name'
                                )
                            ) ,
                            'fields' => array(
                                'Union.id',
                                'Union.name'
                            )
                        ) ,
                        'Continent' => array(
                            'Country' => array(
                                'fields' => array(
                                    'Country.id',
                                    'Country.name'
                                )
                            ) ,
                            'fields' => array(
                                'Continent.id',
                                'Continent.name'
                            )
                        )
                    )
                )
            ) ,
            'recursive' => 4,
        ));
        if (empty($product)) {
            $this->cakeError('error404');
        }
        $countries = array();
        $ProductShipmentDetails = array();
        if (!empty($product['Product']['is_shipment_cost_required'])) {
            if (!empty($product['Product']['product_shipment_cost_count'])) {
                if (!empty($product['ProductShipmentCost'])) {
                    $worldwide = $this->Product->ProductShipmentCost->find('all', array(
                        'conditions' => array(
                            'product_id' => $id,
                            'ProductShipmentCost.grouped_country_id' => ConsGroupedCountry::Worldwide
                        ) ,
                        'contain' => array(
                            'GroupedCountry' => array()
                        ) ,
                        'recursive' => 1
                    ));
                    if (!empty($worldwide)) {
                        $all_countries = $this->Product->Transaction->Country->find('all', array(
                            'recursive' => -1
                        ));
                        foreach($all_countries as $country) {
                            $ProductShipmentDetails[$country['Country']['id']]['shipment_cost'] = $worldwide[0]['ProductShipmentCost']['shipment_cost'];
                            $ProductShipmentDetails[$country['Country']['id']]['country'] = $country['Country']['name'];
                            $countries[$country['Country']['id']] = $country['Country']['name'];
                        }
                    }
                    foreach($product['ProductShipmentCost'] as $productShipmentCost) {
                        if (!empty($productShipmentCost['GroupedCountry'])) {
                            if (empty($productShipmentCost['GroupedCountry']['Union']) && empty($productShipmentCost['GroupedCountry']['Continent'])) {
                                $ProductShipmentDetails[$productShipmentCost['GroupedCountry']['id']]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                $ProductShipmentDetails[$productShipmentCost['GroupedCountry']['id']]['country'] = $productShipmentCost['GroupedCountry']['name'];
                                $countries[$productShipmentCost['GroupedCountry']['id']] = $productShipmentCost['GroupedCountry']['name'];
                            }
                            if (!empty($productShipmentCost['GroupedCountry']['Union'])) {
                                if (!empty($productShipmentCost['GroupedCountry']['Union']['Country'])) {
                                    foreach($productShipmentCost['GroupedCountry']['Union']['Country'] as $country) {
                                        $ProductShipmentDetails[$country['id']]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                        $ProductShipmentDetails[$country['id']]['country'] = $country['name'];
                                        $countries[$country['id']] = $country['name'];
                                    }
                                }
                            }
                            if (!empty($productShipmentCost['GroupedCountry']['Continent'])) {
                                if (!empty($productShipmentCost['GroupedCountry']['Continent']['Country'])) {
                                    foreach($productShipmentCost['GroupedCountry']['Continent']['Country'] as $country) {
                                        $ProductShipmentDetails[$country['id']]['shipment_cost'] = $productShipmentCost['shipment_cost'];
                                        $ProductShipmentDetails[$country['id']]['country'] = $country['name'];
                                        $countries[$country['id']] = $country['name'];
                                    }
                                }
                            }
                        }
                    }
                    $this->js_vars['cfg']['ShipmentCost'] = $ProductShipmentDetails;
                }
            } else {
                $all_countries = $this->Product->Transaction->Country->find('all', array(
                    'recursive' => -1
                ));
                foreach($all_countries as $country) {
                    $ProductShipmentDetails[$country['Country']['id']]['shipment_cost'] = 0;
                    $ProductShipmentDetails[$country['Country']['id']]['country'] = $country['Country']['name'];
                    $countries[$country['Country']['id']] = $country['Country']['name'];
                }
                $this->js_vars['cfg']['ShipmentCost'] = $ProductShipmentDetails;
            }
        }
        if (!empty($this->data)) {
            $this->Product->Transaction->set($this->data['Transaction']);
            if ($this->Product->Transaction->validates()) {
                $quantity = $product['Product']['quantity'];
                $sold_quantity = $product['Product']['sold_quantity'];
                $remain_quantity = $quantity-$sold_quantity;
                $purchase_quantity = $this->data['Transaction']['quantity'];
                if (($quantity > 0) && ($remain_quantity < $purchase_quantity)) {
                    $this->Session->setFlash(__l('Your quantity exceed the available quantity') , 'default', null, 'error');
                } else {
                    $transaction = $this->Product->setTransactionCalculation($purchase_quantity, $product);
                    $payment_gateway_id = ConstPaymentGateways::PayPal;
                    $paymentGateway = $this->Product->Transaction->PaymentGateway->find('first', array(
                        'conditions' => array(
                            'PaymentGateway.id' => $payment_gateway_id,
                        ) ,
                        'contain' => array(
                            'PaymentGatewaySetting' => array(
                                'fields' => array(
                                    'PaymentGatewaySetting.key',
                                    'PaymentGatewaySetting.test_mode_value',
                                    'PaymentGatewaySetting.live_mode_value',
                                ) ,
                            ) ,
                        ) ,
                        'recursive' => 1
                    ));
                    if (empty($paymentGateway)) {
                        $this->cakeError('error404');
                    }
                    $total_ship_cost = 0;
                    if (!empty($product['Product']['is_shipment_cost_required'])) {
                        if (!empty($ProductShipmentDetails)) {
                            $total_ship_cost = $ProductShipmentDetails[$this->data['Transaction']['country_id']]['shipment_cost'];
                            if ($product['Product']['is_shipment_cost_per_item_or_order'] == ConstShipmentCosts::Item) {
                                $total_ship_cost = $ProductShipmentDetails[$this->data['Transaction']['country_id']]['shipment_cost']*$this->data['Transaction']['quantity'];
                            }
                        }
                    }
                    $this->data['Transaction']['ship_amount'] = $total_ship_cost;
                    $this->data['Transaction']['currency_id'] = $product['Product']['currency_id'];                    
                    $this->data['Transaction']['amount'] = $transaction['amount']+$total_ship_cost;
                    $this->data['Transaction']['site_amount'] = $transaction['site_amount'];
                    $this->data['Transaction']['seller_amount'] = $transaction['seller_amount'];                    
                    $this->Product->Transaction->save($this->data['Transaction']);
                    $transaction_id = $this->Product->Transaction->id;
                    $this->pageTitle.= ' - ' . $paymentGateway['PaymentGateway']['name'];
                    if ($paymentGateway['PaymentGateway']['name'] == 'PayPal') {
                        if (!empty($paymentGateway['PaymentGatewaySetting'])) {
                            foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
                                $gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
                            }
                        }
                        $gateway_settings_options['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
                        $this->PaypalPlatform->settings($gateway_settings_options);
                        $actionType = "PAY";
                        $ipnNotificationUrl = Router::url(array(
                            'controller' => 'products',
                            'action' => 'processpayment',   
                            $transaction_id,
                            'admin' => false
                        ) , true);
                        $cancelUrl = Router::url(array(
                            'controller' => 'products',
                            'action' => 'payment_cancel',
                            $id,
                            'admin' => false
                        ) , true);
                        $returnUrl = Router::url(array(
                            'controller' => 'products',
                            'action' => 'payment_success',
                            $transaction_id,
                            'admin' => false
                        ) , true);
                        $currencyCode = $product['Currency']['code'];
                        if (Configure::read('product.payment_gateway_flow_id') == ConstPaymentGatewayFlow::BuyerSellerSite) {
                            $receiverEmailArray = array(
                                $product['User']['email'],
                                $gateway_settings_options['payee_account']
                            );
                            $receiverAmountArray = array(
                                $transaction['amount']+$total_ship_cost,
                                $transaction['site_amount']
                            );							
                        } else {
                            $receiverEmailArray = array(
                                $gateway_settings_options['payee_account'],
                                $product['User']['email']
                            );
                            $receiverAmountArray = array(
                                $transaction['amount']+$total_ship_cost,
                                $transaction['seller_amount']+$total_ship_cost
                            );							
                        }
                        $receiverPrimaryArray = array(
                            'true',
                            ''
                        );
						$feesPayer = $this->Product->_gatewayFeeSettings();
                        $receiverInvoiceIdArray = array(
                            md5('primary_' . date('YmdHis')) ,
                            md5('secondary1_' . date('YmdHis'))
                        );                        
                        $senderEmail = '';						
                        $memo = Configure::read('site.name') . ' - ' . $product['Product']['title'];
                        $pin = '';
                        $preapprovalKey = '';
                        $reverseAllParallelPaymentsOnError = '';
                        $trackingId = $this->PaypalPlatform->generateTrackingID();
                        // Make the Pay API call
                        $resArray = $this->PaypalPlatform->CallPay($actionType, $cancelUrl, $returnUrl, $currencyCode, $receiverEmailArray, $receiverAmountArray, $receiverPrimaryArray, $receiverInvoiceIdArray, $feesPayer, $ipnNotificationUrl, $memo, $pin, $preapprovalKey, $reverseAllParallelPaymentsOnError, $senderEmail, $trackingId);
                        $ack = strtoupper($resArray["responseEnvelope.ack"]);
                        if ($ack == "SUCCESS") {
                            if ('' == $preapprovalKey) {
                                // redirect for web approval flow
								$data['Transaction']['id'] = $this->Product->Transaction->id;
								$data['Transaction']['pay_key'] = $resArray["payKey"];
								$this->Product->Transaction->save($data['Transaction'], false);
                                $cmd = "cmd=_ap-payment&paykey=" . urldecode($resArray["payKey"]);
                                $this->PaypalPlatform->RedirectToPayPal($cmd);
                            } else {
                                // the Pay API call was made for an existing preapproval agreement so no approval flow follows
                                // payKey is the key that you can use to identify the result from this Pay call
                                $payKey = urldecode($resArray["payKey"]);
                                // paymentExecStatus is the status of the payment
                                $paymentExecStatus = urldecode($resArray["paymentExecStatus"]);
                                // note that in order to get the exact status of the transactions resulting from
                                // a Pay API call you should make the PaymentDetails API call for the payKey

                            }
                        } else {
                            //Display a user friendly Error on the page using any of the following error information returned by PayPal
                            //TODO - There can be more than 1 error, so check for "error(1).errorId", then "error(2).errorId", and so on until you find no more errors.
                            $ErrorCode = urldecode($resArray["error(0).errorId"]);
                            $ErrorMsg = urldecode($resArray["error(0).message"]);
                            $ErrorDomain = urldecode($resArray["error(0).domain"]);
                            $ErrorSeverity = urldecode($resArray["error(0).severity"]);
                            $ErrorCategory = urldecode($resArray["error(0).category"]);
                            $this->Session->setFlash($ErrorMsg . $ErrorSeverity . $ErrorCode . $ErrorDomain . $ErrorCategory, 'default', null, 'error');
                        }
                    }
                }
            }
        } else {
            $this->data['Transaction']['quantity'] = 1;
            $this->data['Transaction']['product_id'] = $id;
        }
        if (!empty($this->params['isAjax'])) {
            $this->layout = 'lightbox';
        }
        $this->set(compact('countries'));
        $this->set('product', $product);
    }
	function _saveIPNLog()
    {
        $this->loadModel('AdaptiveIpnLog');        
        $paypal_post_vars_in_str = '';
        foreach($_POST as $key => $value) {
            if ($key == 'transaction' && is_array($value)) {
                foreach($value as $k => $v) {
                    $v = urlencode(stripslashes($v));
                    $paypal_post_vars_in_str.= "&$k=$v";
                }
            } else {
                $value = urlencode(stripslashes($value));
                $paypal_post_vars_in_str.= "&$key=$value";
            }
        }
        $adaptiveIpnLog['post_variable'] = $paypal_post_vars_in_str;
        $adaptiveIpnLog['ip'] = RequestHandlerComponent::getClientIP();
        $this->AdaptiveIpnLog->create();
        $this->AdaptiveIpnLog->save($adaptiveIpnLog);
    }
	function _savePaidLog($transaction_id, $paymentDetails, $class = 'ProductTransaction', $receiver_count = 2)
    {
        $this->loadModel('AdaptiveTransactionLog');       		
        $adaptiveTransactionLog['foreign_id'] = $transaction_id;
        $adaptiveTransactionLog['class'] = $class;
        $adaptiveTransactionLog['timestamp'] = $paymentDetails['responseEnvelope.timestamp'];
        $adaptiveTransactionLog['ack'] = $paymentDetails['responseEnvelope.ack'];
        $adaptiveTransactionLog['correlation_id'] = $paymentDetails['responseEnvelope.correlationId'];
        $adaptiveTransactionLog['build'] = $paymentDetails['responseEnvelope.build'];
        $adaptiveTransactionLog['currency_code'] = $paymentDetails['currencyCode'];
        $adaptiveTransactionLog['sender_email'] = $paymentDetails['senderEmail'];
        $adaptiveTransactionLog['status'] = $paymentDetails['status'];
        $adaptiveTransactionLog['tracking_id'] = $paymentDetails['trackingId'];
        $adaptiveTransactionLog['pay_key'] = $paymentDetails['payKey'];
        $adaptiveTransactionLog['action_type'] = $paymentDetails['actionType'];
        $adaptiveTransactionLog['fees_payer'] = $paymentDetails['feesPayer'];
        $adaptiveTransactionLog['memo'] = $paymentDetails['memo'];
        $adaptiveTransactionLog['reverse_all_parallel_payments_on_error'] = $paymentDetails['reverseAllParallelPaymentsOnError'];
        for ($i = 0; $i < $receiver_count; $i++) {
            $adaptiveTransactionLog['transaction_id'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').transactionId'];
            $adaptiveTransactionLog['transaction_status'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').transactionStatus'];
            $adaptiveTransactionLog['amount'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.amount'];
            $adaptiveTransactionLog['email'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.email'];
            $adaptiveTransactionLog['primary'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.primary'];
            $adaptiveTransactionLog['invoice_id'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').receiver.invoiceId'];
            $adaptiveTransactionLog['refunded_amount'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').refundedAmount'];
            $adaptiveTransactionLog['pending_refund'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').pendingRefund'];
            $adaptiveTransactionLog['sender_transaction_id'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').senderTransactionId'];
            $adaptiveTransactionLog['sender_transaction_status'] = $paymentDetails['paymentInfoList.paymentInfo(' . $i . ').senderTransactionStatus'];
            $this->AdaptiveTransactionLog->create();
            $this->AdaptiveTransactionLog->save($adaptiveTransactionLog);
        }
    }
    function processpayment($transaction_id)
    {
        $this->_saveIPNLog();
		//paypal ipn
        $transaction = $this->Product->Transaction->find('first', array(
            'conditions' => array(
                'Transaction.id' => $transaction_id
            ) ,
            'contain' => array() ,
            'recursive' => -1
        ));
        if (empty($transaction)) {
            $this->cakeError('error404');
        }
		$product = $this->Product->find('first', array(
			'conditions' => array(
				'Product.id' => $transaction['Transaction']['product_id']
			) ,
			'contain' => array(
				'User' => array(
					'UserSetting' => array(
						'fields' => array(
							'UserSetting.id',
							'UserSetting.fb_status_product_sold',
							'UserSetting.twitter_status_product_sold',
						)
					) ,
					'fields' => array(
						'User.id',
						'User.fullname',
						'User.fb_access_token',
						'User.fb_user_id',
						'User.twitter_access_token',
						'User.twitter_access_key',
					)
				),
			   'Currency',
			) ,
			'recursive' => 2
		));
		if (empty($product)) {
			$this->cakeError('error404');
		}
        $gateway_id = ConstPaymentGateways::PayPal;
        $paymentGateway = $this->Product->Transaction->PaymentGateway->find('first', array(
            'conditions' => array(
                'PaymentGateway.id' => $gateway_id
            ) ,
            'contain' => array(
                'PaymentGatewaySetting' => array(
                    'fields' => array(
                        'PaymentGatewaySetting.key',
                        'PaymentGatewaySetting.test_mode_value',
                        'PaymentGatewaySetting.live_mode_value',
                    )
                )
            ) ,
            'recursive' => 1
        ));
		if (!empty($paymentGateway['PaymentGatewaySetting'])) {
			foreach($paymentGateway['PaymentGatewaySetting'] as $paymentGatewaySetting) {
				$gateway_settings_options[$paymentGatewaySetting['key']] = $paymentGateway['PaymentGateway']['is_test_mode'] ? $paymentGatewaySetting['test_mode_value'] : $paymentGatewaySetting['live_mode_value'];
			}
		}
		$gateway_settings_options['is_test_mode'] = $paymentGateway['PaymentGateway']['is_test_mode'];
		$this->PaypalPlatform->settings($gateway_settings_options);
		$payKey       = $transaction['Transaction']['pay_key'];
		$transactionId = '';
        $trackingId = '';
        $paymentDetails = $this->PaypalPlatform->CallPaymentDetails($payKey, $transactionId, $trackingId);
		
		if (strtoupper($paymentDetails["responseEnvelope.ack"]) == "SUCCESS") {
			$this->_savePaidLog($transaction_id, $paymentDetails);
		}
		if (($paymentDetails['status'] == ConstPaymentStatus::Incomplete || $paymentDetails['status'] == ConstPaymentStatus::Completed) && empty($transaction['Transaction']['is_sent'])) {
			 $this->data['Transaction']['id'] = $transaction_id;			 
			 $this->data['Transaction']['status'] = $paymentDetails['status'];
			 $this->data['Transaction']['sender_email'] = $paymentDetails['senderEmail'];
			 $this->data['Transaction']['is_sent'] = 1;
			 $this->Product->Transaction->save($this->data['Transaction'], false);
			 			 
			 $this->data['Product']['id'] = $product['Product']['id'];
			 $this->data['Product']['sold_quantity'] = $product['Product']['sold_quantity']+$transaction['Transaction']['quantity'];
			 $this->Product->save($this->data['Product'], false);
			 $this->_sendProductSoldMail($transaction_id);
			 $this->_sendProductPurchasedMail($transaction_id);
			 $url = Router::url(array(
				'controller' => 'products',
				'action' => 'v',
				'slug' => $product['Product']['slug'],
				'view_type' => ConstViewType::NormalView,
			) , true);
			if (!empty($product['User']['fb_access_token']) && !empty($product['User']['UserSetting']['fb_status_product_sold'])) {
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
					'##SELLER_NAME##' => $product['User']['fullname'],
					'##PRODUCT_PRICE##' => $product['Product']['price'].$product['Currency']['symbol']
				);
				$message = strtr(Configure::read('product.sold_product_status_message') , $messageFindReplace);
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
			if (!empty($product['User']['twitter_access_token']) && !empty($product['User']['UserSetting']['twitter_status_product_sold']) && !empty($product['User']['twitter_access_key'])) {
				$messageFindReplace = array(
					'##PRODUCT_TITLE##' => $product['Product']['title'],
					'##SELLER_NAME##' => $product['User']['fullname'],
					'##PRODUCT_PRICE##' => $product['Product']['price'].$product['Currency']['symbol']
				);
				$message = strtr(Configure::read('product.sold_product_status_message') , $messageFindReplace);
				$message = 'via' . ' ' . '@' . Configure::read('twitter.username') . ': ' . $url . ' ' . $message;
				$xml = $this->OauthConsumer->post('Twitter', $product['User']['twitter_access_token'], $product['User']['twitter_access_key'], 'https://twitter.com/statuses/update.xml', array(
					'status' => $message
				));
			}
		}	
		if($this->params['action'] == 'processpayment'){	      
			$this->autoRender = false;
		}
    }
    function _sendProductPurchasedMail($transaction_id)
    {
        $email = $this->EmailTemplate->selectTemplate('Your Purchase');
        $transaction = $this->Product->Transaction->find('first', array(
            'conditions' => array(
                'Transaction.id' => $transaction_id
            ) ,
            'contain' => array(
                'Product' => array(
                    'fields' => array(
                        'Product.id',
                        'Product.slug',
                        'Product.title',
                        'Product.is_file',
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.email',
                            'User.fullname',
                        )
                    ) ,
                    'Currency',
                ) ,
            ) ,
            'recursive' => 2
        ));
        $product_url = Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $transaction['Product']['slug'],
            'view_type' => ConstViewType::NormalView,
            'admin' => false
        ) , true);
        $download_url = '';
        if (!empty($transaction['Product']['is_file'])) {
            $download_link = Router::url(array(
                'controller' => 'products',
                'action' => 'download',
                $transaction_id,
                $transaction['Transaction']['download_count'],
                'admin' => false
            ) , true);
            $download_url = '<h3 style="font-size: 16px; padding: 5px 0px; margin: 0pt; font-family: Georgia,\'Times New Roman\',serif; font-weight: normal; color: rgb(51, 125, 171);"><a href="' . $download_link . '" style="color: rgb(51, 125, 171);" target="_blank" >' . __l('Download your file') . '</a></h3>';
        }
        $site_url = Router::url('/', true);
        $site_logo_url = $site_url . 'img/logo.png';
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SUBJECT##' => sprintf('Your purchase of: %dx "%s"', $transaction['Transaction']['quantity'], $transaction['Product']['title']) ,
            '##SELLER_NAME##' => $transaction['Product']['User']['fullname'],
            '##SELLER_EMAIL##' => $transaction['Product']['User']['email'],
            '##PRODUCT_TITLE##' => $transaction['Product']['title'],
            '##PRODUCT_URL##' => $product_url,
            '##DOWNLOAD_URL##' => $download_url,
            '##QUANTITY##' => $transaction['Transaction']['quantity'],
            '##CURRENCY_CODE##' => $transaction['Product']['Currency']['code'],
            '##TOTAL_AMOUNT##' => $transaction['Transaction']['amount'],
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
            '##PAYMENT_URL##' => '#',
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to product purchase email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = $this->Product->changeFromEmail($transaction['Transaction']['sender_email']);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    function _sendProductSoldMail($transaction_id)
    {
        $email = $this->EmailTemplate->selectTemplate('Product Sold');
        $transaction = $this->Product->Transaction->find('first', array(
            'conditions' => array(
                'Transaction.id' => $transaction_id
            ) ,
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                    )
                ) ,
                'Product' => array(
                    'fields' => array(
                        'Product.id',
                        'Product.slug',
                        'Product.title',
                        'Product.price',
                        'Product.quantity',
                        'Product.sold_quantity',
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.email',
                            'User.fullname',
                        )
                    ) ,
                    'Currency' ,
                ) ,
            ) ,
            'recursive' => 2
        ));
        $product_url = Router::url(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $transaction['Product']['slug'],
            'view_type' => ConstViewType::NormalView,
            'admin' => false
        ) , true);
        $product_delete_url = Router::url(array(
            'controller' => 'products',
            'action' => 'delete',
            $transaction['Product']['id'],
            'admin' => false
        ) , true);
        $product_edit_url = Router::url(array(
            'controller' => 'products',
            'action' => 'edit',
            $transaction['Product']['id'],
            'admin' => false
        ) , true);
        $site_url = Router::url('/', true);
        $site_logo_url = $site_url . 'img/logo.png';
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SUBJECT##' => sprintf('New order: %dx "%s"', $transaction['Transaction']['quantity'], $transaction['Product']['title']) ,
            '##YOUR_NAME##' => $transaction['Product']['User']['fullname'],
            '##YOUR_EMAIL##' => $transaction['Product']['User']['email'],
            '##PRODUCT_TITLE##' => $transaction['Product']['title'],
            '##PRODUCT_URL##' => $product_url,
            '##PRODUCT_EDIT_URL##' => $product_edit_url,
            '##PRODUCT_DELETE_URL##' => $product_delete_url,
            '##QUANTITY##' => $transaction['Transaction']['quantity'],
            '##CURRENCY_CODE##' => $transaction['Product']['Currency']['code'],
            '##TOTAL_AMOUNT##' => $transaction['Transaction']['amount'],
            '##AMOUNT##' => $transaction['Product']['price'],
            '##REMAIN_QUANTITY##' => ($transaction['Product']['quantity'] > 0) ? ($transaction['Product']['quantity']-$transaction['Product']['sold_quantity']) : 'unlimited',
            '##BUYER_NAME##' => $transaction['Transaction']['name'],
            '##BUYER_EMAIL##' => $transaction['Transaction']['sender_email'],
            '##BUYER_COUNTRY##' => $transaction['Country']['name'],
			'##ADDRESS1##' => !empty($transaction['Transaction']['address1'])?'<span style="width: 120px;">Address1:</span>'.$transaction['Transaction']['address1']:'',	
            '##ADDRESS2##' => !empty($transaction['Transaction']['address2'])?'<span style="width: 120px;">Address2:</span>'.$transaction['Transaction']['address2']:'',	
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
            '##PAYMENT_URL##' => '#',
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to product sold email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = $this->Product->changeFromEmail($transaction['Product']['User']['email']);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    function download($id = null, $download_count = null)
    {
        if (!empty($this->data['Transaction']['id'])) {
            $id = $this->data['Transaction']['id'];
        }
        if (!empty($this->data['Transaction']['id'])) {
            $download_count = $this->data['Transaction']['download_count'];
        }
        if (is_null($id) || !isset($download_count)) {
            $this->cakeError('error404');
        }
        $transaction = $this->Product->Transaction->find('first', array(
            'conditions' => array(
                'Transaction.id' => $id
            ) ,
            'contain' => array(
                'Product' => array(
                    'fields' => array(
                        'Product.id',
                        'Product.is_file',
                        'Product.slug',
                        'Product.title'
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                ) ,
            ) ,
            'recursive' => 2
        ));
        if (empty($transaction)) {
            $this->cakeError('error404');
        }
        $this->pageTitle = __l('Product file download');
        if (!empty($this->data)) {
            $this->Product->Transaction->set($this->data['Transaction']);
            if ($this->Product->Transaction->validates()) {
                if ($this->data['Transaction']['sender_email'] != $transaction['Transaction']['sender_email']) {
                    $this->set('error', 1);
                } else {
                    $transaction_array['Transaction']['is_downloaded'] = 0;
                    $this->Product->Transaction->save($transaction_array, false);
                    $this->_sendDownloadLinkMail($transaction);
                    $this->set('success', 1);
                }
            } else {
                $this->Session->setFlash(__l('Download request could not be completed. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $transaction;
            $this->data['Transaction']['sender_email'] = '';
        }
        if (!empty($transaction['Product']['is_file']) && empty($transaction['Transaction']['is_downloaded']) && $download_count == $transaction['Transaction']['download_count']) {
            $transaction_array['Transaction']['id'] = $transaction['Transaction']['id'];
            $transaction_array['Transaction']['is_downloaded'] = 1;
            $transaction_array['Transaction']['download_count'] = $transaction['Transaction']['download_count']+1;
            $this->Product->Transaction->save($transaction_array, false);
            $file_path = str_replace('\\', '/', 'media' . DS . 'Product' . DS . $transaction['Product']['id'] . DS . $transaction['Product']['Attachment']['filename']);
            $file_extension = explode('.', $transaction['Product']['Attachment']['filename']);
            // Code to download
            Configure::write('debug', 0);
            $this->view = 'Media';
            $this->autoLayout = false;
            $this->set('name', trim($file_extension[0]));
            $this->set('download', true);
            $this->set('extension', trim($file_extension[1]));
            $this->set('mimeType', array(
                $file_extension[1] => get_mime($dest_path . $file)
            ));
            $this->set('path', $file_path);
        } else {
            if (empty($transaction['Product']['is_file'])) {
                $this->Session->setFlash(__l('download failure. Please try once again.') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'products',
                    'action' => 'v',
                    'slug' => $transaction['Product']['slug'],
                    'view_type' => ConstViewType::NormalView
                ));
            }
        }
    }
    function _sendDownloadLinkMail($transaction)
    {
        $email = $this->EmailTemplate->selectTemplate('New Download');
        $download_link = Router::url(array(
            'controller' => 'products',
            'action' => 'download',
            $transaction['Transaction']['id'],
            $transaction['Transaction']['download_count'],
            'admin' => false
        ) , true);
        $site_url = Router::url('/', true);
        $site_logo_url = $site_url . 'img/logo.png';
        $emailFindReplace = array(
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/') ,
            '##SUBJECT##' => sprintf('New download link: \'%s\'', $transaction['Product']['title']) ,
            '##PRODUCT_TITLE##' => $transaction['Product']['title'],
            '##DOWNLOAD_URL##' => $download_url,
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
            '##PAYMENT_URL##' => '#',
            '##COPY_YEAR##' => date('Y') ,
        );
        // send to product new download email
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
        $this->Email->from = strtr($this->Email->from, $emailFindReplace);
        $this->Email->replyTo = strtr($this->Email->replyTo, $emailFindReplace);
        $this->Email->to = $this->Product->changeFromEmail($transaction['Transaction']['sender_email']);
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
    }
    function payment_success($transaction_id)
    {
        if (is_null($transaction_id)) {
            $this->cakeError('error404');
        }
        $transaction = $this->Product->Transaction->find('first', array(
            'conditions' => array(
                'Transaction.id' => $transaction_id
            ) ,
            'contain' => array(
                'Product' => array(
                    'fields' => array(
                        'Product.id',
                        'Product.slug',
                        'Product.title',
                        'Product.price',
                        'Product.is_file',
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.email',
                            'User.fullname',
                        )
                    ) ,
                    'Currency',
                ) ,
            ) ,
            'recursive' => 2
        ));
        if (empty($transaction)) {
            $this->cakeError('error404');
        }
		// call IPN metthod
		$this->processpayment($transaction_id);
        $this->set('transaction', $transaction);
        $this->pageTitle = __l('Payment Success');
    }
    function payment_cancel($product_id)
    {
        $this->pageTitle = __l('Payment Cancel');
        $this->Session->setFlash(__l('Transaction failure. Please try once again.') , 'default', null, 'error');
        $product = $this->Product->find('first', array(
            'conditions' => array(
                'Product.id = ' => $product_id
            ) ,
            'contain' => array() ,
            'fields' => array(
                'Product.slug',
            )
        ));
        $this->redirect(array(
            'controller' => 'products',
            'action' => 'v',
            'slug' => $product['Product']['slug'],
            'view_type' => ConstViewType::NormalView
        ));
    }
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q',
            'user_id',
        ));
        $this->pageTitle = __l('Products');
        $conditions = array();
        if (!empty($this->params['named']['user_id'])) {
            $conditions['Product.user_id'] = $this->params['named']['user_id'];
            $this->data['Product']['user_id'] = $this->params['named']['user_id'];
            $user = $this->Product->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->params['named']['user_id']
                ) ,
                'recursive' => -1,
                'fields' => 'User.fullname'
            ));
            if (!empty($user)) {
                $this->pageTitle.= sprintf(__l(' - %s') , $user['User']['fullname']);
            }
        }
        // check the filer passed through named parameter
        if (isset($this->params['named']['stat']) && $this->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Product.created) <= '] = 0;
            $this->pageTitle.= __l(' - Created today');
        }
        if (isset($this->params['named']['stat']) && $this->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Product.created) <= '] = 7;
            $this->pageTitle.= __l(' - Created in this week');
        }
        if (isset($this->params['named']['stat']) && $this->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Product.created) <= '] = 30;
            $this->pageTitle.= __l(' - Created in this month');
        }
        if (isset($this->params['named']['is_verified'])) {
            $this->pageTitle.= (!empty($this->params['named']['is_verified'])) ? __l(' - Verified') : __l(' - Unverified');
            $conditions['Product.is_verified'] = $this->params['named']['is_verified'];
        }
        if (isset($this->params['named']['is_spam'])) {
            $this->pageTitle.= __l(' - Spammed');
            $conditions['Product.spam_report_count >'] = '0';
        }
		if (isset($this->params['named']['is_abuse'])) {
            $this->pageTitle.= __l(' - Abused');
            $conditions['Product.abuse_report_count >'] = '0';
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Product.id' => 'desc'
            )
        );
        if (isset($this->params['named']['q']) && !empty($this->params['named']['q'])) {
            $this->paginate['search'] = $this->params['named']['q'];
            $this->data['Product']['q'] = $this->params['named']['q'];
        }
        $products_count['verified'] = $this->Product->find('count', array(
            'conditions' => array(
                'Product.is_verified' => 1
            ) ,
            'recursive' => -1
        ));
        $products_count['unverified'] = $this->Product->find('count', array(
            'conditions' => array(
                'Product.is_verified' => 0
            ) ,
            'recursive' => -1
        ));
        $products_count['spammed'] = $this->Product->find('count', array(
            'conditions' => array(
                'Product.spam_report_count >' => 0
            ) ,
            'recursive' => -1
        ));
        $products_count['abused'] = $this->Product->find('count', array(
            'conditions' => array(
                'Product.abuse_report_count >' => 0
            ) ,
            'recursive' => -1
        ));
        $moreActions = $this->Product->moreActions;
        $this->set(compact('moreActions'));
        $this->set('products_count', $products_count);
        $this->Product->recursive = 2;
        $this->set('products', $this->paginate());
        unset($this->Product->validate['user_id']);
    }
    function admin_add()
    {
        $this->setAction('add');
    }
    function admin_edit($id = null)
    {
        $this->setAction('edit', $id);
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->Product->del($id)) {
            $this->Session->setFlash(__l('Product deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>