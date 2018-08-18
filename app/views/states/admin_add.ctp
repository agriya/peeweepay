<?php /* SVN: $Id: $ */ ?>
<div class="states form">
    <div>
        <div>
            <h3><?php echo $html->link(__l('States'), array('action' => 'index'),array('title' => __l('States')));?> &raquo; <?php echo __l('Add State'); ?> </h3>
        </div>
        <div>
            <?php echo $form->create('State',  array('class' => 'normal','action'=>'add'));?>
            <?php
                echo $form->input('country_id',array('empty'=>__l('Please Select')));
                echo $form->input('name', array('label' => __l('Name')));
                echo $form->input('code', array('label' => __l('Code')));
                echo $form->input('adm1code', array('label' => __l('Adm1code')));
                echo $form->input('is_approved', array('label' => __l('Approved?')));
            ?>
            <?php echo $form->end(__l('Add'));?>
        </div>
    </div>
</div>