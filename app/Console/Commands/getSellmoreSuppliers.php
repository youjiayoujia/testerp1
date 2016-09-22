<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class getSellmoreSuppliers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suppliers:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $url = 'http://v2.erp.moonarstore.com/admin/auto/getSuppliers/getSuppliersData?key=SLME5201314&page=1';

        $data = json_decode($this->getCurlData($url));
        for($i = 1; $i>0; $i++){
            $url = 'http://v2.erp.moonarstore.com/admin/auto/getSuppliers/getSuppliersData?key=SLME5201314&page='.$i;
            $data = json_decode($this->getCurlData($url));
            if(empty($data)){
                break;
            }else{
                foreach ($data as $value){
                    
                }
            }


        }

/*        for($i = 1){

        }*/





    }

    /**
     * Curl http Get 数据
     * 使用方法：
     */
    public function getCurlData($remote_server)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $remote_server);
        //curl_setopt ( $ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded; charset=utf-8'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 获取数据返回
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true); // 在启用 CURLOPT_RETURNTRANSFER 时候将获取数据返回
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        $output = curl_exec($ch);
        if (curl_errno($ch)) {
            // $this->setCurlErrorLog(curl_error ( $ch ));
            die(curl_error($ch)); //异常错误
        }
        curl_close($ch);
        return $output;
    }
}
