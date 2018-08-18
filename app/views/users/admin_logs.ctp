<div class="users stats">
	<div>
        <h2><?php echo __l('Disk Space Stats'); ?></h2>
			<?php
				echo $html->link(__l('Purge Cache'), array('controller' => 'users', 'action' => 'clear_cache', 'admin' => 'true'));
			?>
            <dl class="list clearfix">
                <dt class="altrow"><?php echo __l('Used Cache Disk Space');?></dt>
		  			<dd class="altrow"><?php echo $tmpCacheFileSize; ?></dd>
                <dt><?php echo __l('Used Log Disk Space');?></dt>
		  			<dd><?php echo $tmpLogsFileSize; ?></dd>
            </dl>
	</div>
	<div>
		<h2><?php echo __l('Recent Errors & Logs'); ?></h2>
		<div>
			<h3><?php echo __l('Error Log')?></h3>
			<?php
				echo $html->link(__l('Clear Error Log'), array('controller' => 'users', 'action' => 'admin_clear_logs', 'type' => 'error_log'));
			?>
			<div><textarea rows="15" cols="90"><?php echo $error_log;?></textarea></div>
			<h3><?php echo __l('Debug Log')?></h3>
			<?php
			echo $html->link(__l('Clear Debug Log'), array('controller' => 'users', 'action' => 'admin_clear_logs', 'type' => 'debug_log'));
			?>
			<div><textarea rows="15" cols="90"><?php echo $debug_log;?></textarea></div>		
        </div>
	</div>
</div>