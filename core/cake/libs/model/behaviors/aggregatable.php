<?php 
/** 
* Aggregatable Behavior (updates real aggregators of foreign Model) 
* 
*  
*/ 
class AggregatableBehavior extends ModelBehavior { 

/** 
* setup 
* 
* @return boolean true on success 
*/ 
function setup(&$model, $config = array())  
{ 
  $this->settings[$model->alias] = (array)$config; 
} 
/** 
* Update selected models 
* 
* @param AppModel &$model 
* @param AppModel $toUpdate models to be updated 
* @return boolean success 
* @access private 
*/ 
function _updateRealAggregators(&$model, $toUpdate) 
{ 
  foreach ($toUpdate as $groupingModelName) 
  { 
    $groupingModel = $model->$groupingModelName;  
    foreach($groupingModel->aggregatingFields as $fieldToUpdate => $params) 
    { 
      if ($params['model'] == $model->alias) 
      {  
        //TODO: to enable recursive calls  
        // ...  
        $grouped_model_id = $model->field("{$params['foreignKey']}"); 
        $gconds = array("{$groupingModelName}.{$groupingModel->primaryKey}" => $grouped_model_id); 
        $data = $groupingModel->findAll($gconds); 
        if (!empty($data)) 
        { 
          foreach($data as $recordToUpdate) 
          { 
            $groupingModel->create(); 
            $groupingModel->id = $grouped_model_id; 
            $mconds = array("{$model->alias}.{$params['foreignKey']}" => $grouped_model_id); 
            if (!empty($params['conditions'])) 
            { 
              $mconds = array('AND' => $mconds, $params['conditions']); 
            }
// rajesh_04ag02 // 2009-03-04 // As reported by Boopathi when working in PSD2XHTML; the condition wasn't seem to be used at all
// Was      $agreg = $model->find($params['conditions'], $params['function']);
            $agreg = $model->find($mconds, $params['function']);
// <--
            $db =& ConnectionManager::getDataSource($model->useDbConfig); 
            
            $new_value = $this->_extractNewValue($agreg, $model, $db->name($params['function']));
            $groupingModel->saveField($fieldToUpdate, $new_value); 
          } 
        } 
      } 
    }  
  } 
} 
/**
 * Extracts aggregated field from a set ($data)
 * @param array $data
 * @param $model $data was extracted from it
 * @param $function field name, or an sql function
 * @return mixed an extracted value
 */
function _extractNewValue($data, $model, $function){             
  $model_alias = $model->alias;
  if (empty($data[$model_alias]))
  {
    $model_alias = 0;
  }
  if (empty($data[$model_alias][$function]))
  {
    $function_parts = explode('.', $function);
    if (trim($function_parts[0], '`') === $model_alias)
    {
    // if $function is a table field name
      $function = trim($function_parts[1], '`');
    }  
    else
    {
      //if $function is an sql function  (for CakePHP 1.2 RC 2, because it wraps around an SQL function with '`')
      $function = trim($function, '`');
    }    
  }
  $new_value = $data[$model_alias][$function];
  return $new_value;
}  
/** 
* Choose models to be updated 
* 
* @param AppModel &$model 
* @return boolean success 
* @access public 
*/ 
function updateRealAggregators(&$model) 
{ 
  if (!function_exists('getAssociatedModel')) 
  { 
    function getAssociatedModel($record) 
    { 
      if (!empty($record['model'])) 
      { 
        return $record['model']; 
      } 
      else 
      { 
        return null; 
      } 
    } 
  } 
  if (!empty($model->belongsTo)) 
  { 
    $toUpdate = array(); 
    foreach($model->belongsTo as $foreignModel => $model_data) 
    { 
      if (!empty($model->$foreignModel->aggregatingFields)) 
      { 
        $associated = array_map('getAssociatedModel', $model->$foreignModel->aggregatingFields);  
        $associated = array_unique(array_values($associated)); 
        if (in_array($model->alias, $associated)) 
        { 
          $toUpdate[] = $foreignModel; 
        } 
      } 
    } 
    return $this->_updateRealAggregators($model, $toUpdate); 
  } 
  else 
  { 
    return true; 
  }  
} 
/** 
* After save method. Called after all saves 
* 
* @param AppModel $model 
* @param boolean $created indicates whether the node just saved was created or updated 
* @return boolean true on success, false on failure 
* @access public 
*/ 
function afterSave(&$model, $created)  
{ 
  return $this->updateRealAggregators($model); 
} 
/** 
* Before delete method. Called before all deletes 
* 
* @param AppModel $model 
* @return boolean true on success, false on failure 
* @access public 
*/ 
function afterDelete(&$model)  
{ 
  return $this->updateRealAggregators($model); 
} 
} 

?>
