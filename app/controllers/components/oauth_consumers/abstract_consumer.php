<?php
/**
 * Abstract base class for OAuth consumers. 
 * 
 * A typical class extending this base class looks like:
 * 
 * class FireEagleConsumer extends AbstractConsumer {
 *     public public function __construct() {
 * 	       parent::__construct('key', 'secret');
 *     }
 * }
 * 
 * The following conventions apply for subclasses:
 * - class name has to end with "Consumer"
 * - each class has to be in its own file, the name ending with "_consumer.php"
 * - class name is camel-cased, file name uses underscores, e.g. FireEagleConsumer 
 *   and fire_eagle_consumer.php 
 * 
 * Copyright (c) by Daniel Hofstetter (http://cakebaker.42dh.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 */

abstract class AbstractConsumer {
	private $consumerKey = null;
	private $consumerSecret = null;
	
	public function __construct($consumerKey, $consumerSecret) {
		$this->consumerKey = $consumerKey;
		$this->consumerSecret = $consumerSecret;
	}
	
	final public public function getConsumer() {
		return new OAuthConsumer($this->consumerKey, $this->consumerSecret);
	}
}
