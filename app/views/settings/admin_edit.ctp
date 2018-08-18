<div class="js-responses">
<?php if(!empty($settings_category['SettingCategory']['description'])):?>
	<div class=" info-details clearfix"><?php echo $settings_category['SettingCategory']['description'];?> </div>
<?php endif;?>
<?php
	if(!empty($settings)):
		echo $form->create('Setting', array('action' => 'edit', 'class' => 'normal js-ajax-form'));
			echo $form->input('setting_category_id', array('type' => 'hidden'));
		// hack to delete the thumb folder in img directory
        if($settings[0]['SettingCategory']['name'] == 'images'):
        	echo $form->input('delete_thumb_images', array('type' => 'hidden', 'value' => '1'));
        endif;
		if($settings[0]['SettingCategory']['name'] == 'Twitter'):
			 echo $html->link(__l('Update Twitter Credentials'), array('action' => 'tw_update', 'admin' => true), array('class' => 'twitter-link', 'title' => __l('Here you can update Twitter credentials like Access key and Accss Token. Click this link and Follow the steps. Please make sure that you have updated the Consumer Key and  Consumer secret before you click this link.')));
		endif;
		if($settings[0]['SettingCategory']['name'] == 'Facebook'):
			 echo $html->link(__l('Update Facebook Credentials'), $fb_login_url, array('class' => 'facebook-link', 'title' => __l('Here you can update Facebook credentials . Click this link and Follow the steps. Please make sure that you have updated the API Key and Secret before you click this link.')));
		endif;
		$inputDisplay = 0;
    	foreach ($settings as $setting):
            $field_name = explode('.', $setting['Setting']['name']);
            if(isset($field_name[2]) && ($field_name[2] == 'is_not_allow_resize_beyond_original_size' || $field_name[2] == 'is_handle_aspect')){
                continue;
            }
            $options['type'] = $setting['Setting']['type'];
            $options['value'] = $setting['Setting']['value'];
            $options['div'] = array('id' => "setting-{$setting['Setting']['name']}");
			$options['label'] = $setting['Setting']['label'];
            if($options['type'] == 'checkbox' && $options['value']):
                $options['checked'] = 'checked';
            endif;
            if($options['type'] == 'select'):
                $selectOptions = explode(',', $setting['Setting']['options']);
                $setting['Setting']['options'] = array();
                if(!empty($selectOptions)):
                    foreach($selectOptions as $key => $value):
                        if(!empty($value)):
                            $setting['Setting']['options'][trim($value)] = trim($value);
                        endif;
                    endforeach;
                endif;
                $options['options'] = $setting['Setting']['options'];			
            endif;
			if($options['type'] == 'radio'):
                $selectOptions = explode(',', $setting['Setting']['options']);
                $setting['Setting']['options'] = array();
                $options['legend'] = false;
                if(!empty($selectOptions)):
                    foreach($selectOptions as $key => $value):
                        if(!empty($value)):
                            $setting['Setting']['options'][trim($value)] = trim($value);
                        endif;
                    endforeach;
                endif;
                $options['options'] = $setting['Setting']['options'];
                ?>
                <fieldset class="form-block round-5">
                <legend class="round-5">
	               <?php echo $options['label']; ?>
    			</legend>
			<?php
			elseif($setting['Setting']['setting_category_id'] == '3' && empty($isSetlegend)):
                $isSetlegend = 1;
                ?>
                <fieldset class="form-block round-5">
                <legend class="round-5">
	               <?php echo __l('Others'); ?>
    			</legend>
				<?php
			endif;
            if($setting['Setting']['name'] == 'site.language'):
                $options['options'] = $languageOptions;
            endif;
			if (!empty($setting['Setting']['description'])):
				$options['help'] = "{$setting['Setting']['description']}";
			endif;			
			if ($setting['SettingCategory']['name'] == 'Images' && $inputDisplay == 0):
				$options['class'] = 'image-settings';
				echo '<div class="outer-image-settings clearfix">';
			elseif($setting['SettingCategory']['name'] == 'Images'):
				$options['class'] = 'image-settings image-settings-height';
			endif;
			if($setting['Setting']['name'] != 'site.language' || ($setting['Setting']['name'] == 'site.language' && !empty($languageOptions))) :
			 echo $form->input("Setting.{$setting['Setting']['id']}.name", $options);
            endif;
			if($setting['SettingCategory']['name'] == 'Images' && !$inputDisplay++):
                echo '<div class="input image-separator">X</div>';
			endif;
			if($setting['SettingCategory']['name'] == 'Images' && $inputDisplay == 2):
				echo '</div>';
			endif;
			if($options['type'] == 'radio'): ?>
                </fieldset>
			<?php
			// for gig categroy boxes
            elseif($setting['Setting']['setting_category_id'] == '3' && !isset($isSetlegend)):
                ?>
                </fieldset>
            
            <?php
            endif;
			$inputDisplay = ($inputDisplay == 2) ? 0 : $inputDisplay;
            unset($options);
		endforeach;
		if(!empty($beyondOriginals)){
            echo $form->input('not_allow_beyond_original', array('type' => 'select', 'multiple' => 'multiple', 'options' => $beyondOriginals));
        }
        if(!empty($aspects)){
            echo $form->input('allow_handle_aspect', array('type' => 'select', 'multiple' => 'multiple', 'options' => $aspects));
        }
    	echo $form->end('Update');
	else:
?>
		<div class="notice"><?php echo __l('No settings available'); ?></div>
<?php
	endif;
?>
</div>