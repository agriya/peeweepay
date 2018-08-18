<?php
class CurrenciesController extends AppController
{
    var $name = 'Currencies';
    function admin_index()
    {
        $this->pageTitle = __l('Currencies');
        $this->_redirectGET2Named(array(
            'q',
        ));
        $this->paginate = array(
            'order' => array(
                'Currency.id' => 'desc'
            )
        );
        if (isset($this->params['named']['q']) && !empty($this->params['named']['q'])) {
            $this->paginate['search'] = $this->params['named']['q'];
            $this->data['Currency']['q'] = $this->params['named']['q'];
        }
        $this->Currency->recursive = 0;
        $moreActions = $this->Currency->moreActions;
        $this->set(compact('moreActions'));
        $this->set('currencies', $this->paginate());
    }
    function admin_add()
    {
        $this->pageTitle = __l('Add Currency');
        if (!empty($this->data)) {
            $this->Currency->create();
            if ($this->Currency->save($this->data)) {
                $this->Session->setFlash(__l('Currency has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Currency could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data['Currency']['is_enabled'] = 1;
        }
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit Currency');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->Currency->save($this->data)) {
                $this->Session->setFlash(__l('Currency has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Currency could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Currency->read(null, $id);
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
        }
        $this->pageTitle.= ' - ' . $this->data['Currency']['name'];
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->Currency->del($id)) {
            $this->Session->setFlash(__l('Currency deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>