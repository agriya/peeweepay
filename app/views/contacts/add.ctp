<div class="js-responses colorbox-content">
<?php if(isset($success)) : ?>
    <div class="success-msg">
        <?php echo __l('Thank you, we received your message and will get back to you as soon as possible.'); ?>
    </div>
<?php else: ?>
    <h2><?php echo __l('Contact Us'); ?></h2>
    <?php
        echo $form->create('Contact', array('class' => 'normal contact-form js-ajax-form'));
        echo $form->input('first_name', array('label' => __l('First Name')));
        echo $form->input('last_name', array('label' => __l('Last Name')));
        echo $form->input('email', array('label' => __l('Email')));
        echo $form->input('telephone', array('label' => __l('Telephone')));
        echo $form->input('subject', array('label' => __l('Subject')));
        echo $form->input('message', array('label' => __l('Message')));
    ?>    
	<div class="captcha-block clearfix js-captcha-container">
    			<div class="captcha-left">
    	           <?php echo $html->image($html->url(array('controller' => 'users', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>
    	        </div>
    	        <div class="captcha-right">
        	        <?php echo $html->link(__l('Reload CAPTCHA'), '#', array('class' => 'js-captcha-reload captcha-reload', 'title' => __l('Reload CAPTCHA')));?>
	               <div>
		              <?php echo $html->link(__l('Click to play'), Router::url('/', true)."flash/securimage/play.swf?audio=". $html->url(array('controller' => 'users', 'action'=>'captcha_play'), true) ."&amp;bgColor1=#777&amp;bgColor2=#fff&amp;iconColor=#000&amp;roundedCorner=5&amp;height=19&amp;width=19", array('class' => 'js-captcha-play')); ?>
			      </div>
    	        </div>
            </div>
    <?php
        echo $form->input('captcha', array('label' => __l('Security Code')));
        echo $form->end(__l('Send'));
    ?>
<?php endif; ?>
</div>