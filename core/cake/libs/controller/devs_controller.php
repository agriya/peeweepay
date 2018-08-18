<?php
class DevsController extends AppController
{
    var $name = 'Devs';
    var $uses = null;
    function robots()
    {
    }
    function diagnose()
    {
        $_writable_folders = array(
            TMP,
            CSS,
            JS,
            IMAGES,
            APP . 'media'
        );
        $out = '';
        foreach($_writable_folders as $folder) {
            if ($this->_is_writable_recursive($folder)) {
                $out.= '<li><span class="success">Writable</span> ' . $folder . '</li>';
            } else {
                $out.= '<li><span class="error">NOT Writable</span> ' . $folder . '</li>';
            }
        }
        $debugLog = nl2br(file_get_contents(TMP . 'logs' . DS . 'debug.log'));
        $errorLog = nl2br(file_get_contents(TMP . 'logs' . DS . 'error.log'));
        $this->set('writable', $out);
        $this->set('debugLog', $debugLog);
        $this->set('errorLog', $errorLog);
        $this->set('tmpCacheFileSize', bytes_to_higher(dskspace(TMP . 'cache')));
        $this->set('tmpLogsFileSize', bytes_to_higher(dskspace(TMP . 'logs')));
    }
    function sitemap()
    {
        $import_models = Configure::read('sitemap.models');
        if (!empty($import_models)) {
            foreach($import_models as $model_name => $settings) {
                //condition to check when only model name given in sitemap models array
                if (!is_array($settings)) {
                    unset($import_models[$model_name]);
                    $model_name = $settings;
                    $settings = array();
                    $import_models[$model_name] = array();
                }
                //Default settings, you can override priority, fields
                $_settings = array(
                    'limit' => 20,
                    'recursive' => - 1,
                    'priority' => 0.8,
                    'fields' => array('slug')
                );
                $settings = array_merge($_settings, $settings);
                //Adding modified field in settings to fetch in find query
                $settings['fields'][] = 'modified';
                $import_models[$model_name] = $settings;
                $this->loadModel($model_name);
            }
        }
        $this->set('_this', $this);
        $this->set('import_models', $import_models);
        $this->render('index');
    }
    //@todo favatar helper
    function favatar($base64_encoded_url)
    {
        $url = base64_decode($base64_encoded_url);
        $url_parts = parse_url($url);
        $host = str_replace(array(
            '/',
            '\\',
            '.',
            '&',
            '>',
            '<',
            ';',
            ',',
            '@',
            '$'
        ) , '-', $url_parts['host']);
        if (file_exists(IMAGES . 'favicons' . DS . $host . '.png')) {
            $this->redirect('/img/favicons/' . $host . '.png');
        }
        require_once (APP . 'vendors' . DS . 'floicon' . DS . 'floIcon.php');
        ini_set('user_agent', 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.11) Gecko/20071127 Firefox/2.0.0.11');
        $icon_file = new floIcon();
        if (@copy('http://' . $url_parts['host'] . '/favicon.ico', IMAGES . 'favicons' . DS . $host . '.ico')) {
            if ($icon_file->readICO(IMAGES . 'favicons' . DS . $host . '.ico')) {
                imagepng($icon_file->getBestImage(16, 16) , IMAGES . 'favicons' . DS . $host . '.png');
            } else {
                @copy(IMAGES . 'favicons' . DS . '_no-favicon.png', IMAGES . 'favicons' . DS . $host . '.png');
            }
            @unlink(IMAGES . 'favicons' . DS . $host . '.ico');
            $this->redirect('/img/favicons/' . $host . '.png');
        }
        /* Not necessarily needed; until client insists it... so commented out
        // code from http://plugins.trac.wordpress.org/browser/favatars/trunk/favatars.php
        // start by fetching the contents of the URL they left...

        if ($html = @file_get_contents($url)) {
        if (preg_match('/<link[^>]+rel="(?:shortcut )?icon"[^>]+?href="([^"]+?)"/si', $html, $matches)) {
        // Attempt to grab a favicon link from their webpage url
        $linkUrl = html_entity_decode($matches[1]);
        if (substr($linkUrl, 0, 1) == '/') {
        $urlParts = parse_url($url);
        $faviconURL = $urlParts['scheme'] . '://' . $urlParts['host'] . $linkUrl;
        } else if (substr($linkUrl, 0, 7) == 'http://') {
        $faviconURL = $linkUrl;
        } else if (substr($url, -1, 1) == '/') {
        $faviconURL = $url . $linkUrl;
        } else {
        $faviconURL = $url . '/' . $linkUrl;
        }
        }
        } */
        @copy(IMAGES . 'favicons' . DS . '_no-favicon.png', IMAGES . 'favicons' . DS . $host . '.png');
        $this->redirect('/img/favicons/' . $host . '.png');
    }
    function _is_writable_recursive($dir)
    {
        if (!($folder = @opendir($dir))) {
            return false;
        }
        while ($file = readdir($folder)) {
            if ($file != '.' && $file != '..' && $file != '.svn' && (!is_writable($dir . DS . $file) || (is_dir($dir . DS . $file) && !$this->_is_writable_recursive($dir . DS . $file)))) {
                closedir($folder);
                return false;
            }
        }
        closedir($folder);
        return true;
    }
}
?>