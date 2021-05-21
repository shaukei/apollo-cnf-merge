<?php

namespace Freya\ApolloCnf;

use ApolloSdk\Config\Client;
use Freya\ApolloCnf\ParseCnf;

class ApolloCnf
{
    private $opts = [
        // 功能开关
        'enable'        =>  true,

        // 使用额外进程（Swoole）处理
        'useProcess'    =>  true,

        // Apollo 服务主机
        'apolloHost'    =>  'http://127.0.0.1:8080',

        // 监听的Appid
        'appid'         =>  '',

        // 监听的集群
        'cluster'       =>  'default',

        // 监听的空间
        'namespaces'    =>  [],

        // 进程监听超时时间
        'interval'      =>  30,

        'client_ip'     =>  '',

        'secret'        =>  '',

        'mergeMode'     =>  \Freya\ApolloCnf\CnfMode\PhpFile::class,

        'path'          =>  './',

        'file'          =>  'default.php',

        'NSFile'        =>  false,
    ];

    public function __construct(array $opts = [])
    {
        $this->setOpts($opts);
    }

    public function getOpt($name)
    {
        return isset($this->opts[$name]) ? $this->opts[$name] : null;
    }

    public function setOpt($name, $value)
    {
        return $this->setOpts([$name=>$value]);
    }

    public function setOpts(array $opts)
    {
        $this->opts = array_merge(
            $this->opts,
            array_intersect_key(
                $opts,
                $this->opts
            )
        );
        return $this;
    }

    public function getOpts()
    {
        return $this->opts;
    }

    public function run()
    {
        $client = new Client([
            'config_server_url' =>  $this->opts['apolloHost'],
            'cluster_name'      =>  $this->opts['cluster'],
            'client_ip'         =>  $this->opts['client_ip'],
            'secret'            =>  $this->opts['secret'],
        ]);

        $namespaces = array_combine((array)$this->opts['namespaces'], array_pad([], count((array)$this->opts['namespaces']), 0));
        $appNotificationsData  = [
            $this->opts['appid'] => $namespaces,
        ];
        
        $client->listenMultiAppConfigUpdate(
            $appNotificationsData,
            //当某个应用的namespace更新了会触发下面这个回调函数
            //如果默认初始化应用的notificationId为-1，则每个应用在都会立即触发一次回调函数
            function ($appId, $namespaceName, $newConfig, $notificationId, $namespaceNotificationMapping) {
                $parser = new ParseCnf($newConfig);
                $driver = new $this->opts['mergeMode'](
                    $parser->parse(),
                    $this->opts['path'],
                    $this->opts['file'],
                    $this->opts['NSFile'],
                    $namespaceName
                );
                $cnfs = $driver->update();

                echo date('Y-m-d H:i:s').'___'.$appId.'___'.$namespaceName.'___'.$notificationId.PHP_EOL;
                // print_r($newConfig);//这个是被更新之后的配置
                // print_r($namespaceNotificationMapping);//这个是应用的namespace的notification映射列表，1.0.2版本及之后的版本提供了这个参数
                // echo PHP_EOL;
            },
            //监听配置变化时会进入http长连接轮询，每个接口响应的时候会触发下面这个方法
            function ($appId, \GuzzleHttp\Psr7\Response $response) {//1.0.4版本及之后的版本新增这个回调方法
                echo '应用：'.$appId.'完成一次http请求'.PHP_EOL;
                //想了解更多关于guzzle http的respone信息
                //参考这个文档https://guzzle-cn.readthedocs.io/zh_CN/latest/psr7.html#responses
                echo $response->getStatusCode().PHP_EOL;
            }
        );
    }
}
