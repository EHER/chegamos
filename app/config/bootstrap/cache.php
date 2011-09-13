<?php
/**
 * Lithium: the most rad php framework
 *
 * @copyright     Copyright 2010, Union of RAD (http://union-of-rad.org)
 * @license       http://opensource.org/licenses/bsd-license.php The BSD License
 */

/**
 * This file creates a default cache configuration using the most optimized adapter available, and
 * uses it to provide default caching for high-overhead operations.
 */
use lithium\storage\Cache;
use lithium\core\Libraries;
use lithium\action\Dispatcher;
use lithium\storage\cache\adapter\Memcache;
use lithium\storage\cache\adapter\File;

if (PHP_SAPI === 'cli') {
	return;
}

/**
 * If APC is not available and the cache directory is not writeable, bail out.
 */
if (!($memcacheEnabled = Memcache::enabled()) && !is_writable(LITHIUM_APP_PATH . '/resources/tmp/cache')) {
	return;
}


if($memcacheEnabled) {
    Cache::config(array(
        'default' => array(
            'adapter' => 'Memcache',
            'host' => '127.0.0.1:11211'
        )
    ));
} else {
    Cache::config(array(
        'default' => array('adapter' => 'File')
    ));
}


Dispatcher::applyFilter('run', function($self, $params, $chain) {
	if ($cache = Cache::read('default', 'core.libraries')) {
		$cache = (array) unserialize($cache) + Libraries::cache();
		Libraries::cache($cache);
	}
	$result = $chain->next($self, $params, $chain);

	if ($cache != Libraries::cache()) {
		Cache::write('default', 'core.libraries', serialize(Libraries::cache()), '+1 day');
	}
	return $result;
});

?>