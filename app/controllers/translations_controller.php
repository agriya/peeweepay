<?php
class TranslationsController extends AppController
{
    var $name = 'Translations';
    function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Translation.googleTranslate',
            'Translation.manualTranslate',
            'Translation.from_language',
            'Translation.makeUpdate',
            'Translation.makeSubmit'
        );
        parent::beforeFilter();
    }
    function admin_index()
    {
        $pageTitle = __l('Translations');
        if (!empty($this->params['named']['remove_language_id'])) {
            if ($this->params['named']['remove_language_id'] == '38') {
                $this->cakeError('error404');
            }
            $this->Translation->deleteAll(array(
                'Translation.language_id' => $this->params['named']['remove_language_id']
            ));
            $lang_code = $this->Translation->Language->find('first', array(
                'conditions' => array(
                    'Language.id' => $this->params['named']['remove_language_id']
                ) ,
                'fields' => array(
                    'Language.iso2'
                ) ,
                'recursive' => -1
            ));
            Cache::delete($lang_code['Language']['iso2'] . '_translations');
            $this->Session->setFlash(__l('Translation deleted successfully') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        $translations_verified = $this->Translation->find('all', array(
            'fields' => array(
                'Language.name',
                'Translation.language_id',
                'Translation.key',
                'Translation.is_translated',
                'Translation.is_google_translate',
                'COUNT(Translation.is_verified) as counts',
                'Translation.is_verified',
            ) ,
            'conditions' => array(
                'Translation.language_id !=' => 0
            ) ,
            'group' => 'Translation.language_id, Translation.is_verified'
        ));
        $languageArr = array();
        foreach($translations_verified as $transaltion) {
            if (!array_key_exists($transaltion['Translation']['language_id'], $languageArr)) {
                $languageArr[$transaltion['Translation']['language_id']] = array(
                    'name' => $transaltion['Language']['name'],
                    'verified' => 0,
                    'not_verified' => 0
                );
            }
            if ($transaltion['Translation']['is_verified']) {
                $languageArr[$transaltion['Translation']['language_id']]['verified'] = $transaltion[0]['counts'];
            } else {
                $languageArr[$transaltion['Translation']['language_id']]['not_verified'] = $transaltion[0]['counts'];
            }
        }
        $this->set('translations', $languageArr);
        $this->set('title_for_layout', $pageTitle);
    }
    function admin_view($id = null)
    {
        $pageTitle = __l('Translation');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        $translation = $this->Translation->find('first', array(
            'conditions' => array(
                'Translation.id = ' => $id
            ) ,
            'fields' => array(
                'Translation.id',
                'Translation.created',
                'Translation.modified',
                'Translation.language_id',
                'Translation.key',
                'Translation.lang_text',
                'Language.id',
                'Language.created',
                'Language.modified',
                'Language.name',
                'Language.iso2',
                'Language.iso3',
            ) ,
            'recursive' => 0,
        ));
        if (empty($translation)) {
            $this->cakeError('error404');
        }
        $pageTitle.= ' - ' . $translation['Translation']['id'];
        $this->set('translation', $translation);
        $this->set('title_for_layout', $pageTitle);
    }
    function admin_add_text()
    {
        $pageTitle = __l('Add New Language Variable');
        if (!empty($this->data)) {
            $valid = true;
            $key = $this->data['Translation']['key'];
            unset($this->data['Translation']['key']);
            foreach($this->data['Translation'] as $translation_id => $translation_vars) {
                $data = array();
                $data['Translation']['language_id'] = $translation_id;
                $data['Translation']['key'] = $key;
                $data['Translation']['lang_text'] = $translation_vars['lang_text'];
                $this->Translation->set($data);
                if (!$this->Translation->validates()) {
                    $valid = false;
                    if ($this->Translation->validationErrors['lang_text'] == 'Required') {
                        $this->Translation->validationErrors[$translation_id]['lang_text'] = 'Required';
                    }
                }
            }
            if ($valid) {
                foreach($this->data['Translation'] as $translation_id => $translation_vars) {
                    $data = array();
                    $data['Translation']['language_id'] = $translation_id;
                    $data['Translation']['key'] = $key;
                    $data['Translation']['lang_text'] = $translation_vars['lang_text'];
                    $data['Translation']['is_translated'] = 1;
                    $data['Translation']['is_verified'] = 1;
                    $this->Translation->create();
                    $this->Translation->set($data);
                    $this->Translation->save($data);
                }
                $this->Session->setFlash(__l('Language variables has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Language variables could not be added') , 'default', null, 'error');
            }
        }
        $translations = $this->Translation->find('all', array(
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name'
            ) ,
            'conditions' => array(
                'Translation.language_id !=' => 0
            )
        ));
        if (!empty($key)) {
            $this->data['Translation']['key'] = $key;
        }
        foreach($translations as $translation) {
            $languages[$translation['Translation']['language_id']] = $translation['Language']['name'];
        }
        $this->set(compact('languages'));
        $this->set('title_for_layout', $pageTitle);
    }
    function admin_add()
    {
        $pageTitle = __l('Add Translation');
        $is_success_translations = 0;
        $translations = $this->Translation->find('all', array(
            'conditions' => array(
                'Language.iso2' => 'en'
            ) ,
            'recursive' => 0
        ));
        if (empty($translations)) {
            $this->Session->setFlash(__l('Default English variable is missing') , 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
        if (!empty($this->data)) {
            if (!empty($this->data['Translation']['googleTranslate']) && !empty($this->data['Translation']['language_id'])) {
                $new_language = $this->Translation->Language->find('first', array(
                    'conditions' => array(
                        'Language.id' => $this->data['Translation']['language_id']
                    ) ,
                    'fields' => array(
                        'Language.iso2'
                    ) ,
                    'recursive' => -1
                ));
                for ($i = 0; $i < count($translations); $i+= 20) {
                    $key = '';
                    for ($j = $i; $j < $i+20; $j++) {
                        if (isset($translations[$j]['Translation']['key'])) {
                            $key.= 'q=' . urlencode($translations[$j]['Translation']['key']) . '&';
                        }
                    }
                    $ch = curl_init();
                    curl_setopt($ch, CURLOPT_URL, 'http://ajax.googleapis.com/ajax/services/language/translate?v=1.0&' . $key . 'langpair=en%7C' . $new_language['Language']['iso2']);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    $out = curl_exec($ch);
                    if (!curl_errno($ch)) {
                        $var = json_decode($out);
                        curl_close($ch);
                    } else {
                        $this->Session->setFlash(__l('Translation could not be updated. Please, try again.') , 'default', null, 'error');
                        $this->redirect(array(
                            'action' => 'add',
                        ));
                    }
                    if ($var->responseStatus == 200) {
                        $j = $i;
                        $is_success_translations = 1;
                        foreach($var->responseData as $translated_arr) {
                            if ($translated_arr->responseStatus == 200) {
                                $this->data['Translation']['language_id'] = $this->data['Translation']['language_id'];
                                $this->data['Translation']['key'] = $translations[$j]['Translation']['key'];
                                $this->data['Translation']['lang_text'] = $translated_arr->responseData->translatedText;
                                $this->data['Translation']['is_translated'] = 1;
                                $this->data['Translation']['is_google_translate'] = 1;
                                $this->Translation->create();
                                $this->Translation->save($this->data);
                            }
                            $j++;
                        }
                    }
                }
            } elseif (!empty($this->data['Translation']['manualTranslate']) && !empty($this->data['Translation']['language_id'])) {
                foreach($translations as $translation) {
                    unset($translation['Translation']['id']);
                    $translation['Translation']['language_id'] = $this->data['Translation']['language_id'];
                    $translation['Translation']['lang_text'] = '';
                    $translation['Translation']['is_translated'] = 1;
                    $this->Translation->create();
                    $this->Translation->save($translation);
                }
            }
            if (!empty($is_success_translations)) {
                $this->Session->setFlash(__l('Translation has been added') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('No translations available for the selected language.') , 'default', null, 'error');
            }
            $this->redirect(array(
                'action' => 'manage',
                'language_id' => $this->data['Translation']['language_id']
            ));
        }
        $existTranslations = $this->Translation->find('all', array(
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name'
            )
        ));
        $languages = $this->Translation->Language->find('list', array(
            'conditions' => array(
                'Language.is_active' => 1
            )
        ));
        $exists = array();
        if (!empty($existTranslations)) {
            foreach($existTranslations as $existTranslation) {
                $exists[] = $existTranslation['Translation']['language_id'];
                unset($languages[$existTranslation['Translation']['language_id']]);
            }
            $exists[] = array_search('English', $languages);
            unset($languages[array_search('English', $languages) ]);
        }
        $this->set(compact('languages'));
        $this->set('title_for_layout', $pageTitle);
    }
    function admin_edit($id = null)
    {
        $pageTitle = __l('Edit Translation');
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if (!empty($this->data)) {
            if ($this->Translation->save($this->data)) {
                $this->Session->setFlash(sprintf(__l('"%s" Translation has been updated') , $this->data['Translation']['id']) , 'default', null, 'success');
            } else {
                $this->Session->setFlash(sprintf(__l('"%s" Translation could not be updated. Please, try again.') , $this->data['Translation']['id']) , 'default', null, 'error');
            }
        } else {
            $this->data = $this->Translation->read(null, $id);
            if (empty($this->data)) {
                $this->cakeError('error404');
            }
        }
        $pageTitle.= ' - ' . $this->data['Translation']['id'];
        $languages = $this->Translation->Language->find('list', array(
            'conditions' => array(
                'Language.is_active' => 1
            )
        ));
        $this->set(compact('languages'));
        $this->set('title_for_layout', $pageTitle);
    }
    function admin_delete($id = null)
    {
        if (is_null($id)) {
            $this->cakeError('error404');
        }
        if ($this->Translation->delete($id)) {
            $this->Session->setFlash(__l('Translation deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            $this->cakeError('error404');
        }
    }
    function admin_manage()
    {
        $pageTitle = __l('Edit Translations');
        if (empty($this->params['named']['language_id']) and empty($this->data['Translation']['language_id'])) {
            $this->cakeError('error404');
        }
        if (!empty($this->params['named']['language_id'])) {
            $this->data['Translation']['language_id'] = $this->params['named']['language_id'];
        }
        if (!empty($this->params['named']['filter'])) {
            $this->data['Translation']['filter'] = $this->params['named']['filter'];
        }
        $conditions = array();
        if (!empty($this->data)) {
            if (!empty($this->data['Translation']['language_id'])) {
                $this->params['named']['language_id'] = $this->data['Translation']['language_id'];
            }
            if (!empty($this->data['Translation']['filter'])) {
                $this->params['named']['filter'] = $this->data['Translation']['filter'];
            }
            if (!empty($this->data['Translation']['q'])) {
                $this->params['named']['q'] = $this->data['Translation']['q'];
            }
            if (!empty($this->data['Translation']['makeUpdate'])) {
                unset($this->data['Translation']['makeUpdate']);
                unset($this->data['Translation']['language_id']);
                unset($this->data['Translation']['filter']);
                unset($this->data['Translation']['q']);
                foreach($this->data['Translation'] as $key => $value) {
                    $this->Translation->id = $key;
                    $data['Translation']['lang_text'] = $value['lang_text'];
                    $data['Translation']['is_verified'] = $value['is_verified'];
                    $this->Translation->save($data);
                }
                $this->Session->setFlash(__l('Translation updated successfully') , 'default', null, 'success');
            }
            if (!empty($this->params['named']['language_id'])) {
                $conditions['Translation.language_id'] = $this->params['named']['language_id'];
                $this->data['Translation']['language_id'] = $this->params['named']['language_id'];
            }
            if (!empty($this->params['named']['filter'])) {
                if ($this->params['named']['filter'] == 'verified') {
                    $conditions['Translation.is_verified'] = 1;
                    $pageTitle.= __l(' - Verified ');
                } else if ($this->params['named']['filter'] == 'unverified') {
                    $conditions['Translation.is_verified'] = 0;
                    $pageTitle.= __l(' - Unverified ');
                }
                $this->data['Translation']['filter'] = $this->params['named']['filter'];
            }
            if (!empty($this->params['named']['q'])) {
                $conditions['OR']['Translation.key LIKE '] = '%' . $this->params['named']['q'] . '%';
                $conditions['OR']['Translation.lang_text LIKE '] = '%' . $this->params['named']['q'] . '%';
                $this->data['Translation']['q'] = $this->params['named']['q'];
            }
            $lang_code = $this->Translation->Language->find('first', array(
                'conditions' => array(
                    'Language.id' => $this->data['Translation']['language_id']
                ) ,
                'fields' => array(
                    'Language.iso2'
                ) ,
                'recursive' => -1
            ));
            Cache::delete($lang_code['Language']['iso2'] . '_translations');
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'Translation.id',
                'Translation.language_id',
                'Translation.key',
                'Translation.lang_text',
                'Translation.is_verified'
            ) ,
            'recursive' => -1,
        );
        $this->set('translations', $this->paginate());
        $this->set('verified_count', $this->Translation->find('count', array(
            'conditions' => array(
                'Translation.language_id' => $this->data['Translation']['language_id'],
                'Translation.is_verified' => 1
            )
        )));
        $this->set('unverified_count', $this->Translation->find('count', array(
            'conditions' => array(
                'Translation.language_id' => $this->data['Translation']['language_id'],
                'Translation.is_verified' => 0
            )
        )));
        $translations = $this->Translation->find('all', array(
            'fields' => array(
                'DISTINCT(Translation.language_id)',
                'Language.name'
            ) ,
            'conditions' => array(
                'Translation.language_id !=' => 0
            )
        ));
        foreach($translations as $translation) {
            $languages[$translation['Translation']['language_id']] = $translation['Language']['name'];
        }
        $this->set(compact('languages'));
        $this->set('title_for_layout', $pageTitle);
    }
}
