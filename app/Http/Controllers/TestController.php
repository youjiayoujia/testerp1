<?php
/**
 * 仓库控制器
 * 处理仓库相关的Request与Response
 *
 * @author: MC<178069409@qq.com>
 * Date: 15/12/18
 * Time: 15:22pm
 */

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Jobs\test;
use App\Jobs\test1;
use Queue;
use App\Models\StockModel;
use Illuminate\Foundation\Bus\DispatchesJobs;

class TestController extends Controller
{
    use DispatchesJobs;

    public function test()
    {
      $obj = StockModel::where(['warehouses_id'=>12, 'items_id'=>4])->get()->first()->position;
      var_dump($obj->toArray());
      exit;
    }
    // public function test()
    // {
    //   error_reporting(E_ALL);
    //   ini_set('display_errors', 0);

    //   getType($type);
    //   getType();
    //   get_type();

    // }

    public function test3()
    {
        // set_time_limit(0);

        // $port = 32655;
        // $ip = '127.0.0.1';

        // if(($sock = socket_create(AF_INET,SOCK_STREAM,SOL_TCP)) === false) {
        //     echo 'sock创建失败';
        // }
        // if(socket_bind($sock, $ip, $port) === false) {
        //     echo 'bind失败';
        // }

        // if(socket_listen($sock, 5) === false){
        //      echo 'listen失败';
        // }
        // echo 'listen.......';
        // do{
        //     if(($msg = socket_accept($sock)) == false) {
        //         echo 'accept失败';
        //         break;
        //     }            
        //     $tmp = socket_read($msg, 1024);
        //     var_dump($tmp);
        //     $buf = 'server in test';
        //     socket_write($msg, $buf, strlen($buf));


        // }while(1);

        // socket_close($sock);
        $job = (new test1($this))->onQueue('mc');
        $this->dispatch($job);
    }

    public function wri()
    {
      $fd = fopen('d:/mc.txt');
      fwrite($fd, "love you dajie\r\n");
      fclose($fd);
    }

    public function test1()
    {
       $port = 32655;
       $ip = '127.0.0.1';

       if(($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) == false){
            echo 'socket创建失败';
            exit;
       }

       if(($result = socket_connect($sock, $ip, $port)) == false) {
            echo '连接失败';
            exit;
       }
       $buf = 'client';

       socket_write($sock, $buf, strlen($buf));
       $tmp = socket_read($sock, 1234);
       var_dump($tmp);
       sleep(30);
       socket_close($sock);
    }
}