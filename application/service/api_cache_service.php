<?php
	
class Api_Cache_Service extends MY_Service
{
    /**
     * REDIS缓存示例
     */
    public function demo()
    {
        return RedisCache::instance('demo', array(
            Redis::OPT_PREFIX => 'demo:'
        ));
    }
}