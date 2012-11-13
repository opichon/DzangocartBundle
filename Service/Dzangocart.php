<?php

namespace Dzangocart\Bundle\DzangocartBundle\Service;

class Dzangocart
{
    protected $url;    
    protected $test_code;
    protected $secret_key;

    public function __construct($url, $test_code, $secret_key)
    {
        $this->url = $url;
        $this->test_code = $test_code;
        $this->secret_key = $secret_key;
    }
    
    public function getUrl()
    {
        return $this->url;
    }
    
    public function getTestCode()
    {
        return $this->test_code;
    }
    
    public function getSecretKey()
    {
        return $this->secret_key;
    }
}
