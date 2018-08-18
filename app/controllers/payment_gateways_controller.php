<?php
class PaymentGatewaysController extends AppController
{
    var $name = 'PaymentGateways';
    function admin_index()
    {
        $this->pageTitle = __l('Payment Gateways');
        $this->paginate = array(
            'order' => array(
                'PaymentGateway.id' => 'desc'
            ) ,
            'recursive' => -1
        );
        $this->set('paymentGateways', $this->paginate());
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Payment Gateway');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->PaymentGateway->save($this->data)) {
                if (!empty($this->data['PaymentGatewaySetting'])) {
                    foreach($this->data['PaymentGatewaySetting'] as $key => $value) {
                        $this->PaymentGateway->PaymentGatewaySetting->updateAll(array(
                            'PaymentGatewaySetting.test_mode_value' => '\'' . trim($value['test_mode_value']) . '\'',
                            'PaymentGatewaySetting.live_mode_value' => '\'' . trim($value['live_mode_value']) . '\''
                        ) , array(
                            'PaymentGatewaySetting.id' => $key
                        ));
                    }
                }
                $this->Session->setFlash(__l('Payment Gateway has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Payment Gateway could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->PaymentGateway->read(null, $id);
            unset($this->data['PaymentGatewaySetting']);
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
        }
        $paymentGatewaySettings = $this->PaymentGateway->PaymentGatewaySetting->find('all', array(
            'conditions' => array(
                'PaymentGatewaySetting.payment_gateway_id' => $id
            ) ,
            'order' => array(
                'PaymentGatewaySetting.id' => 'asc'
            )
        ));
        if (!empty($this->data['PaymentGatewaySetting'])) {
            foreach($paymentGatewaySettings as $key => $paymentGatewaySetting) {
                $paymentGatewaySettings[$key]['PaymentGatewaySetting']['value'] = $this->data['PaymentGatewaySetting'][$paymentGatewaySetting['PaymentGatewaySetting']['id']]['value'];
            }
        }        
        $this->set(compact('paymentGatewaySettings'));
        $this->pageTitle.= ' - ' . $this->data['PaymentGateway']['name'];
    }
}
?>