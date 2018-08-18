<?php
class TransactionsController extends AppController
{
    var $name = 'Transactions';
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q',
        ));
        $conditions = array();
        $conditions['Transaction.status'] = array(
            ConstPaymentStatus::Completed,
            ConstPaymentStatus::Incomplete
        );
        $this->pageTitle = __l('Transactions');
        if (!empty($this->params['named']['stat'])) {
            if (!empty($this->params['named']['stat'])) {
                if ($this->params['named']['stat'] == 'day') {
                    $conditions['TO_DAYS(NOW()) - TO_DAYS(Transaction.created) <='] = 0;
                    $this->pageTitle.= __l(' - Today');
                    $this->set('transaction_filter', __l('- Today'));
                    $days = 0;
                } else if ($this->params['named']['stat'] == 'week') {
                    $conditions['TO_DAYS(NOW()) - TO_DAYS(Transaction.created) <='] = 7;
                    $this->pageTitle.= __l(' - This Week');
                    $this->set('transaction_filter', __l('- This Week'));
                    $days = 7;
                } else if ($this->params['named']['stat'] == 'month') {
                    $conditions['TO_DAYS(NOW()) - TO_DAYS(Transaction.created) <='] = 30;
                    $this->pageTitle.= __l(' - This Month');
                    $this->set('transaction_filter', __l('- This Month'));
                    $days = 30;
                } else {
                    $this->pageTitle.= __l(' - Total');
                    $this->set('transaction_filter', __l('- Total'));
                }
            }
        }
        if (!empty($this->params['named']['product_id'])) {
            $conditions['Transaction.product_id'] = $this->params['named']['product_id'];
            $product = $this->Transaction->Product->find('first', array(
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
        if (!empty($this->params['named']['currency_id'])) {
            $conditions['Transaction.currency_id'] = $this->params['named']['currency_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Transaction.id' => 'desc'
            )
        );
        if (isset($this->params['named']['q']) && !empty($this->params['named']['q'])) {
            $this->paginate['search'] = $this->params['named']['q'];
            $this->data['Transaction']['q'] = $this->params['named']['q'];
        }
        $this->Transaction->recursive = 0;
        $this->set('transactions', $this->paginate());
    }
    function admin_view($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }

		$transaction = $this->Transaction->find('first', array(
            'conditions' => array(
                'Transaction.id = ' => $id
            ) ,
            'fields' => array(
                'Transaction.id',
                'Transaction.created',
                'Transaction.modified',
                'Transaction.product_id',
                'Transaction.currency_id',
                'Transaction.name',
                'Transaction.address1',
                'Transaction.address2',
                'Transaction.city',
                'Transaction.state',
                'Transaction.country_id',
                'Transaction.postal_code',
                'Transaction.quantity',
                'Transaction.amount',
                'Transaction.seller_amount',
                'Transaction.site_amount',
                'Transaction.ship_amount',
                'Transaction.status',
                'Transaction.sender_email',                                
                'Transaction.pay_key',                
                'Transaction.is_downloaded',
                'Transaction.download_count',
				'Product.title',
				'Product.slug',
				'Country.name',
				'Currency.code',
				'Currency.symbol',
				'Currency.prefix',
				'Currency.suffix',
				'Currency.decimals',
				'Currency.dec_point',
				'Currency.thousands_sep',
				'Currency.locale',
				'Currency.format_string',
				'Currency.grouping_algorithm_callback',
				'Currency.is_use_graphic_symbol',
            ) ,
            'recursive' => 0,
        ));
        if (empty($transaction)) {
            $this->cakeError('error404');
        }
        $this->pageTitle.= ' Transaction - ' . $transaction['Transaction']['id'];
        $this->set('transaction', $transaction);
    }
}
?>