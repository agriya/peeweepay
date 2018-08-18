<h2><?php echo __l('Edit settings'); ?></h2>
<div class="js-tabs">
	<ul class="clearfix">
<?php
	foreach ($setting_categories as $setting_category):
	if($setting_category['SettingCategory']['id'] == 5 || $setting_category['SettingCategory']['id'] == 6){
		echo '<!--';
	}
?>	
		<li><?php echo $html->link($html->cText($setting_category['SettingCategory']['name'], false), array('controller' => 'settings', 'action' => 'edit', $setting_category['SettingCategory']['id']), array('title' => $setting_category['SettingCategory']['name'], 'escape' => false)); ?></li>
<?php
   if($setting_category['SettingCategory']['id'] == 5 || $setting_category['SettingCategory']['id'] == 6){
		echo '-->';
	}
	endforeach;
?>
	</ul>
</div>