<?php
class Subscription extends AppModel
{
    var $name = 'Subscription';
    var $displayField = 'name';
    //$validate set in __construct for multi-language support
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'email' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
    }
}
?>