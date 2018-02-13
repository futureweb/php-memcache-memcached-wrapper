<?php
/**
 * Procedural drop in replacement for legacy projects using the memcache Function
 * https://www.futureweb.at
 *
 * @author Andreas Schnederle-Wagner
 * @version 0.1
 */

// Make sure the memcache Extension is not loaded and there is no other drop in replacement active
if (!extension_loaded('memcache') && !function_exists('memcache_connect')) {
    
    // Validate if the MySQLi extension is present
    if (!extension_loaded('memcached')) {
        trigger_error('The extension "memcached" is not available', E_USER_ERROR);
    }
    
    // The function name "getMemcachedLinkIdentifier" will be used to return a valid link_indentifier, check if it is available
    if (function_exists('getMemcachedLinkIdentifier')) {
        trigger_error('The function name "getMemcachedLinkIdentifier" is already defined, please change the function name', E_USER_ERROR);
    }
    
    // Will contain the link identifier
    $memcachedLink = null;
    
    /**
     * Get the link identifier
     *
     * @param $memcached
     * @return memcached|null
     */
    function getMemcachedLinkIdentifier($memcached = null)
    {
        if (!$memcached) {
            global $memcachedLink;
            $memcached = $memcachedLink;
        }
        
        return $memcached;
    }
    
    /**
     * Open a connection to a memcache Server
     *
     * @param $host
     * @param $port
     * @param $timeout
     * @return bool
     */
    function memcache_connect($host, $port = 11211, $timeout = 1)
    {
        global $memcachedLink;
        
        $memcachedLink = new Memcached;
        $memcachedLink->setOption(Memcached::OPT_LIBKETAMA_COMPATIBLE, true);
        //$memcachedLink->setOption(Memcached::OPT_BINARY_PROTOCOL, true); //can be activated if needed
        $memcachedLink->addServer($host, $port);
        
        //need to check if connection was successfully established ... and return object || false
        $conStat = $memcachedLink->getStats();
        if($conStat[$host.':'.$port]['uptime']<1){
            return false;
        }else{
            return $memcachedLink;
        }
    }
   
    /**
     * Close memcached server connection
     *
     * @param $memcached
     * @return return|false
     */
    function memcache_close($memcached = null)
    {
        return getMemcachedLinkIdentifier($memcached)->close($key);
    }
    
    /**
     * Add a memcached server to connection pool
     *
     * @param $memcached
     * @param $host
     * @param $port
     * @param $persistent
     * @param $weight
     * @param $timeout
     * @param $retry_interval
     * @param $status
     * @param $failure_callback
     * @param $timeoutms
     * @return bool
     */
    function memcache_addServer($memcached = null, $host, $port = 11211, $persistent = false, $weight, $timeout, $retry_interval, $status, $failure_callback, $timeoutms)
    {
        return getMemcachedLinkIdentifier($memcached)->addServer($host, $port, $weight);
    }
    
    /**
     * Retrieve item from the server
     *
     * @param $memcached
     * @param $key
     * @param $flags
     * @return return|false
     */
      function memcache_get($memcached = null, $key, $flags = false)
      {
          return getMemcachedLinkIdentifier($memcached)->get($key);
      }
    
    /**
     * Store data at the server
     *
     * @param $memcached
     * @param $key
     * @param $var
     * @param $flag
     * @param $timeout
     * @return bool
     */
      function memcache_set($memcached = null, $key, $var, $flag = false, $timeout = 0)
      {
          return getMemcachedLinkIdentifier($memcached)->set($key, $var, $timeout);
      }
    
    /**
     * Replace value of the existing item
     *
     * @param $memcached
     * @param $key
     * @param $var
     * @param $flag
     * @param $timeout
     * @return bool
     */
    function memcache_replace($memcached = null, $key, $var, $flag = false, $timeout = 0)
    {
        return getMemcachedLinkIdentifier($memcached)->replace($key, $var, $timeout);
    }

    /**
     * Store data at the server if key not exists
     *
     * @param $memcached
     * @param $key
     * @param $var
     * @param $flag
     * @param $timeout
     * @return bool
     */
    function memcache_add($memcached = null, $key, $var, $flag = false, $timeout = 0)
    {
        return getMemcachedLinkIdentifier($memcached)->add($key, $var, $timeout);
    }
    
    /**
     * Delete item from the server
     *
     * @param $memcached
     * @param $key
     * @return bool
     */
    function memcache_delete($memcached = null, $key, $timeout = false)
    {
        return getMemcachedLinkIdentifier($memcached)->delete($key);
    }

    /**
     * Increment item's value
     *
     * @param $memcached
     * @param $key
     * @param $val
     * @return bool
     */
    function memcache_increment($memcached = null, $key, $val)
    {
        return getMemcachedLinkIdentifier($memcached)->increment($key, $val);
    }

    /**
     * Decrement item's value
     *
     * @param $memcached
     * @param $key
     * @param $val
     * @return bool
     */
    function memcache_decrement($memcached = null, $key, $val)
    {
        return getMemcachedLinkIdentifier($memcached)->decrement($key, $val);
    }
    
    /**
     * Flush all existing items at the server
     *
     * @param $memcached
     * @return bool
     */
    function memcache_flush($memcached = null)
    {
        return getMemcachedLinkIdentifier($memcached)->flush();
    }
    
    /**
     * Get statistics from all servers in pool
     *
     * @todo implement this Function
     */
    function memcache_getExtendedStats($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Returns server status
     *
     * @todo implement this Function
     */
    function memcache_getServerStatus($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Get statistics of the server
     *
     * @todo implement this Function
     */
    function memcache_getStats($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Return version of the server
     *
     * @todo implement this Function
     */
    function memcache_getVersion($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Enable automatic compression of large values
     *
     * @todo implement this Function
     */
    function memcache_setCompressThreshold($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Turn debug output on/off
     *
     * @todo implement this Function
     */
    function memcache_debug($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Changes server parameters and status at runtime
     *
     * @todo implement this Function
     */
    function memcache_setServerParams($memcached = null)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
    
    /**
     * Open memcached server persistent connection
     *
     * @todo implement this Function
     */
    function memcache_pconnect($host, $port = 11211, $timeout = 1)
    {
        trigger_error('This function is yet not implemented', E_USER_WARNING);
        return false;
    }
}   
?>
