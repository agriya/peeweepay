<?php
class Union extends AppModel
{
    var $name = 'Union';
    var $displayField = 'name';
    var $hasAndBelongsToMany = array(
        'Country' => array(
            'className' => 'Country',
            'joinTable' => 'countries_unions',
            'foreignKey' => 'union_id',
            'associationForeignKey' => 'country_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    var $hasOne = array(
        'GroupedCountry' => array(
            'className' => 'GroupedCountry',
            'foreignKey' => 'related_condition',
            'dependent' => true,
            'conditions' => array(
                'GroupedCountry.related_class' => 'Union',
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
}
?>