<div class="js-responses">
<h2><?php echo $html->cText($this->data['EmailTemplate']['name'], false); ?></h2>
<div class="info-details">
		<?php echo $html->cText($this->data['EmailTemplate']['description'], false); ?>
</div>
<?php
	echo $form->create('EmailTemplate', array('id' => 'EmailTemplateAdminEditForm'.$this->data['EmailTemplate']['id'], 'class' => 'normal js-insert js-ajax-form', 'action' => 'edit'));
	echo $form->input('id');
	echo $form->input('from', array('id' => 'EmailTemplateFrom'.$this->data['EmailTemplate']['id'], 'info' => __l('(eg. "displayname &lt;email address>")')));
	echo $form->input('reply_to', array('id' => 'EmailTemplateReplyTo'.$this->data['EmailTemplate']['id'], 'info' => __l('(eg. "displayname &lt;email address>")')));
	echo $form->input('subject', array('class' => 'js-email-subject', 'id' => 'EmailTemplateSubject'.$this->data['EmailTemplate']['id']));
	echo $form->input('email_content', array('class' => 'js-email-content', 'id' => 'EmailTemplateEmailContent'.$this->data['EmailTemplate']['id']));
	echo $form->end(__l('Update'));
?>
</div>