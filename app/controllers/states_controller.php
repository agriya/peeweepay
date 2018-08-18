<?php
class StatesController extends AppController
{
    var $name = 'States';
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'filter_id',
            'q'
        ));
        $this->pageTitle = __l('States');
        $conditions = array();
        $this->State->validate = array();
        if (isset($this->params['named']['filter_id'])) {
            $this->data[$this->modelClass]['filter_id'] = $this->params['named']['filter_id'];
        }
        if (!empty($this->data[$this->modelClass]['filter_id'])) {
            if ($this->data[$this->modelClass]['filter_id'] == ConstMoreAction::Active) {
                $this->pageTitle.= __l(' - Approved');
                $conditions[$this->modelClass . '.is_approved'] = 1;
            } else if ($this->data[$this->modelClass]['filter_id'] == ConstMoreAction::Inactive) {
                $this->pageTitle.= __l(' - Unapproved');
                $conditions[$this->modelClass . '.is_approved'] = 0;
            }
            $this->params['named']['filter_id'] = $this->data[$this->modelClass]['filter_id'];
        }
        if (isset($this->params['named']['q'])) {
            $this->data['State']['q'] = $this->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->params['named']['q']);
        }
        $this->State->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'State.id',
                'State.name',
                'State.code',
                'State.adm1code',
                'State.is_approved',
                'Country.name'
            ) ,
            'order' => array(
                'State.name' => 'asc'
            ) ,
            'limit' => 15,
        );
        if (isset($this->data['State']['q'])) {
            $this->paginate['search'] = $this->data['State']['q'];
        }
        $this->set('states', $this->paginate());
        $this->set('pending', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 0
            )
        )));
        $this->set('approved', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 1
            )
        )));
        $filters = $this->State->isFilterOptions;
        $moreActions = $this->State->moreActions;
        $this->set(compact('filters', 'moreActions'));
    }
    function admin_add()
    {
        $this->pageTitle = __l('Add State');
        if (!empty($this->data)) {
            $this->State->create();
            if ($this->State->save($this->data)) {
                $this->Session->setFlash(__l('State has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data['State']['is_approved'] = 1;
        }
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit State');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->State->save($this->data)) {
                $this->Session->setFlash(__l('State has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->State->read(null, $id);
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
        }
        $this->pageTitle.= ' - ' . $this->data['State']['name'];
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
    }
    // To change approve/disapprove status by admin
    function admin_update_status($id = null, $status = null)
    {
        if (is_null($id) || is_null($status)) {
            $this->cakeError('error404');
        }
        $this->data['State']['id'] = $id;
        if ($status == 'disapprove') {
            $this->data['State']['is_approved'] = 0;
        }
        if ($status == 'approve') {
            $this->data['State']['is_approved'] = 1;
        }
        $this->State->save($this->data);
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    function admin_update()
    {
        if (!empty($this->data['State'])) {
            $r = $this->data[$this->modelClass]['r'];
            $actionid = $this->data[$this->modelClass]['more_action_id'];
            unset($this->data[$this->modelClass]['r']);
            unset($this->data[$this->modelClass]['more_action_id']);
            $stateIds = array();
            foreach($this->data['State'] as $state_id => $is_checked) {
                if ($is_checked['id']) {
                    $stateIds[] = $state_id;
                }
            }
            if ($actionid && !empty($stateIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->State->updateAll(array(
                        'State.is_approved' => 0
                    ) , array(
                        'State.id' => $stateIds
                    ));
                    $this->Session->setFlash(__l('Checked states has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->State->updateAll(array(
                        'State.is_approved' => 1
                    ) , array(
                        'State.id' => $stateIds
                    ));
                    $this->Session->setFlash(__l('Checked states has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    $this->State->deleteAll(array(
                        'State.id' => $stateIds
                    ));
                    $this->Session->setFlash(__l('Checked states has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->State->del($id)) {
            $this->Session->setFlash(__l('State deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>