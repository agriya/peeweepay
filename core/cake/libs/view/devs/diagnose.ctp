<?php
/* SVN: $Id: install.ctp 119 2008-07-28 13:10:19Z rajesh_04ag02 $ */
$this->pageTitle = __l('Install');
if (Configure::read() > 0):
	Debugger::checkSessionKey();
else:
    // Important: die when site is live
    $this->cakeError('error404');
endif;
?>
<div class="pages">
 <h2><?php echo $this->pageTitle;?></h2>
 <div class="notice">
    <p>This file is devs/diagnose.ctp</p>
    
    <p>This will intentionally throw 404 error when debug value is set to 0</p>
 </div>
<div>
    <div>
        <?php echo __l('Cache folder Size: ').$tmpCacheFileSize; ?>
    </div>
    <div>
        <?php echo __l('Log folder Size: ').$tmpLogsFileSize; ?>
    </div>
    <h3><?php echo __l('Write Permission Check');?></h3>
    <ul>
    <?php echo $writable; ?>
    </ul>
</div>
 <h3><?php echo __l('Cache Settings Check');?></h3>
<p>
<?php
	$settings = Cache::settings();
	if (!empty($settings)):
		echo '<span class="success">';
				echo sprintf(__('The %s is being used for caching. To change the config edit APP/config/core.php ', true), '<em>'. $settings['engine'] . 'Engine</em>');
		echo '</span>';
	else:
		echo '<span class="error">';
		echo __l('Your cache is NOT working. Please check the settings in APP/config/core.php');
		echo '</span>';
	endif;
?>
</p>
 <h3><?php echo __l('Database Check');?></h3>
<p>
<?php
	$filePresent = null;
	if (file_exists(CONFIGS . 'database.php')):
		echo '<span class="success">';
		echo __l('Your database configuration file is present.');
			$filePresent = true;
		echo '</span>';
	else:
		echo '<span class="error">';
		echo __l('Your database configuration file is NOT present.');
			echo '<br/>';
		echo __l('Rename config/database.php.default to config/database.php');
		echo '</span>';
	endif;
?>
</p>
<?php
if (!empty($filePresent)):
 	uses('model' . DS . 'connection_manager');
	$db = ConnectionManager::getInstance();
 	$connected = $db->getDataSource('default');
?>
<p>
<?php
	if ($connected->isConnected()):
		echo '<span class="success">';
 		echo __l('Cake is able to connect to the database.');
		echo '</span>';
	else:
		echo '<span class="error">';
		echo __l('Cake is NOT able to connect to the database.');
		echo '</span>';
	endif;
?>
</p>
<?php endif;?>
 <h3><?php echo __l('Configurations');?></h3>
<p>
    Site name is set to <em><?php echo Configure::read('site.name');?></em> Edit value at config/config.php
</p>
 <h3><?php echo __l('Source Code Analysis');?></h3>
<ul>
<?php
function AnalyzeRecursive($dir)
{
    $handle = opendir($dir);
    while (false !== ($readdir = readdir($handle))) {
        if ($readdir != '.' && $readdir != '..' && $readdir != '.svn') {
            $path = $dir . DS . $readdir;
            if (is_dir($path)) {
                AnalyzeRecursive($path);
            }
            if (is_file($path) && (strpos($path, 'install.ctp') === false) && ((strpos($path, '.php') !== false) || (strpos($path, '.ctp') !== false))) {
                $contents = file_get_contents($path);
                $err = '';
                if (stripos($contents, 'pr(') !== false) {
                    $err.= '<li><span class="error">Use of pr()</span> Remove debug code</li>' . "\n";
                }
                if (stripos($contents, 'die(') !== false) {
                    $err.= '<li><span class="error">Use of die()</span> Remove debug code</li>' . "\n";
                }
                if (stripos($contents, 'debug(') !== false) {
                    $err.= '<li><span class="notice">Use of debug()</span> Keep debug() only if it\'s really important</li>' . "\n";
                }
                if (stripos($contents, 'log(') !== false) {
                    $err.= '<li><span class="notice">Use of log()</span> Keep log() only if it\'s really important</li>' . "\n";
                }
                if (strpos($contents, '__(') !== false) {
                    $err.= '<li><span class="error">Use of __()</span> Use <code>__l()</code> instead</li>';
                }
                if ((strpos($path, 'controllers') !== false) || (strpos($path, 'models') !== false)) {
                    if (stripos($contents, 'exit') !== false) {
                        $err.= '<li><span class="error">Use of exit</span> Use <code>$this->autoRender = false;</code> instead</li>' . "\n";
                    }
                    if (stripos($contents, '$this->params[\'requested\']') !== false) {
                        $err.= '<li><span class="error">Use of <code>$this->params[\'requested\']</code></span> Use recent draft on pagination instead</li>' . "\n";
                    }
                    if (stripos($contents, 'attach(\'Containable\')') !== false) {
                        $err.= '<li><span class="error">Use of <code>attach(\'Containable\')</code></span> Containable behavior is automatically/already attached; no need to duplicate it.</li>' . "\n";
                    }
                    if (stripos($contents, '$this->Session->setFlash(__l(\'Invalid') !== false) {
                        $err.= '<li><span class="error">Use of $this->Session->setFlash(__l(\'Invalid</span> Usage obsolete.  Check <code>$this->cakeError(\'error404\');</code></li>' . "\n";
                    }
                    if (stripos($contents, '404') !== false) {
                        $err.= '<li><span class="notice">Use of $this->redirect(..404..) ? (detection not accurate)</span> Usage obsolete.  Check <code>$this->cakeError(\'error404\');</code></li>' . "\n";
                    }
                    if ((stripos($contents, 'if(') !== false) || (strpos($contents, '){') !== false) || (strpos($contents, '),') !== false) || (strpos($contents, 'array(\'') !== false)) {
                        $err.= '<li><span class="error">Indentation</span> Use Devl to indent codes</li>' . "\n";
                    }
                }
                if (strpos($path, 'models') !== false) {
                    if (stripos($contents, '$order') !== false) {
                        $err.= '<li><span class="error">Use of $order</span> Usage leads inefficient queries. Add \'order\' in <code>find()</code></li>' . "\n";
                    }
                }
                if ($err) {
                    echo '<li>' . ($path) . "\n";
                    echo '<ul>' . "\n";
                    echo $err;
                    echo '</ul>' . "\n";
                    echo '</li>' . "\n";
                }
            }
        }
    }
    closedir($handle);
}
AnalyzeRecursive(CONTROLLERS);
AnalyzeRecursive(MODELS);
AnalyzeRecursive(VIEWS);
?>
</ul>
    <h3><?php echo __l('Debug.log');?></h3>
    <?php echo nl2br($debugLog); ?>
    <h3><?php echo __l('Debug.log');?></h3>
    <?php echo nl2br($errorLog); ?>

</div>
