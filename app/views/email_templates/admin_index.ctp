<h2><?php echo __l('Update Email Templates');?></h2>
<div class="js-tabs">	
<?php
	if (!empty($emailTemplates)):
?>
	<ul class="clearfix">
<?php
		foreach ($emailTemplates as $emailTemplate):
?>		
			<li><?php echo $html->link($html->cText($emailTemplate['EmailTemplate']['name'], false), array('controller' => 'email_templates', 'action' => 'edit', $emailTemplate['EmailTemplate']['id']), array('escape' => false,'title' => $html->cText($emailTemplate['EmailTemplate']['name'], false)));?></li>
<?php
		endforeach;
?>
	</ul>
<?php
	else:
?>
	<ul>
		<li><?php echo __l('No e-mail templates added yet.'); ?></li>
	</ul>
<?php
	endif;
?>	
</div>