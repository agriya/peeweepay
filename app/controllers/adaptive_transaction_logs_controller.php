<?php
class AdaptiveTransactionLogsController extends AppController
{
    var $name = 'AdaptiveTransactionLogs';
    function admin_index($class = '', $foreign_id = '') 
    {
        $this->pageTitle = __l('Adaptive Transaction Logs');
        $conditions = array();
        if (!empty($class)) {
            $conditions['AdaptiveTransactionLog.class'] = Inflector::classify($class);
        }
        if (!empty($foreign_id)) {
            $conditions['AdaptiveTransactionLog.foreign_id'] = $foreign_id;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'AdaptiveTransactionLog.id' => 'desc'
            ) ,
            'recursive' => 0
        );
        $this->set('adaptiveTransactionLogs', $this->paginate());
    }
    function admin_view($id = null) 
    {
        $this->pageTitle = __l('Adaptive Transaction Log');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $adaptiveTransactionLog = $this->AdaptiveTransactionLog->find('first', array(
            'conditions' => array(
                'AdaptiveTransactionLog.id = ' => $id
            ) ,
            'fields' => array(
                'AdaptiveTransactionLog.id',
                'AdaptiveTransactionLog.created',
                'AdaptiveTransactionLog.modified',
                'AdaptiveTransactionLog.class',
                'AdaptiveTransactionLog.foreign_id',
                'AdaptiveTransactionLog.transaction_id',
                'AdaptiveTransactionLog.amount',
                'AdaptiveTransactionLog.email',
                'AdaptiveTransactionLog.primary',
                'AdaptiveTransactionLog.invoice_id',
                'AdaptiveTransactionLog.refunded_amount',
                'AdaptiveTransactionLog.pending_refund',
                'AdaptiveTransactionLog.sender_transaction_id',
                'AdaptiveTransactionLog.sender_transaction_status',
                'AdaptiveTransactionLog.timestamp',
                'AdaptiveTransactionLog.ack',
                'AdaptiveTransactionLog.correlation_id',
                'AdaptiveTransactionLog.build',
                'AdaptiveTransactionLog.currency_code',
                'AdaptiveTransactionLog.sender_email',
                'AdaptiveTransactionLog.status',
                'AdaptiveTransactionLog.tracking_id',
                'AdaptiveTransactionLog.pay_key',
                'AdaptiveTransactionLog.action_type',
                'AdaptiveTransactionLog.fees_payer',
                'AdaptiveTransactionLog.memo',
                'AdaptiveTransactionLog.reverse_all_parallel_payments_on_error',
                'AdaptiveTransactionLog.refund_status',
                'AdaptiveTransactionLog.refund_net_amount',
                'AdaptiveTransactionLog.refund_fee_amount',
                'AdaptiveTransactionLog.refund_gross_amount',
                'AdaptiveTransactionLog.total_of_alll_refunds',
                'AdaptiveTransactionLog.refund_has_become_full',
                'AdaptiveTransactionLog.encrypted_refund_transaction_id',
                'AdaptiveTransactionLog.refund_transaction_status',
                'AdaptiveTransactionLog.paypal_post_vars',        
            ) ,
            'recursive' => -1,
        ));
        if (empty($adaptiveTransactionLog)) {
            $this->cakeError('error404');
        }
        $this->pageTitle.= ' - ' . $adaptiveTransactionLog['AdaptiveTransactionLog']['id'];
        $this->set('adaptiveTransactionLog', $adaptiveTransactionLog);
    }
}
?>