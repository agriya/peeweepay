<?php
App::import('Model', 'Attachment');
class UserAvatar extends Attachment
{
    var $name = 'UserAvatar';
    var $useTable = 'attachments';
    var $actsAs = array(
        'Inheritable' => array(
            'inheritanceField' => 'class',
            'fieldAlias' => 'UserAvatar'
        )
    );
}
?>