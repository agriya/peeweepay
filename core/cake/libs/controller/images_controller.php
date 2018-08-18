<?php
// For SERVING UP Images or other files
class ImagesController extends AppController
{
    function __setupDir($destination)
    {
        new Folder(dirname($destination) , true, 0755); // make sure folders exist
        if (!file_exists(dirname($destination))) {
            die('couldn\'t create webdir folder');
        }
        return true;
    }
    function view()
    {
        $args = func_get_args();
        $hash_id = array_pop($args);
        if (count((explode('.', $hash_id))) < 3) {
            $this->cakeError('error404');
        }
        list($id, $hash, $ext) = explode('.', $hash_id);
        $model = implode('/', $args);
        if ($hash != md5(Configure::read('Security.salt') . $model . $id . $ext . $this->params['named']['size'] . Configure::read('site.name'))) {
            $this->cakeError('error404');
        }
        $this->autoRender = false;
        $this->Image->recursive = - 1;
        $data = $this->Image->findById($id);
        if (!$data) {
            $this->cakeError('error404');
        }
        $this->Image->id = $data['Image']['id'];
        $this->Image->data = $data;
        $size = $this->params['named']['size'];
        $original = $this->Image->absolutePath();
        if (!file_exists($original)) {
            $this->cakeError('error404');
        }
        //Added for Windows.. slash problem
        if (substr(PHP_OS, 0, 3) == 'WIN') {
            $destination = WWW_ROOT . str_replace('/', '\\', $this->params['url']['url']);
        } else {
            //Was $this->here, resolving to /atei/img/... (added atei)
            $destination = WWW_ROOT . $this->params['url']['url'];
        }
        // check for valid dimensions
        // Checking Config settings value
        if ($size == 'original') {
            $this->__setupDir($destination);
            if ($this->Image->original($original, $destination)) {
                $this->redirect('/' . $this->params['url']['url'], null, true);
            }
            // Was $this->here
            $this->redirect('/' . $this->params['url']['url'], null, true);
        } else if (!(array_key_exists($size, Configure::read('thumb_size')))) {
            $this->cakeError('error404');
        }
        extract(Configure::read('thumb_size.' . $size));
        if (strpos($data['Image']['mimetype'], 'image/') !== 0) {
            $this->cakeError('error404');
        }
        $saveToWwwRoot = true; // switch
        if ($saveToWwwRoot) {
            $this->__setupDir($destination);
            $aspect = (Configure::read($model . '.' . $size . '.is_handle_aspect') !== null) ? Configure::read($model . '.' . $size . '.is_handle_aspect') : Configure::read($model . '.is_handle_aspect');
            $is_beyond_original = (Configure::read($model . '.' . $size . '.is_not_allow_resize_beyond_original_size') !== null) ? Configure::read($model . '.' . $size . '.is_not_allow_resize_beyond_original_size') : Configure::read($model . '.is_not_allow_resize_beyond_original_size');
            if ($this->Image->resize(null, $width, $height, $destination, $aspect, $original, $is_beyond_original)) {
                $this->redirect('/' . $this->params['url']['url'], null, true);
            }
            $this->cakeError('error404');
        }
    }
}
?>