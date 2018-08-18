<?php
class GroupedCountry extends AppModel
{
    var $name = 'GroupedCountry';
    var $displayField = 'name';
    var $actsAs = array(
        'Polymorphic' => array(
            'classField' => 'related_class',
            'foreignKey' => 'related_condition',
        )
    );
    var $belongsTo = array(
        'Union' => array(
            'className' => 'Union',
            'foreignKey' => 'related_condition',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Continent' => array(
            'className' => 'Continent',
            'foreignKey' => 'related_condition',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
}
?>