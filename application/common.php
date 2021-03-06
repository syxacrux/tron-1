<?php
/**
 * 行为绑定
 */
use redis\RedisPackage;
\think\Hook::add('app_init','app\\common\\behavior\\InitConfigBehavior');

/**
 * 返回对象
 * @param $array 响应数据
 */
function resultArray($array){
    if(isset($array['data'])) {
        $array['error'] = '';
        $code = 200;
    } elseif (isset($array['error'])) {
        $code = 400;
        $array['data'] = '';
    }
    return [
        'code'  => $code,
        'data'  => $array['data'],
        'error' => $array['error']
    ];
}

/**
 * 调试方法
 * @param  array   $data  [description]
 */
function p($data,$die=1)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    if ($die) die;
}

/**
 * 用户密码加密方法
 * @param  string $str      加密的字符串
 * @param  [type] $auth_key 加密符
 * @return string           加密后长度为32的字符串
 */
function user_md5($str, $auth_key = '')
{
    return '' === $str ? '' : md5(sha1($str) . $auth_key);
}


/**
 * 对象转换成数组
 * @param $obj
 * @return mixed
 * @author zjs 2018/3/6
 */
function objToArray($obj){
    return json_decode(json_encode($obj), true);
}

/**
 * 通过redis执行外部python脚本
 * @param $str
 * @author zjs 2018/3/26
 */
function exec_python($str){
    $redis = new RedisPackage();
    $cmd = "python /var/www/html/tronPipelineScript/createDirPath/parser.py $str ";
    $redis::LPush("pyFile",$cmd);
}



