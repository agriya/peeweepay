<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="countries index js-response">
    <h2><?php echo __l('Countries');?></h2>
      
    <?php echo $form->create('Country', array('type' => 'get', 'class' => 'normal filter-form clearfix','action'=>'index'));?>
    <div>
        <?php echo $form->input('q', array('label' => __l('Keyword'))); ?> </div>
       <div class="submit-block"> <?php echo $form->submit(__l('Filter')); ?>
    </div>
    <?php echo $form->end(); ?>
    <div>
      <div class="add-block">
            <?php echo $html->link(__l('Add'),array('controller'=>'countries','action'=>'add'),array('title' => __l('Add New Country'), 'class' => 'admin-add'));?>
			<?php echo $this->element('paging_counter');?>
        </div>
        <div>
            <?php echo $form->create('Country' , array('action' => 'update','class'=>'normal'));?>
            <?php echo $form->input('r', array('type' => 'hidden', 'value' => $this->params['url']['url'])); ?>
            <div class="overflow-block set-scroll">
            <table class="list">
                <tr>
                    <th rowspan="2"><?php echo __l('Select'); ?></th>
                  
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'name');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Fips104'), 'fips104');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('ISO2'), 'iso2');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('ISO3'), 'iso3');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('ISON'), 'ison');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Internet'), 'internet');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Capital'), 'capital');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Map Reference'), 'map_reference');?></div></th>
                    <th colspan="2"><?php echo __l('Nationality');?></th>
                    <th colspan="2"><?php echo __l('Currency');?></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Population'), 'population');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Title'), 'title');?></div></th>
                    <th rowspan="2"><div class="js-pagination"><?php echo $paginator->sort(__l('Comment'), 'comment');?></div></th>
                </tr>
                <tr>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Singular'), 'nationality_singular');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Plural'), 'nationality_plural');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Name'), 'currency');?></div></th>
                    <th><div class="js-pagination"><?php echo $paginator->sort(__l('Code'), 'currency_code');?></div></th>

                </tr>
                <?php
                if (!empty($countries)):
                    $i = 0;
                    foreach ($countries as $country):
                        $class = null;
                        if ($i++ % 2 == 0) :
                            $class = ' class="altrow"';
                        endif;
                        ?>
                        <tr<?php echo $class;?>>
                            <td>
								<?php echo $form->input('Country.'.$country['Country']['id'].'.id',array('type' => 'checkbox', 'id' => "admin_checkbox_".$country['Country']['id'],'label' => false , 'class' => 'js-checkbox-list'));                                ?>
                                <div class="actions-block">
									<div class="actions round-5-left">
										<span><?php echo $html->link(__l('Edit'), array('action'=>'edit', $country['Country']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span><span><?php echo $html->link(__l('Delete'), array('action'=>'delete', $country['Country']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>
									</div>
								</div>
                            </td>
                            <td><?php echo $html->cText($country['Country']['name']);?></td>
                            <td><?php echo $html->cText($country['Country']['fips104']);?></td>
                            <td><?php echo $html->cText($country['Country']['iso2']);?></td>
                            <td><?php echo $html->cText($country['Country']['iso3']);?></td>
                            <td><?php echo $html->cText($country['Country']['ison']);?></td>
                            <td><?php echo $html->cText($country['Country']['internet']);?></td>
                            <td><?php echo $html->cText($country['Country']['capital']);?></td>
                            <td><?php echo $html->cText($country['Country']['map_reference']);?></td>
                            <td><?php echo $html->cText($country['Country']['nationality_singular']);?></td>
                            <td><?php echo $html->cText($country['Country']['nationality_plural']);?></td>
                            <td><?php echo $html->cText($country['Country']['currency']);?></td>
                            <td><?php echo $html->cText($country['Country']['currency_code']);?></td>
                            <td><?php echo $html->cInt($country['Country']['population']);?></td>
                            <td><?php echo $html->cText($country['Country']['title']);?></td>
                            <td><?php echo $html->cText($country['Country']['comment']);?></td>
                        </tr>
                        <?php
                    endforeach;
                else:
                    ?>
                    <tr>
                        <td class="notice" colspan="19"><?php echo __l('No countries available');?></td>
                    </tr>
                    <?php
                endif;
                ?>
            </table>
            </div>
            <?php if (!empty($countries)): ?>
            <div class="admin-select-block">
                <div>
                    <?php echo __l('Select:'); ?>
                    <?php echo $html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                    <?php echo $html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
                </div>
              
                <div>
                    <?php echo $form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
                </div>
                </div>
                  <div class="js-pagination">
                    <?php echo $this->element('paging_links');  ?>
                </div>
                <div class="hide">
                    <?php echo $form->submit(__l('Submit'));  ?>
                </div>
                <?
            endif;
            echo $form->end();
            ?>
        </div>
    </div>
</div>