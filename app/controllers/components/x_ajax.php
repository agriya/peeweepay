<?php
/**
 * XAjax - Extended Ajax
 *
 * @author      rajesh_04ag02 // 2008-12-01
 * Note: Original version from http://cakeforge.org/snippet/download.php?type=snippet&id=286 (AutocompleteComponent)
 *      But, heavily modified it to work with Router::parseExetensions() and make it automatic as much as possible
 */
class XAjaxComponent extends Object
{
    var $enabled = true;
    var $autocompleteLimit = 250;
    /**
     * Startup
     *
     * @param object A reference to the controller
     * @return null
     */
    function startup(&$controller)
    {
        $this->Controller = & $controller;
    }
    function autocomplete($param_encode = null, $param_hash = null, $conditions = false)
    {
        $controller = & $this->Controller;
        if (is_null($param_encode) || is_null($param_hash)) {
            $controller->cakeError('error404');
        }
        $exp_param_hash = substr(md5(Configure::read('Security.salt') . $param_encode) , 5, 7);
        if (strcmp($exp_param_hash, $param_hash) !== 0) {
            $controller->cakeError('error404');
        }
        $params = unserialize(gzinflate(base64_url_decode($param_encode)));
        $this->autocomplete2(@$params['acFieldKey'], @$params['acFields'], @$params['acSearchFieldNames'], $conditions);
    }
    //@todo the search fields array to be handled for proper condition formation
    function autocomplete2($fieldKey = null, $fieldNames = null, $autocompleteSearchFieldNames = null, $conditions = false)
    {
        $controller = & $this->Controller;
        $modelClass = Inflector::singularize($controller->name);
        if (!$this->enabled || !$controller->RequestHandler->isAjax() || !$controller->RequestHandler->prefers('json')) {
            //            $controller->cakeError('error404');

        }
        $controller->view = 'Json';
        $findOptions = array(
            'recursive' => - 1
        );
        if (is_null($fieldKey)) {
            $fieldKey = 'id';
        }
        if (is_null($fieldNames)) {
            $findOptions['fields'] = array(
                $fieldKey,
                $controller->{$modelClass}->displayField
            );
        } else {
            $findOptions['fields'] = $fieldNames;
        }
        if ($conditions) {
            $findOptions['conditions'] = $conditions;
        }
        $findOptions['limit'] = $this->autocompleteLimit;
        if (isset($controller->params['url']['q'])) {
            if (is_null($autocompleteSearchFieldNames)) {
                $autocompleteSearchFieldNames = $controller->{$modelClass}->displayField;
            } else { // array
                //@todo handle array
                $autocompleteSearchFieldNames = $autocompleteSearchFieldNames[0];
            }
            $findOptions['conditions'][$autocompleteSearchFieldNames . ' LIKE '] = '%' . $controller->params['url']['q'] . '%';
        }
        $data = $controller->{$modelClass}->find('list', $findOptions);
        $controller->set('json', $data);
    }
    function flashuploadset($data)
    {
        Configure::write('debug', 0);
        $controller = & $this->Controller;
        $_SESSION['flashupload_data'][$controller->name] = $data;
        echo 'flashupload';
        exit;
    }
	// show thumbnail 
	function thumbnail($file_id)
    {        		
        //Work around the Flash Player Cookie Bug
        if (empty($_SESSION["product_file_info"][$file_id])) {
            header("HTTP/1.1 404 Not found");
            exit(0);
        }
        header("Content-type: image/jpeg");
        header("Content-Length: " . strlen($_SESSION["product_file_info"][$file_id]['thumb']));
        echo $_SESSION["product_file_info"][$file_id]['thumb'];
        exit(0);
    }
	function _setMemoryLimitForImage($image_path)
    {
        $imageInfo = getimagesize($image_path);
        $memoryNeeded = round(($imageInfo[0] * $imageInfo[1] * $imageInfo['bits'] * $imageInfo['channels'] / 8 + Pow(2, 16)) * 1.65);
        if (function_exists('memory_get_usage') && memory_get_usage() + $memoryNeeded > (integer)ini_get('memory_limit') * pow(1024, 2)) {
            ini_set('memory_limit', (integer)ini_get('memory_limit') + ceil(((memory_get_usage() + $memoryNeeded) - (integer)ini_get('memory_limit') * pow(1024, 2)) / pow(1024, 2)) . 'M');
        }
    }
	// preview image
	function previewImage()
    {        
		// Check the upload
        if (!isset($_FILES["Filedata"]) || !is_uploaded_file($_FILES["Filedata"]["tmp_name"]) || $_FILES["Filedata"]["error"] != 0) {            
			$this->log('Upload Image: ERROR:invalid upload');
            exit(0);
        }
		if (!($size = getimagesize($_FILES['Filedata']['tmp_name']))) {            
			$this->log('Upload Image: ERROR:image doesn\'t exist');
            return false;
        }
        list($currentWidth, $currentHeight, $currentType) = $size;
		$types = array(
			1 => 'gif',
			'jpeg',
			'png',
			'swf',
			'psd',
			'wbmp'
		);
		$this->_setMemoryLimitForImage( $_FILES['Filedata']['tmp_name']);
        // Get the image and create a thumbnail        
		$img = call_user_func('imagecreatefrom' . $types[$currentType], $_FILES['Filedata']['tmp_name']);        
		ini_restore('memory_limit');

        if (!$img) {            			
			$this->log('Upload Image: ERROR:could not create image handle' . $_FILES["Filedata"]["tmp_name"]);
            exit(0);
        }
        $width = imageSX($img);
        $height = imageSY($img);
        if (!$width || !$height) {            
			$this->log('Upload Image: ERROR:Invalid width or height');
            exit(0);
        }
        // Build the thumbnail
        $target_width = 120;
        $target_height = 90;
        $target_ratio = $target_width/$target_height;
        $img_ratio = $width/$height;
        if ($target_ratio > $img_ratio) {
            $new_height = $target_height;
            $new_width = $img_ratio*$target_height;
        } else {
            $new_height = $target_width/$img_ratio;
            $new_width = $target_width;
        }
        if ($new_height > $target_height) {
            $new_height = $target_height;
        }
        if ($new_width > $target_width) {
            $new_height = $target_width;
        }
        $new_img = imagecreatetruecolor($target_width, $target_height);
        if (!@imagefilledrectangle($new_img, 0, 0, $target_width-1, $target_height-1, 0)) { // Fill the image black            
			$this->log('Upload Image: ERROR:Could not fill new image');
            exit(0);
        }
        if (!@imagecopyresampled($new_img, $img, ($target_width-$new_width) /2, ($target_height-$new_height) /2, 0, 0, $new_width, $new_height, $width, $height)) {            
			$this->log('Upload Image: ERROR:Could not resize image');
            exit(0);
        }
        if (!isset($_SESSION["product_file_info"])) {
            $_SESSION["product_file_info"] = array();
        }
        // Use a output buffering to load the image into a variable
        ob_start();
        imagejpeg($new_img);
        $imagevariable = ob_get_contents();
        ob_end_clean();
        $file_id = md5($_FILES["Filedata"]["tmp_name"]+rand() *100000);
        $_SESSION["product_file_info"][$file_id]['type'] = get_mime($_FILES["Filedata"]["tmp_name"]);
        $_SESSION["product_file_info"][$file_id]['filename'] = $_FILES["Filedata"]["name"];
        $_SESSION["product_file_info"][$file_id]['thumb'] = $imagevariable;
        $handle = fopen($_FILES["Filedata"]["tmp_name"], "r");
        $contents = fread($handle, filesize($_FILES["Filedata"]["tmp_name"]));
        fclose($handle);
        //Encode for web service
        $base64string = base64_encode($contents);
        $_SESSION["product_file_info"][$file_id]['original'] = $base64string;		
        echo 'FILEID:' . $file_id;
        exit;
    }
    function flashupload($multiple = false)
    {
        $controller = & $this->Controller;
        $modelClass = Inflector::singularize($controller->name);
        if (isset($_FILES['Filedata']['name']) and !empty($_SESSION['flashupload_data'][$controller->name])) {
            $_FILES['Filedata']['type'] = get_mime($_FILES['Filedata']['tmp_name']);
            $this->data = $_SESSION['flashupload_data'][$controller->name];
            if ($multiple) {
                // update the title field with the file name
                $t_filename = $_FILES['Filedata']['name'];
                $this->data[$modelClass]['title'] = Inflector::humanize(str_replace(array(
                    '_',
                    '-'
                ) , ' ', basename($t_filename, substr($t_filename, strrpos($t_filename, '.')))));
                $controller->{$modelClass}->create();
                if ($controller->{$modelClass}->save($this->data, false)) {
                    $attachments = array();
                    $attachments['Attachment']['filename'] = $_FILES['Filedata'];
                    $attachments['Attachment']['class'] = $modelClass;
                    $attachments['Attachment']['foreign_id'] = $controller->{$modelClass}->id;
                    $controller->{$modelClass}->Attachment->create();
                    $controller->{$modelClass}->Attachment->save($attachments);
                    // save in session to retrieve the last inserted id in controller
                    $_SESSION['flash_uploaded']['data'][] = $controller->{$modelClass}->id;
                }
            } else {
                $attachments = array();
                $attachments['Attachment']['filename'] = $_FILES['Filedata'];
                $attachments['Attachment']['class'] = $modelClass;
                $attachments['Attachment']['foreign_id'] = $this->data['Attachment']['foreign_id'];
                $controller->{$modelClass}->Attachment->create();
                $controller->{$modelClass}->Attachment->save($attachments);
            }
            echo ' '; // Prevent bug in Mac OS 8 flash player
            session_write_close(); // Write session variables!
            exit();
        }
    }
    function normalupload($data, $multiple = false)
    {
        $controller = & $this->Controller;
        $modelClass = Inflector::singularize($controller->name);
        if ($multiple) {
            foreach($data['Attachment'] as $attachment) {
                $controller->{$modelClass}->Attachment->Behaviors->attach('ImageUpload');
                if (!empty($attachment['filename']['name'])) {
                    // update the title field with the file name
                    $t_filename = $attachment['filename']['name'];
                    $data[$modelClass]['title'] = Inflector::humanize(str_replace(array(
                        '_',
                        '-'
                    ) , ' ', basename($t_filename, substr($t_filename, strrpos($t_filename, '.')))));
                }
                $controller->{$modelClass}->create();
                if (!empty($attachment['filename']['name']) && $controller->{$modelClass}->save($data, false)) {
                    $attachments = array();
                    $attachments['Attachment']['filename'] = $attachment['filename'];
                    $attachments['Attachment']['class'] = $modelClass;
                    $attachments['Attachment']['foreign_id'] = $controller->{$modelClass}->id;
                    $controller->{$modelClass}->Attachment->create();
                    $controller->{$modelClass}->Attachment->save($attachments);
                    // save in session to retrieve the last inserted id in controller
                    $_SESSION['flash_uploaded']['data'][] = $controller->{$modelClass}->id;
                }
                $controller->{$modelClass}->Attachment->Behaviors->detach('ImageUpload');
            }
        } else {
            foreach($data['Attachment'] as $attachment) {
                $attachments = array();
                if (!empty($attachment['filename']['name'])) {
                    $attachments['Attachment']['filename'] = $attachment['filename'];
                    $attachments['Attachment']['class'] = $modelClass;
                    $attachments['Attachment']['foreign_id'] = $data['foreign_id'];
                    $controller->{$modelClass}->Attachment->create();
                    $controller->Attachment->save($attachments);
                }
            }
        }
    }
}
?>
