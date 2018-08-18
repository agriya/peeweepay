<?php /* SVN: $Id: $ */ ?>
<div>
    <div>
        <div>
            <h3><?php echo $html->link(__l('Countries'), array('action' => 'index'),array('title' => __l('Countries')));?> &raquo; <?php echo __l('Edit Country - ').$html->cText($this->data['Country']['name'], false); ?> </h3>
        </div>
        <div>
            <?php echo $form->create('Country', array('action' => 'edit', 'class' => 'normal'));?>
            <?php
            echo $form->input('id');
            echo $form->input('name', array('label' => __l('Name')));
            echo $form->input('fips104', array('label' => __l('Fips104')));
    		echo $form->input('iso2', array('label' => __l('ISO2')));
    		echo $form->input('iso3', array('label' => __l('ISO3')));
    		echo $form->input('ison', array('label' => __l('ISON')));
    		echo $form->input('internet', array('label' => __l('Internet')));
    		echo $form->input('capital', array('label' => __l('Capital')));
    		echo $form->input('map_reference', array('label' => __l('Map Reference')));
    		echo $form->input('nationality_singular', array('label' => __l('Nationality Singular')));
    		echo $form->input('nationality_plural', array('label' => __l('Nationality Plural')));
    		echo $form->input('currency', array('label' => __l('Currency')));
    		echo $form->input('currency_code', array('label' => __l('Currency Code')));
    		echo $form->input('population', array('label' => __l('Population'), 'info' => __l('Ex: 2001600')));
    		echo $form->input('title', array('label' => __l('Title')));
    		echo $form->input('comment', array('label' => __l('Comment')));
            ?>
            <?php echo $form->end(__l('Update'));?>
        </div>
    </div>
</div>
