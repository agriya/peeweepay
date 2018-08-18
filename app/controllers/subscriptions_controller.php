<?php
class SubscriptionsController extends AppController
{
    var $name = 'Subscriptions';
    function add()
    {
        $this->pageTitle = __l('Add Subscription');
        if (!empty($this->data)) {
            $this->Subscription->create();
            if ($this->Subscription->save($this->data)) {
                $this->Session->setFlash(__l('Subscription has been added') , 'default', null, 'success');
                $ajax_url = Router::url('/', true);
                $success_msg = 'redirect*' . $ajax_url;
                echo $success_msg;
                exit;
            } else {
                $this->Session->setFlash(__l('Subscription could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'q',
        ));
        $conditions = array();
        if (isset($this->params['named']['q']) && !empty($this->params['named']['q'])) {
            $this->paginate['search'] = $this->params['named']['q'];
            $this->data['Subscription']['q'] = $this->params['named']['q'];
        }
        $this->pageTitle = __l('Subscriptions');
        $this->Subscription->recursive = 0;
        $this->set('subscriptions', $this->paginate());
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->Subscription->del($id)) {
            $this->Session->setFlash(__l('Subscription deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>