<?php
class Product extends AppModel
{
    var $name = 'Product';
    var $displayField = 'title';
    var $actsAs = array(
        'Taggable',
        'Aggregatable'
    );
    var $aggregatingFields = array(
        'product_normal_view_count' => array(
            'mode' => 'real',
            'key' => 'product_id',
            'foreignKey' => 'product_id',
            'model' => 'ProductView',
            'function' => 'COUNT(ProductView.product_id)',
            'conditions' => array(
                'ProductView.product_view_type_id' => 1
            )
        ) ,
        'product_embed_view_count' => array(
            'mode' => 'real',
            'key' => 'product_id',
            'foreignKey' => 'product_id',
            'model' => 'ProductView',
            'function' => 'COUNT(ProductView.product_id)',
            'conditions' => array(
                'ProductView.product_view_type_id' => 2
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    var $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
        ) ,
        'Currency' => array(
            'className' => 'Currency',
            'foreignKey' => 'currency_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    var $hasOne = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'Product'
            ) ,
            'dependent' => true
        )
    );
    var $hasMany = array(
        'ProductPhoto' => array(
            'className' => 'ProductPhoto',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'ProductView' => array(
            'className' => 'ProductView',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'AbuseReport' => array(
            'className' => 'AbuseReport',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'SpamReport' => array(
            'className' => 'SpamReport',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'ProductShipmentCost' => array(
            'className' => 'ProductShipmentCost',
            'foreignKey' => 'product_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
    );
    var $hasAndBelongsToMany = array(
        'ProductTag' => array(
            'className' => 'ProductTag',
            'joinTable' => 'products_product_tags',
            'foreignKey' => 'product_id',
            'associationForeignKey' => 'product_tag_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'title' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'price' => array(
                'rule2' => array(
                    'rule' => array(
                        'comparison',
                        '>',
                        0
                    ) ,
                    'allowEmpty' => false,
                    'message' => __l('Should be greater than 0')
                ) ,
                'rule1' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                ) ,
            ) ,
            'currency_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,            
            'quantity' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'description' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        // shipment costs
        $this->moreActions = array(
            ConstMoreAction::Verified => __l('Verified') ,
            ConstMoreAction::Unverified => __l('Unverified') ,
            ConstMoreAction::Suspend => __l('Suspend') ,
            ConstMoreAction::Unsuspend => __l('Unsuspend') ,
            ConstMoreAction::Delete => __l('Delete') ,
        );
        $this->shipmentCostsOptions = array(
            ConstShipmentCosts::Order => __l('order') ,
            ConstShipmentCosts::Item => __l('item') ,
        );
    }
    function setTransactionCalculation($purchase_quantity, $product)
    {
        $total_amount = ($product['Product']['price']*$purchase_quantity);
        $site_amount = ((Configure::read('site.fee') /100) *$total_amount);
        if ($site_amount < Configure::read('site.site_min_fee')) {
            $site_amount = Configure::read('site.site_min_fee');
        }
		$site_amount =   round($site_amount, 2);
        $seller_amount = $total_amount-$site_amount;
               
        $transaction['amount'] = $total_amount;
        $transaction['site_amount'] = $site_amount;
        $transaction['seller_amount'] = $seller_amount;
                
		return $transaction;
    }
	function _gatewayFeeSettings()
    {
        $feesPayer = '';
        if (Configure::read('product.payment_gateway_flow_id') == ConstPaymentGatewayFlow::BuyerSellerSite) {
            if (Configure::read('product.payment_gateway_fee_id') == ConstPaymentGatewayFee::Seller) {
                $feesPayer = 'PRIMARYRECEIVER';
            } else if (Configure::read('product.payment_gateway_fee_id') == ConstPaymentGatewayFee::Site) {
                $feesPayer = 'SECONDARYONLY';
            } else if (Configure::read('product.payment_gateway_fee_id') == ConstPaymentGatewayFee::SiteAndSeller) {
                $feesPayer = 'EACHRECEIVER';
            }
        } else if (Configure::read('product.payment_gateway_flow_id') == ConstPaymentGatewayFlow::BuyerSiteSeller) {
            if (Configure::read('product.payment_gateway_fee_id') == ConstPaymentGatewayFee::Seller) {
                $feesPayer = 'SECONDARYONLY';
            } else if (Configure::read('product.payment_gateway_fee_id') == ConstPaymentGatewayFee::Site) {
                $feesPayer = 'PRIMARYRECEIVER';
            } else if (Configure::read('product.payment_gateway_fee_id') == ConstPaymentGatewayFee::SiteAndSeller) {
                $feesPayer = 'EACHRECEIVER';
            }
        }
        return $feesPayer;
    }
	function afterSave($created){
		$this->_updateProductCount();
		return true;
	}
	function afterDelete(){ 		
		$this->_updateProductCount();
		return true;
	}
	function _updateProductCount(){
		$product = $this->find('first', array(
			'conditions' => array(
				'Product.id = ' => $this->id
			) ,
			'fields' => array(
				'Product.email',
				'Product.user_id'
			),
			'recursive' => -1,
		));
		$user = $this->User->find('first', array(
			'conditions' => array(
				'User.email = ' => $product['Product']['email']
			) ,
			'fields' => array(
				'User.id',				
			),
			'recursive' => -1,
		));	
		if(!empty($user)){
			$product_verified_count = $this->find('count', array(
				'conditions' => array(
					'Product.email' => $product['Product']['email'],
					'Product.is_verified' => 1,
					'Product.is_admin_suspended ' => 0
				) ,				
			));
			$product_unverified_count = $this->find('count', array(
				'conditions' => array(
					'Product.email' => $product['Product']['email'],
					'Product.is_verified' => 0,
					'Product.is_admin_suspended ' => 0
				) ,				
			));
			$this->User->updateAll(array(
				'User.product_verified_count' => $product_verified_count,
				'User.product_unverified_count' => $product_unverified_count
			) , array(
				'User.id' => $user['User']['id']
			));			
		}
	}
}
?>