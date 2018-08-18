<?php /* SVN: $Id: admin_edit.ctp 13910 2010-07-16 14:34:46Z siva_063at09 $ */ ?>
<?php echo $this->element('js_tiny_mce_setting', array('cache' => array('time' => '120')));?>
<?php
    if(!empty($page)):
        ?>
        <div class="js-tabs">
        <ul>
            <li><span><?php echo $html->link(__l('Preview'), '#preview'); ?></span></li>
            <li><span><?php echo $html->link(__l('Change'), '#add'); ?></span></li>
        </ul>
        <div id="preview">
            <div class="page">
                <h2><?php echo $page['Page']['title']; ?></h2>
                <div class="entry">
                   <?php echo $page['Page']['content']; ?>
                </div>
            </div>
        </div>
        <?php
    endif;
?>
<div id="add">
    <h2><?php echo __l('Edit Page'); ?></h2>
    <div class="pages form">      
        <fieldset>
            <?php
				echo $form->create('Page', array('class' => 'normal'));
                echo $form->input('id');
                echo $form->input('title', array('between' => '', 'label' => __l('Page title')));
                echo $form->input('content', array('type' => 'textarea', 'class' => 'js-editor', 'label' =>__l('Body'), 'info' => __l('Available Variables: ##SITE_NAME##, ##SITE_URL##, ##ABOUT_US_URL##, ##CONTACT_US_URL##, ##FAQ_URL##, ##SITE_CONTACT_PHONE##, ##SITE_CONTACT_EMAIL##')));                
                echo $form->input('slug',array('label' => __l('Slug'),'info' => __l('If you change value of this field then don\'t forget to update links created for this page. It should be page/value of this field.')));
				?>
                <div class="submit-block clearfix">
                <?php echo $form->submit(__l('Update'), array('name' => 'data[Page][Update]')); ?>
                    <div class = "cancel-block">
                        <?php  echo  $html->link(__l('Cancel'), array('controllers' => 'pages', 'action' => 'index' ), array('title' => 'Cancel'));?>
                     </div>
               </div>
            	<?php echo $form->end(); ?>
        </fieldset>
    </div>
</div>
<?php
    if(!empty($page)):
    ?>
    </div> <!-- js-tabs end !>
    <?php
endif;
?>