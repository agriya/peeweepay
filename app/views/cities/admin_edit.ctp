<?php /* SVN: $Id: $ */ ?>
<div>
    <div>
        <div>
            <h3><?php echo $html->link(__l('Cities'), array('action' => 'index'),array('title' => __l('Cities')));?> &raquo; <?php echo __l('Edit City - ').$html->cText($this->data['City']['name'], false); ?></h3>
        </div>
        <div>
            <?php echo $form->create('City', array('class' => 'normal','action'=>'edit'));?>
            <?php
                echo $form->input('id');
                echo $form->input('country_id', array('empty'=>'Please Select'));
                echo $form->input('state_id', array('empty'=>'Please Select'));
                echo $form->input('name', array('label' => __l('Name')));
                echo $form->input('latitude', array('label' => __l('Latitude')));
                echo $form->input('longitude', array('label' => __l('Longitude')));
                echo $form->input('timezone', array('label' => __l('Timezone')));
                echo $form->input('county', array('label' => __l('County')));
                echo $form->input('code', array('label' => __l('Code')));
                echo $form->input('is_approved', array('label' => 'Approved?'));
            ?>
            <?php echo $form->end(__l('Update'));?>
        </div>
    </div>
</div>
