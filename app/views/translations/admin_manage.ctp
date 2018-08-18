<?php /* SVN: $Id: admin_manage.ctp 17696 2010-08-05 12:37:07Z boopathi_026ac09 $ */ ?>
<div class="js-response">
<?php if(!empty($translations)): ?>
<h2><?php echo sprintf(__l('Edit Translations').' - %s', $languages[$this->data['Translation']['language_id']]); ?></h2>
<?php else: ?>
<h2><?php echo __l('Edit Translations'); ?></h2>
<?php endif; ?>
<div class="translations form">
<h3><?php echo __l('Translation Stats');?></h3>
<dl class="list clearfix">
	<dt><?php echo __l('Verified');?></dt>
		<dd><?php echo $html->link($verified_count, array('controller' => 'translations', 'action' => 'manage', 'language_id' => $this->data['Translation']['language_id'], 'filter' => 'verified'), array('title' => __l('Verified')));?></dd>
	<dt><?php echo __l('Unverified');?></dt>
		<dd><?php echo $html->link($unverified_count, array('controller' => 'translations', 'action' => 'manage', 'language_id' => $this->data['Translation']['language_id'], 'filter' => 'unverified'), array('title' => __l('Unverified')));?></dd>
</dl>
<div class = "notice">
	<?php echo __l('If you translated with Google Translate, it may not be perfect translation and it may have mistakes. So you need to manually check all translated texts. The translation stats will give summary of verified/unverified translated text.');?>
</div>
<?php echo $form->create('Translation', array('action' => 'manage', 'class' => 'normal')); ?>
	<fieldset>
	<?php
		echo $form->input('language_id');
		echo $form->input('filter', array('type' => 'hidden'));
		echo $form->input('q', array('label' => __l('Keyword'))); ?>
		<div class="submit-block clearfix">
		<?php
		echo $form->submit(__l('Submit'), array('name' => 'data[Translation][makeSubmit]'));
		?>
		</div>
		<?php
		if(!empty($translations)):
			echo $this->element('paging_counter');
		endif;		
?>

<table class="list">
<thead>
<th><?php echo __l('Verified'); ?></th>
<th><?php echo __l('Key'); ?></th>
<th><?php echo __l('Translate Text'); ?></th>
</thead>
<?php		
		if(!empty($translations)):
			foreach ($translations as $translation):
			?>
				<tr><td> <?php echo $form->input('Translation.'.$translation['Translation']['id'].'.is_verified', array('checked' => ($translation['Translation']['is_verified'])?true:false, 'class' => '', 'label' => false)); ?></td>
                <td> <?php echo $translation['Translation']['key']; ?></td>
                 <td> <?php echo $form->input('Translation.'.$translation['Translation']['id'].'.lang_text', array('label' => false, 'value' => $translation['Translation']['lang_text'])); ?></td>
                </tr>
		<?php	
            endforeach;
			?>
	<tr><td colspan="3">
	            <?php 
				echo $form->submit(__l('Update'), array('name' => 'data[Translation][makeUpdate]'));
			?>    
</td>
	</tr>
            

            <?php
		else:
	?>
	<tr><td colspan="2">
	<?php echo __l('No translations available');?></td>
	</tr>
	<?php endif;?>
    </table>
	<div class="js-pagination">
	<?php  	if(!empty($translations)):
    			echo $this->element('paging_links');
			endif;
	?>
    </div>
	</fieldset>
	<?php echo $form->end(); ?>
</div>
</div>