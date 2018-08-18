<?php
/* SVN FILE: $Id: i18n.php 233 2009-05-28 15:59:08Z rajesh_04ag02 $ */
/**
 * Requires:
 * CakePHP 1.2.0.7125 RC1
 *
 * I18n behavior for database content internationalization using locale dependent table field names.  
 *
 * I18n behavior integration steps:
 * 1. Identify which languages you are going to use 
 *	(e.g. English and Russian)
 * 2. Identify your default language 
 *	(e.g. English);
 * 3. Identify fields of your models to be internationalized (
 *	(e.g. model Country field 'name' should be i18n compatible);
 * 4. Update your database tables for each model field to be i18n compatible 
 *	e.g. rename 'name' field to <name>.'_'.DEFAULT_LANGUAGE - default, and create field 'name_rus' that will be russian content); 
 * 5. Add to your model this behavior;
 *	(e.g. $artAs = array('i18n' => array('fields' => array('name'), 'display' => 'name');) 
 * 6. Add to all models that are associated with i18n compatible models this behavior;
 *	(e.g. $artAs = array('i18n'); //yuo can simply add this to each model )
 *	Its necessary because beforeFind and afterFind invoked for the behavior of the model that calls find method. 
 *	During beforeFind and afterFind the behavior will look for any i18n behaviors, see _localizeScheme and _unlocalizeResults.
 * 7. In your model you can set $displayField as usual. The i18n behavior will unlocalize result field names in afterFind. Default $displayField is  name.
 * 8. In your model you can set $order as usual. The i18n behavior will localize your order field name in beforeFind.
 * 9. In your relations you can set order attribute for one field and it will be localized.
 * TODO: localize fields in beforeSave, localize all relation attributes 
 *
 * PHP versions 4 and 5
 *
 * Copyright 2008, Palivoda IT Solutions, Inc.
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2008, Palivoda IT Solutions, Inc.
 * @link			http://www.palivoda.eu
 * @package		app
 * @subpackage		app.models.behaviors
 * @since			CakePHP(tm) v 1.2
 * @version			$Revision:  $
 * @modifiedby		$LastChangedBy:  $
 * @lastmodified		$Date: $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
 */
class I18nBehavior extends ModelBehavior {
 
	//for each model stores lozalizable field names and their aliases to current locale
	var $fields = array();
 
	/** 
	 * Reads configuration of behavior.
	 * Allowed values:
	 * fields - array of i18n compatible field names;
	 */
	function setup(&$model, $config = array()) {
		if (!defined('DEFAULT_LANGUAGE')) {
			trigger_error("Add to bootstrap.php line: define('DEFAULT_LANGUAGE', 'eng');");
		}
		if (!empty($config['fields'])) {
			$this->fields[$model->alias] = array_fill_keys($config['fields'], null);
		}
	}
 
	function beforeFind(&$model, &$query) {
 
		$locale = $this->_getLocale($model);
		//debug('i18n-'.$model->alias.'-beforeFind-'.$locale);
		//debug($query);
 
		$recursive = empty($query['recursive']) ? 0 : $query['recursive']; //during 'delete' there are queries with empty recursive
		$this->_localizeScheme($model, $locale, $recursive);
		$this->_localizeQuery($model, $query);
 
		return $query;
	}
 
	/**
	* Modifies query fielelds to load localized content for current locale.
	*/
	function _localizeQuery(&$model, &$query) {
		if (isset($model->Behaviors->i18n) && isset($model->Behaviors->i18n->fields[$model->alias])) {
			foreach($this->fields as $localModelName => $localModel) {
				if ($localModelName == $model->alias) {
					foreach ($localModel as $localField => $localAlias) { //$localAlias set by _localizeScheme
 
						//localize select field names
						if (is_array($query['fields'])) {
							foreach($query['fields'] as $queryAlias => &$queryField) {
								//full name
								if ($queryField == $localModelName.'.'.$localField) {
									$queryField = $localModelName.'.'.$localAlias;
								}
								//short name
								if ($queryField == $localField) {
									$queryField = $localAlias;
								}
							}
							unset($queryAlias); unset($queryField);
						}
 
						//append  default display name to query if not exists
						if (is_array($query['fields']) &&
							$model->displayField == $localField &&
							!in_array($localModelName.'.'.$localAlias,  $query['fields']) &&
							!in_array($localAlias,  $query['fields']) ) {
								//keep only one Id column in query
								$query['fields'] = array_values(array_unique($query['fields']));
								$query['fields'][] = $localModelName.'.'.$localAlias;
								//set displayFieled fof list type of query
								$query['list']['valuePath'] = '{n}.'.$model->alias.'.'.$localField; 
 
						}
 
						//localize order field names
						if (is_array($query['order'])) {
							foreach($query['order'] as $queryAlias => &$queryField) {
								//multiple order filed as array
								if (is_array($queryField)) {
									foreach($queryField as &$subField) {
										if (strstr($subField, $localField) != false)
											$subField = str_replace($localField, $localAlias, $subField);
									}
								}
								//multiple order fileds in one string, comma separated
								else {
									if (strstr($queryField, $localField) != false) {
										$queryField = str_replace($localField, $localAlias, $queryField);
									}
								}
							}
							unset($queryAlias); unset($queryField);
						}
 
					}
					break; //break if model found
				}
			}
		}
	}
 
	/**
	* Modifies theme to load localized content only for default and current locale.
	*/
	function _localizeScheme(&$model, $locale, $recursive) {
 
		$model->locale = $locale;
 
		if (isset($model->Behaviors->i18n) && isset($model->Behaviors->i18n->fields[$model->alias])) {
			foreach($model->Behaviors->i18n->fields[$model->alias] as $configName => &$configAlias) {
 
				//ammend schema and store in config localized field name <name>_<locale> or <name>_def
				$foundSpecific = false;
				foreach($model->_schema as $shemaName => $v) {
					if (strpos('_'.$shemaName, $configName) == 1) { //is one of i18n fields
						if ($configName.'_'.DEFAULT_LANGUAGE != $shemaName) { //not for default locale
							if ($configName.'_'.$locale != $shemaName) { //not for current locale
								unset($model->_schema[$shemaName]);
							}
							else {
								$foundSpecific = true;
								$configAlias = $configName.'_'.$locale;
							}
						}
					}
				}
				unset($shemaName); unset($v);
				if ($foundSpecific) { //found locale specific content, no need in default content
					unset($model->_schema[$configName.'_'.DEFAULT_LANGUAGE]);
				}
				else {
					// siva_43ag07 // fixed to take 'name' field from database when DEFAULT_LANGUAGE is NULL
                    if (DEFAULT_LANGUAGE != '' and array_key_exists($configName.'_'.DEFAULT_LANGUAGE, $model->_schema)) {						
                        $configAlias = $configName . '_' . DEFAULT_LANGUAGE;
                    } else {
                        $configAlias = $configName;
                    }
				}
 
				//set defailt display field to i18n name or title
				if (empty($model->displayField) || $model->displayField == 'id') {
					if (isset($this->fields[$model->alias]['name'])) {
						$model->displayField = 'name';
					}
					if (isset($this->fields[$model->alias]['title'])) {
						$model->displayField = 'title';
					}
				}
 
			}
		}
 
		//if no recursive set then update schema of related mdels
		if (empty($recursive)) $recursive = 0;
 
		if ($recursive < 0) return;
 
		//go throught related models and if thay has i18n behaviour then localize theme
		//Note: models A-B-C, if B is not 18n then C will not be localized, even if it has 18n behaviour
 
		if (isset($model->belongsTo)) {
			foreach ($model->belongsTo as $name => &$relation) {
				if (isset($model->Behaviors->i18n)) {
					$model->Behaviors->i18n->_localizeScheme($model->$name, $locale, $recursive-1);
					$model->Behaviors->i18n->_localizeRelation($name, $relation);
				}
			}
		}
 
		if (isset($model->hasOne)) {
			foreach ($model->hasOne as $name => &$relation) {
				if (isset($model->Behaviors->i18n)) {
					$model->Behaviors->i18n->_localizeScheme($model->$name, $locale, $recursive-1);
					$model->Behaviors->i18n->_localizeRelation($name, $relation);
				}
			}
		}
 
		if (isset($model->hasMany)) {
			foreach ($model->hasMany as $name => &$relation) {
				if (isset($model->Behaviors->i18n)) {
					$model->Behaviors->i18n->_localizeScheme($model->$name, $locale, $recursive-1);
					$model->Behaviors->i18n->_localizeRelation($name, $relation);
				}
			}
		}
 
		if (isset($model->hasAndBelongsToMany)) {
			foreach ($model->hasAndBelongsToMany as $name => &$relation) {
				if (isset($model->Behaviors->i18n)) {
					$model->Behaviors->i18n->_localizeScheme($model->$name, $locale, $recursive-1);
					$model->Behaviors->i18n->_localizeRelation($name, $relation);
				}
			}
		}
 
	}
 
	/*
	 * Localize order field name in belongsTo, hasOne, hasMany, hasAndBelongsToMany.
	 * TODO: localize other relation attributes: 'conditions', 'fields', 'order', 'finderQuery', 'deleteQuery', 'insertQuery'.
 	 */
	function _localizeRelation($name, &$relation) {
		if (isset($this->fields[$name][$relation['order']])) {
			$configOrder = $this->fields[$name][$relation['order']];
			if (isset($configOrder)) {
				$relation['order'] = $configOrder;
			}
		}
	}
 
	function afterFind(&$model, &$results, &$primary) {
		//debug('i18n-'.$model->alias.'-afterFind');
		if (is_array($results)) {
			foreach ($results as &$result) {
				$this->_unlocalizeResults($model, $result, $this->_getLocale($model));
			}
		}
		return $results;
	}
 
	/**
	* Narrows fields of loaded data to locale independant names, e.g. fields <name>_def and <name>_eng will became just <name>.
	* It recurse as far as resulsts are exists. If you made find with recursive 2 then it will recurse till second level of results.
	* TODO: The reverse process should be made before model saved.
	*/
	function _unlocalizeResults(&$model, &$result, &$locale) {
 
		if (isset($model->Behaviors->i18n) && isset($model->Behaviors->i18n->fields[$model->alias])) {
 
			//collection of models
			if (!empty($result[$model->alias])) {
				$data = &$result[$model->alias];
			}
			//single model
			else {
				$data = &$result;
			}
 
			foreach($model->Behaviors->i18n->fields[$model->alias] as $name => $alias) { //alias set in _localizeScheme
				//unlocalize field name
				if (array_key_exists($alias, $data)) {
					$data[$name] = $data[$alias];
					// siva_43ag07 // fixed: no need to unset if name and alias are same
                    if ($name != $alias) {
                        unset($data[$alias]);
                    }
				}
			}
 
			unset($data);
		}
 
		if (isset($model->belongsTo)) {
			foreach ($model->belongsTo as $name => $relation) {
				$behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && isset($model->Behaviors->i18n)) {
					$model->Behaviors->i18n->_unlocalizeResults($model->$name, $result[$name], $locale);
				}
			}
		}
 
		if (isset($model->hasOne)) {
			foreach ($model->hasOne as $name => $relation) {
				$behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && isset($model->Behaviors->i18n)) {
					$model->Behaviors->i18n->_unlocalizeResults($model->$name, $result[$name], $locale);
				}
			}
		}
 
		if (isset($model->hasMany)) {
			foreach ($model->hasMany as $name => $relation) {
				$behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && isset($model->Behaviors->i18n)) {
					foreach ($result[$name] as &$record) {
						$model->Behaviors->i18n->_unlocalizeResults($model->$name, $record, $locale);
					}
				}
			}
		}
 
		if (isset($model->hasAndBelongsToMany)) {
			foreach ($model->hasAndBelongsToMany as $name => $relation) {
				$behaviors = $model->$name->Behaviors;
				if (isset($result[$name]) && isset($model->Behaviors->i18n)) {
					foreach ($result[$name] as &$record) {
						$model->Behaviors->i18n->_unlocalizeResults($model->$name, $record, $locale);
					}
				}
			}
		}
 
	}
 
	function _getLocale(&$model) {
		if (!isset($model->locale) || is_null($model->locale)) {
			if (!class_exists('I18n')) {
				uses('i18n');
			}
			$I18n =& I18n::getInstance();
			$model->locale = $I18n->l10n->locale;
		}
		return $model->locale;
	}	
 
}
 
?>
