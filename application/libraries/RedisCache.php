<?php
class RedisCache
{
    private static $instance;

    private $redisObj;

    private function __construct($conf, $option = array())
    {
        if(! (is_array($conf) && ! empty($conf))) {
            $CI = & get_instance();
            $CI->load->config('redis', TRUE);
            $rc = $CI->config->item('redis');
            if(empty($conf)) {
                $conf = 'default';
            }
            $conf = $rc[$conf];
        }
        $this->redisObj = new Redis();

        if(! @$this->redisObj->connect($conf['host'], $conf['port'])) {
            throw new System_Exception('REDIS('.$conf['host'].')连接失败');
        }
        if(isset($conf['pass']) && $conf['pass'] != '') {
            if(! $this->redisObj->auth($conf['pass'])) {
                throw new System_Exception('REDIS('.$conf['host'].')连接失败');
            }
        }
        if(isset($conf['db']) && $conf['db'] != '') {
            if(! $this->redisObj->select($conf['db'])) {
                throw new System_Exception('REDIS('.$conf['host'].')连接失败');
            }
        }
        if(! empty($option)) {
            foreach($option as $key => $val) {
                $this->redisObj->setOption($key, $val);
            }
        }
    }

    public static function instance($conf = '', $option = array())
    {
        if(is_array($conf)) {
            $k = md5(implode(",", $conf));
        } else {
            $k = md5($conf);
        }
        if(! isset(self::$instance[$k]) || ! self::$instance[$k] instanceof self) {
            self::$instance[$k] = new self($conf, $option);
        }
        return self::$instance[$k];
    }

    public function get($key)
    {
        $result = $this->redisObj->get($key);
        return unserialize($result);
    }

    public function mget($keys)
    {
        $rtn = array();
        $result = $this->redisObj->mget($keys);
        if(is_array($result)) {
            foreach($result as $val) {
                $rtn[] = unserialize($val);
            }
        }
        return $rtn;
    }

    public function set($key, $data, $ttl = 3600)
    {
        $data = serialize($data);
        return ($ttl) ? $this->redisObj->setex($key, $ttl, $data) : $this->redisObj->set($key, $data);
    }

    public function delete($key)
    {
        return $this->redisObj->delete($key);
    }

    public function expire($key, $ttl)
    {
        return $this->redisObj->expire($key, $ttl);
    }

    public function incr($key)
    {
        return $this->redisObj->incr($key);
    }

    public function decr($key)
    {
        return $this->redisObj->decr($key);
    }

    public function exists($key)
    {
        return $this->redisObj->exists($key);
    }

    public function ttl($key)
    {
        return $this->redisObj->ttl($key);
    }

    public function sAdd($store_name, $value)
    {
        return $this->redisObj->sAdd($store_name, $value);
    }

    public function sRemove($store_name, $value)
    {
        return $this->redisObj->sRemove($store_name, $value);
    }

    public function sMembers($store_name)
    {
        return $this->redisObj->sMembers($store_name);
    }

    public function sUnion($store)
    {
        return $this->redisObj->sUnion($store);
    }

    public function cache()
    {
        return $this->redisObj;
    }
}