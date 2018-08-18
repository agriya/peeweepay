<div class="js-response js-responses">
    <div class="js-pagination refresh-block">
    <?php
    echo $html->link(__l('Refresh'), array('controller' => 'users', 'action' => 'splashboard', 'refresh', 'admin' => true),array('class' => 'refresh'));
    ?>
    </div>
	<?php echo $this->element('admin_stats-dashboard', array('cache' => array('time' => '1 hour')));?>
</div>