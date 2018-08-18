<?php /* SVN: $Id: admin_add.ctp 13910 2010-07-16 14:34:46Z siva_063at09 $ */ ?>
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
    <?php echo $this->element('js_tiny_mce_setting', array('cache' => array('time' => '120')));?>
    <div class="pages form">
        <?php echo $form->create('Page', array('class' => 'normal'));?>
        <fieldset>
     		<h2><?php echo __l('Add Page');?></h2>
            <?php
                echo $form->input('title', array('between' => '', 'label' =>__l('Page title')));
                echo $form->input('content', array('type' => 'textarea', 'class' => 'js-editor', 'label' => __l('Body'), 'info' => __l('Available Variables: ##SITE_NAME##, ##SITE_URL##, ##ABOUT_US_URL##, ##CONTACT_US_URL##, ##FAQ_URL##')));                
                echo $form->input('description_meta_tag',array('label' => __l('Description Meta Tag')));
                echo $form->input('slug',array('label' => __l('Slug'),'info' => __l('When you create link for this page, url should be page/value of this field.')));
			?>
            <div class="submit-block clearfix">
            	<?php
					echo $form->submit(__l('Add'), array('name' => 'data[Page][Add]'));
					echo $form->submit(__l('Preview'), array('name' => 'data[Page][Preview]'));
				?>
            </div>
        </fieldset>
            <?php echo $form->end();  ?>
    </div>
</div>
<?php
    if(!empty($page)):
    ?>
    </div> <!-- js-tabs end !>
    <?php
endif;
?>
