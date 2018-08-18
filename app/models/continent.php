<?php
class Continent extends AppModel
{
    var $name = 'Continent';
    var $displayField = 'name';
    var $hasAndBelongsToMany = array(
        'Country' => array(
            'className' => 'Country',
            'joinTable' => 'continents_countries',
            'foreignKey' => 'continent_id',
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
                'GroupedCountry.related_class' => 'Continent',
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