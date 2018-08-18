<?php
class LanguagesController extends AppController
{
    var $name = 'Languages';
    function admin_index()
    {
        $this->_redirectGET2Named(array(
            'filter_id',
        ));
        $this->pageTitle = __l('Languages');
        $conditions = array();
        if (isset($this->params['named']['filter_id'])) {
            $this->data[$this->modelClass]['filter_id'] = $this->params['named']['filter_id'];
        }
        if (!empty($this->data[$this->modelClass]['filter_id'])) {
            if ($this->data[$this->modelClass]['filter_id'] == ConstMoreAction::Active) {
                $conditions[$this->modelClass . '.is_active'] = 1;
                $this->pageTitle.= __l(' - Approved');
            } else if ($this->data[$this->modelClass]['filter_id'] == ConstMoreAction::Inactive) {
                $conditions[$this->modelClass . '.is_active'] = 0;
                $this->pageTitle.= __l(' - Unapproved');
            }
            $this->params['named']['filter_id'] = $this->data[$this->modelClass]['filter_id'];
        }
        $this->Language->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'Language.name' => 'asc'
            )
        );
        $this->set('languages', $this->paginate());
        $filters = $this->Language->isFilterOptions;
        $moreActions = $this->Language->moreActions;
        $this->set(compact('moreActions', 'filters'));
    }
    function admin_update()
    {
        if (!empty($this->data[$this->modelClass])) {
            $r = $this->data[$this->modelClass]['r'];
            $actionid = $this->data[$this->modelClass]['more_action_id'];
            unset($this->data[$this->modelClass]['r']);
            unset($this->data[$this->modelClass]['more_action_id']);
            $selectedIds = array();
            foreach($this->data[$this->modelClass] as $primary_key_id => $is_checked) {
                if ($is_checked['id']) {
                    $selectedIds[] = $primary_key_id;
                }
            }
            if ($actionid && !empty($selectedIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 0
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked languages has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->{$this->modelClass}->updateAll(array(
                        $this->modelClass . '.is_active' => 1
                    ) , array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked languages has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    $this->{$this->modelClass}->deleteAll(array(
                        $this->modelClass . '.id' => $selectedIds
                    ));
                    $this->Session->setFlash(__l('Checked languages has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    function change_language()
    {
        if (!empty($this->data)) {
            if ($this->Auth->user('id')) {
                $this->Cookie->write('user_language', $this->data['Language']['language_id'], false);
            } else {
                $this->Cookie->write('user_language', $this->data['Language']['language_id'], false, time() +60*60*4);
            }
            $this->redirect(Router::url('/', true) . $this->data['Language']['r']);
        }
    }
}
?>