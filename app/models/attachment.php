<?php
class Attachment extends AppModel
{
    var $name = 'Attachment';
    var $actsAs = array(
        'ImageUpload' => array(
            'allowedMime' => '*',
            'allowedExt' => '*'
        )
    );
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
    }
}
?>