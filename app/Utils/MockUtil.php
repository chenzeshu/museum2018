<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/17 0017
 * Time: 18:48
 */

namespace App\Utils;


use App\Model\Common\Record;

class MockUtil
{
    /**
     * @param {string} $year 触发时的年份
     */
    static public function mockGetPerfCode($year){
        $record = Record::findOrFail(1);
        $number = ++$record->record_number;       //填充时不需要++
        $number = self::padZero($number);
        $record->increment('record_number');
        return intval($year . $number);
    }

    /**
     * 补0函数
     */
    static private function padZero($number){
        $adder = "";
        switch (strlen($number)){
            case 1:
                $adder = "00";
                break;
            case 2:
                $adder = "0";
                break;
            default:
                break;
        }
        return $adder.$number;
    }

    static public function generate_password( $length = 8 ) {
        // 密码字符集，可任意添加你需要的字符
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()-_ []{}<>~`+=,.;:/?|";
        $password = "";
        for ( $i = 0; $i < $length; $i++ )
        {
        // 这里提供两种字符获取方式
        // 第一种是使用 substr 截取$chars中的任意一位字符；
        // 第二种是取字符数组 $chars 的任意元素
        // $password .= substr($chars, mt_rand(0, strlen($chars) – 1), 1);
            $password .= $chars[ mt_rand(0, strlen($chars) - 1) ];
        }
        return $password;
    }
}