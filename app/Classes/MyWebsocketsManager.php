<?php
namespace App\Classes;

use \Pusher\Pusher;

class MyWebsocketsManager
{
    static $_instance = null;

    public $pusher = null;
    public $data = [];

    public function __construct($key = null, $secret = null, $appId = null, $options = null)
    {
        $connection = config( 'broadcasting.connections.pusher' );
        $key = $key??$connection['key'];
        $secret = $secret??$connection['secret'];
        $appId = $appId??$connection['app_id'];
        $options = $options??$connection['options'];

        $this->pusher = new Pusher($key, $secret, $appId,$options,);
    }

    //Static:  private function calls go through here (call methods form outside)
    public static function __callStatic($method, $args)
    {
        $instance = self::getInstance();
        return call_user_func_array(array($instance, $method), $args);
    }

    //Instance:  private function calls go through here (call methods form outside)
    public function __call($method, $args)
    {
        $instance = self::getInstance();
        return call_user_func_array(array($instance, $method), $args);
    }

    public static function getInstance()
    {
        if (self::$_instance == null)
        {
            self::$_instance = new static();
        }

        return self::$_instance;
    }

    private function getChannels()
    {
        $chanels = $this->pusher->get_channels()->channels??[];
        $chanels = json_decode(json_encode($chanels),true);
        return array_keys($chanels);
    }
    private function getChannelUsers($chanel_name)
    {
        $users = $this->pusher->get_users_info($chanel_name)->users??[];
        $users = json_decode(json_encode($users),true);
        return  $users;
    }

    private function getData()
    {
        return $this->data;
    }

    private function setData($key, $value)
    {
        $this->data[$key] = $value;
        return $this;
    }

}
