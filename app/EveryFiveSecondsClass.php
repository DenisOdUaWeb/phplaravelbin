<?php

namespace App;
use Binance;
//use App\EveryFiveSecondsClass;
//require 'vendor/jaggedsoft/php-binance-api/php-binance-api.php';
//require 'vendor/autoload.php';
//C:\pr_fol\phpbin\vendor\jaggedsoft\php-binance-api\php-binance-api.php
class EveryFiveSecondsClass
{
    private $asset = ['LTC','SAND', 'DOT', 'ADA'];

    private $ADAsold = '?';

    //$this->$assetsUSDT = ['LTCUSDT','SANDUSDT', 'DOTUSDT', 'ADAUSDT'];
    /**
     * Create a new class instance.
     */
    public function __invoke()
    {
        
        
        $api = new Binance\API();
        $ticker = $api->prices();
        $balances = $api->balances($ticker);
        //
        //require 'vendor/autoload.php';
        //$api = new Binance\API();
        //$price = $api->price("BNBUSDT");
        //echo "Price of BNB: {$price} USDT.".PHP_EOL;
        //logger($price);
        //return view("welcome");
        $this->checkPriceADA($balances);
        //$this->checkPrices();
    }

    private function checkPriceADA($balances){
        $ADAmax = '?';
        $api = new Binance\API();
        $stopLineADA = 0.9627;
        $maxAmountADA = 10728;
        $bidADA = 100;
        $totalADA = $balances['ADA']['onOrder']+$balances['ADA']['available'];
        if($totalADA >= $maxAmountADA){
            $ADAmax = true; 
            echo $totalADA;
        }else{
            $ADAmax = false;
        }
        $priceADA = $api->price("ADAUSDT");
        
        $availableADA = $balances['ADA']['available'].PHP_EOL;
        //logger($price);
        //echo "Price of ADA: {$price} USDT.".PHP_EOL; // console echo
        //$stopLine = $price;


        if ($priceADA < $stopLineADA){
            echo "Price is {$priceADA}. It goes DOWN , SELL it IF YOU NEED!";
            
            if($availableADA >= $bidADA && $ADAmax === true){ //&& $ADAmax == true
                //place the order to sell (market price )
                echo "placing ord Mr S";
                $order = $api->marketSell("ADAUSDT", $bidADA);
                if($order){
                    echo "SOLD";
                    //var_dump($order);
                    $ADAsold = true;
                }else{
                    echo "NOT SOLD !!!!!!!!!!!!!!!!!!!!!!";
                }
            }

        } else if($priceADA > $stopLineADA){ //echo "dont do nothing !";
            echo "Price is {$priceADA}. It's goes UP  BUY it IF YOU NEED!";
            
            if($totalADA >= $bidADA && $ADAmax == false){ // $totalADA >= $bidADA && $ADAmax == true
                //place the order to sell (market price )     // IF USDT ENOUGH
                echo "placing ord Mr BUY";
                $order = $api->marketBuy("ADAUSDT", $bidADA);
                if($order){
                    $ADAmax = true;
                    $ADAsold = false;
                    //var_dump($order);
                    echo "BOUGHT !!!!!!!!!!!!!!!!!!!!!!";
                }else{
                    echo "NOT BOUGHT !!!!!!!!!!!!!!!!!!!!!!";
                }
                
            }

            //place order ByBack
        } else if ($priceADA == $stopLineADA){
            echo "dont do nothing !";
        }else{
            echo "ChTO PROISHODIT ? WOOBSHE ?";
        }
    }

    private function checkPrices(){

        $api = new Binance\API();
        $assetsUSDT = ['LTCUSDT','SANDUSDT', 'DOTUSDT', 'ADAUSDT'];
        $assets = ['LTC','SAND', 'DOT', 'ADA'];
        $ticker = $api->prices();
        $balances = $api->balances($ticker);
        

        foreach ($assetsUSDT as &$asset){
            $price = $api->price($asset);
            echo "Price of {$asset}: {$price} USDT.".PHP_EOL;
            logger($price);
            //echo "DOT TOTAL: ".$balances['DOT']['onOrder']+$balances['DOT']['available'];
            //logger($balances[$asset]['available']);
            //echo "{$assets} available: ".$balances[$assets]['available'].PHP_EOL;
        }

        
        foreach ($assets as &$asset){

            //echo "DOT TOTAL: ".$balances['DOT']['onOrder']+$balances['DOT']['available'];
            //logger($balances[$asset]['available']);
            echo "{$asset} available: ".$balances[$asset]['onOrder'].PHP_EOL;
        }
    }

    

    
}
