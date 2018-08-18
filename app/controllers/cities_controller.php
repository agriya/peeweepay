<?php
class CitiesController extends AppController
{
    var $name = 'Cities';
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'filter_id',
            'q'
        ));
        $this->disableCache();
        $this->pageTitle = __l('Cities');
        $conditions = array();
        $this->City->validate = array();
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
            $this->data['City']['q'] = $this->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->params['named']['q']);
        }
        $this->City->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'City.id',
                'City.name',
                'City.latitude',
                'City.longitude',
                'City.timezone',
                'City.county',
                'City.code',
                'City.is_approved',
                'State.name',
                'Country.name',
            ) ,
            'order' => array(
                'City.name' => 'asc'
            ) ,
            'limit' => 15
        );
        if (isset($this->data['City']['q'])) {
            $this->paginate['search'] = $this->data['City']['q'];
        }
        $this->set('cities', $this->paginate());
        $this->set('pending', $this->City->find('count', array(
            'conditions' => array(
                'City.is_approved = ' => 0
            )
        )));
        $this->set('approved', $this->City->find('count', array(
            'conditions' => array(
                'City.is_approved = ' => 1
            )
        )));
        $filters = $this->City->isFilterOptions;
        $moreActions = $this->City->moreActions;
        $this->set(compact('filters', 'moreActions'));
    }
    function admin_edit($id = null)
    {
        $this->pageTitle = __l('Edit City');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->City->save($this->data)) {
                $this->Session->setFlash(__l('City has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('City could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->City->read(null, $id);
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
        }
        $this->pageTitle.= ' - ' . $this->data['City']['name'];
        $countries = $this->City->Country->find('list');
        $states = $this->City->State->find('list', array(
            'conditions' => array(
                'State.is_approved' => 1
            )
        ));
        $this->set(compact('countries', 'states'));
    }
    function admin_add()
    {
        $this->pageTitle = __l('Add City');
        if (!empty($this->data)) {
            $this->data['City']['is_approved'] = 1;
            $this->City->create();
            if ($this->City->save($this->data)) {
                $this->Session->setFlash(__l(' City has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' City could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data['City']['is_approved'] = 1;
        }
        $countries = $this->City->Country->find('list');
        $states = $this->City->State->find('list', array(
            'conditions' => array(
                'State.is_approved =' => 1
            ) ,
            'order' => array(
                'State.name'
            )
        ));
        $this->set(compact('countries', 'states'));
    }
    // To change approve/disapprove status by admin
    function admin_update_status($id = null, $status = null)
    {
        if (is_null($id) || is_null($status)) {
            $this->cakeError('error404');
        }
        $this->data['City']['id'] = $id;
        if ($status == 'disapprove') {
            $this->data['City']['is_approved'] = 0;
        }
        if ($status == 'approve') {
            $this->data['City']['is_approved'] = 1;
        }
        $this->City->save($this->data);
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    function admin_update()
    {
        if (!empty($this->data['City'])) {
            $r = $this->data[$this->modelClass]['r'];
            $actionid = $this->data[$this->modelClass]['more_action_id'];
            unset($this->data[$this->modelClass]['r']);
            unset($this->data[$this->modelClass]['more_action_id']);
            $cityIds = array();
            foreach($this->data['City'] as $city_id => $is_checked) {
                if ($is_checked['id']) {
                    $cityIds[] = $city_id;
                }
            }
            if ($actionid && !empty($cityIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->City->updateAll(array(
                        'City.is_approved' => 0
                    ) , array(
                        'City.id' => $cityIds
                    ));
                    $this->Session->setFlash(__l('Checked cities has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->City->updateAll(array(
                        'City.is_approved' => 1
                    ) , array(
                        'City.id' => $cityIds
                    ));
                    $this->Session->setFlash(__l('Checked cities has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    $this->City->deleteAll(array(
                        'City.id' => $cityIds
                    ));
                    $this->Session->setFlash(__l('Checked cities has been deleted') , 'default', null, 'success');
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
        if ($this->City->del($id)) {
            $this->Session->setFlash(__l('City deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
}
?>