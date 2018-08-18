<?php
if (!empty($import_models)) {
    foreach($import_models as $model_name => $settings) {
		$models = $_this->$model_name->find('all', $settings);
		if (!empty($models)) {
			foreach ($models as $model):
				echo $rss->item(array() , array(
					'title' => $model[$model_name][$settings['fields'][0]],
					'link' => array(
						'controller' => Inflector::tableize($model_name),
						'action' => 'view',
						$model[$model_name][$settings['fields'][1]]
					) ,
					'description' => '<p>' . $html->cHtml($html->truncate($model[$model_name]['description'])) . '</p>',
					'createdDate' => $html->cDateTime($model[$model_name]['created'], false) ,
				));
			endforeach;
		}
    }
}
?>