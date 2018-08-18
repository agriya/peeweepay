<?php
class PagesController extends AppController
{
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Page.id',
            'Page.Update',
            'Page.Add',
            'Page.content',
            'Page.description_meta_tag',
            'Page.parent_id',
            'Page.slug',
            'Page.status_option_id',
            'Page.title',
            'Page.Preview'
        );
        parent::beforeFilter();
    }
    function admin_add()
    {
        if (!empty($this->data)) {
            $this->Page->set($this->data);
            if ($this->Page->validates()) {
                $this->Page->save($this->data);
                $this->Session->setFlash(__l('Page has been created') , 'default', null, 'success');
                $page_id = $this->Page->getLastInsertId();
                if ($this->data['Page']['Preview']) {
                    $page_slug = $this->Page->find('first', array(
                        'conditions' => array(
                            'Page.id' => $page_id
                        ) ,
                        'fields' => array(
                            'Page.slug'
                        ) ,
                        'recursive' => 1
                    ));
                    $this->redirect(array(
                        'controller' => 'pages',
                        'action' => 'view',
                        'type' => 'preview',
                        $page_slug['Page']['slug']
                    ));
                } else $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Page could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $templates = array();
        $template_files = glob(APP . 'views' . DS . 'pages' . DS . 'themes' . DS . '*.ctp');
        if (!empty($template_files)) {
            foreach($template_files as $template_file) {
                $templates[basename($template_file) ] = basename($template_file);
            }
        }
        $statusOptions = $this->Page->statusOptions;
        $parentIdOptions = $this->Page->getListThreaded();
        $this->set(compact('parentIdOptions', 'templates', 'statusOptions'));
    }
    function admin_edit($id = null)
    {
        if (!empty($this->data)) {
            $this->Page->set($this->data);
            if ($this->Page->validates()) {
                $this->Page->save($this->data);
                $this->Session->setFlash(__l('Page has been Updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Page could not be Updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Page->read(null, $id);
        }
    }
    function admin_index()
    {
        $this->pageTitle = __l('Pages');
        $this->Page->recursive = -1;
        $this->paginate = array(
            'order' => array(
                'id' => 'DESC'
            )
        );
        $this->set('pages', $this->paginate());
    }
    function admin_delete($id = null, $cancelled = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->Page->del($id)) {
            $this->Session->setFlash(__l('Page Deleted Successfully') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index',
                $cancelled
            ));
        } else {
            $this->cakeError('error404');
        }
    }
    function admin_view($slug = null)
    {
        $this->setAction('view', $slug);
    }
    function view($slug = null)
    {
        $this->Page->recursive = -1;
        if (!empty($slug)) {
            $page = $this->Page->findBySlug($slug);
        } else {
            $page = $this->Page->find('first', array(
                'conditions' => array(
                    'Page.is_default' => 1
                )
            ));
        }
        $about_us_url = array(
            'controller' => 'users',
            'action' => 'login',
        );
        $pageFindReplace = array(
            '##CONTACT_EMAIL##' => Configure::read('site.contact_email') ,
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_URL##' => Router::url('/', true) ,
            '##PRESS_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'press',
                'admin' => false
            ) , true) ,
            '##PRESS_RESOURCE_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'press-resources',
                'admin' => false
            ) , true) ,
            '##PRESS_RELEASE_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'press-releases',
                'admin' => false
            ) , true) ,
            '##SUBSCRIPTION_URL##' => Router::url(array(
                'controller' => 'subscriptions',
                'action' => 'add',
                'admin' => false
            ) , true) ,
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add',
                'admin' => false
            ) , true) ,
            '##TERM_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'terms',
                'admin' => false
            ) , true) ,
            '##PRIVACY_URL##' => Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'privacy',
                'admin' => false
            ) , true) ,
            '##AUP_LINK##' => "<a href='" . Router::url(array(
                'controller' => 'pages',
                'action' => 'view',
                'aup'
            ) , true) . "' title='" . __l('Acceptable Use Policy') . "'>" . __l('Acceptable Use Policy') . "</a>"
        );
        if ($page) {
            $page['Page']['title'] = strtr($page['Page']['title'], $pageFindReplace);
            $page['Page']['content'] = strtr($page['Page']['content'], $pageFindReplace);
            $this->pageTitle = $page[$this->modelClass]['title'];
            $this->set('page', $page);
            $this->set('currentPageId', $page[$this->modelClass]['id']);
            $this->set('isPage', true);
            $this->_chooseTemplate($page);
        } else {
            $this->cakeError('error404');
        }
    }
    private function _chooseTemplate($page)
    {
        $render = 'view';
        if (!empty($page[$this->modelClass]['template'])) {
            $possibleThemeFile = APP . 'views' . DS . 'pages' . DS . 'themes' . DS . $page[$this->modelClass]['template'];
            if (file_exists($possibleThemeFile)) {
                $render = $possibleThemeFile;
            }
        }
        return $this->render($render);
    }
    function display() 
    {
        $path = func_get_args();
        $count = count($path);
        if (!$count) {
            $this->redirect('/');
        }
        $page = $subpage = $title = null;
        if (!empty($path[0])) {
            $page = $path[0];
        }
        if($path[0] == 'tools' && (!$this->Auth->user('id') || $this->Auth->user('user_type_id') != ConstUserTypes::Admin)) {
            $this->cakeError('error404');
        } else {
            $this->layout = 'admin';
        }
        if (!empty($path[1])) {
            $subpage = $path[1];
        }
        if (!empty($path[$count-1])) {
            $title = Inflector::humanize($path[$count-1]);
        }
        $this->set(compact('page', 'subpage', 'title'));
        $this->render(join('/', $path));
    }
    function admin_display($page) {
        $this->setAction('display', $page);
    }
}
