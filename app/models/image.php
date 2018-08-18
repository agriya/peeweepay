<?php
include_once ('attachment.php');
class Image extends Attachment
{
    var $name = 'Image';
    var $useTable = 'attachments';
    var $actsAs = array(
        //		'WhoDunnit',
        /*		'Slug' => array (
        'label' =>'description',
        'overwrite' => true,
        'unique' => false
        ),
        */
        'ImageUpload'
    );
}
?>
