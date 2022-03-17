<?php
/**
 * Plugin Name:  用友短信
 * Plugin URI:   https://www.gechiui.com/plugin/yonyoucloud-sms
 * Description:  专业验证码短信通道，99.99%到达率，5秒必达。
 * Version:      1.1.0
 * Author: 用友APILink
 * Author URI: https://api.yonyoucloud.com/

 * Text Domain:  yonyoucloud-sms
 *
 */

//header('Content-type:text/json;charset=utf-8');
//设置时区,读取系统配置
//date_default_timezone_set(get_option('timezone_string'));

define( 'YONYOUCLOUDSMS__FILE__', __FILE__ );
define( 'YONYOUCLOUD_SMS_PATH', plugin_dir_path( YONYOUCLOUDSMS__FILE__ ) );

class YONYOUCLOUD_SMS {
    
    private $_Options;
    
    public function __construct() {
        //注册菜单
        add_action( 'admin_menu', array( $this, 'add_menu' ) );
        //给GeChiUI的短信钩子，注册短信发送发送
        add_filter( 'gc_sms', array($this,'to_sms') );
        //数据初始化
        $this->_Options = get_option( 'yonyoucloud_sms_options' );
        
        //修改默认注册方式
//        if( $this->_Options['registration_sms ']) {
            add_filter( 'register_url', array( $this,  'register_url') );
//        }
        
        //添加异步发送短信功能
        add_action( 'gc_ajax_smscode',array( $this,  'ajax_smscode') );
        add_action( 'gc_ajax_nopriv_smscode',array( $this,  'ajax_smscode') );
    }
    
    function register_url( $url ){
        return site_url( 'gc-login.php?action=register-sms', 'login' );
    }
    
    /**短信验证码,注册给gc_ajax
    * 作者：宫叔
    * $mobile ： 手机号
    * $smscode ： 验证码 4-8位，默认随机6位数字
    * $expiry 过期时间，格式 2021-01-01 18:18:18 ,默认是当前时间+15分钟
    */
    function ajax_smscode(){
        check_ajax_referer( 'smscode' );
        $mobile = $_REQUEST['mobile'];
        if(!isset($mobile)){
            exit('{"code":-1,"msg":"请填写手机号"}');
        }
        $error =validate_usermobile( $mobile );
        if (  is_gc_error( $error ) ) {
            exit('{"code":-1,"msg":"请准确填写手机号"}');
        }
        $session = GC_User::get_session($mobile);
        if ( $session  &&  60 >(strtotime(current_time( 'mysql' )) -  strtotime($session->session_date)) ) {
            exit('{"code":-1,"msg":"操作频次过快，请稍后尝试"}');
        }

         //过期时间
        if( empty( $expiry) ) {
            $expiry = date('Y-m-d H:i:s', strtotime("+15 minutes"));
        }
        
        //调用第三方组件发短信
        $pre_gc_sms = apply_filters( 'gc_sms', $mobile );
        
        if ( ! isset( $pre_gc_sms ) ) {
            exit('{"code":-1,"msg":"短信插件无法正常工作"}');
        }
        if ( $pre_gc_sms['code'] ) {
            GC_User::set_session($mobile, $pre_gc_sms['msg'], $expiry);
        }
        exit( json_encode( $pre_gc_sms ) );
    }
    
    /*
    *
    */
    public function add_menu() {
        add_submenu_page( 'options-general.php',  '用友短信',  '用友短信', 'manage_options','gc-smtp', array( $this, 'sms_setting_menu' ) );
	}
    
    /*
    *
    */
    function sms_setting_menu() {
		require_once YONYOUCLOUD_SMS_PATH . '/yonyoucloud_sms_admin.php';
	}

    /*
    * 发送短信前的短信数据构造
    * $mobile：接收短信的手机号码
    */
    function to_sms($mobile){
        if(!isset($this->_Options) || empty($this->_Options)) {
            return array("code" => 1, "msg" => "请设置验证码短信的基础信息");
        }
        
        //判断调试模式
        if( $this->_Options['debug'] && ! empty($this->_Options['debug']) ) {
            return array("code" => 1, "msg" => $this->_Options['debug']);
        }
        
        $apicode = $this->_Options['apicode'];
        $signature = $this->_Options['signature'];
        $url = "https://api.yonyoucloud.com/apis/dst/mobilemessage/sendmessage";

        $method = "POST";
        //获取6位验证码
        $smscode = $this->code();
        $params = array(
                "msg" => '【'.$signature.'】验证码 '. $smscode .'，切勿将验证码泄露于他人，本条验证码有效期15分钟',
                "uid" => "",
                "phone" => $mobile,
        );

        $header = array();
        $header[] = "apicode:".$apicode;
        $header[] = "content-type:application/json";
        $header[] = "Content-Type:application/json";


        $content = $this->linkcurl($url,$method,$params,$header);
        $result = json_decode($content,true);
        if($result){
            if($result['success']){
                //成功，验证码回给前端
                return array("code" => 1, "msg" => $smscode);
            }else{
                //返回失败信息
                return  $result;
            }
        }else{
            return array("code" => -1, "msg" => "请求失败");
        }
    }

    /**
     * 请求接口返回内容
     * @param  string $url [请求的URL地址]
     * @param  string $params [请求的参数]
     * @param  int $ipost [是否采用POST形式]
     * @return  string
     */
    function linkcurl($url,$method,$params=false,$header=false){
        $httpInfo = array();
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        if (1 == strpos("$".$url, "https://"))
        {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 60 );
        curl_setopt( $ch, CURLOPT_TIMEOUT , 60);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

        if($method == "POST" ){
            curl_setopt( $ch , CURLOPT_POST , true );
            curl_setopt( $ch , CURLOPT_POSTFIELDS, json_encode($params) );
        }else if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.http_build_query($params) );
        }
        $response = curl_exec( $ch );
        if ($response === FALSE) {
            //echo "cURL Error: " . curl_error($ch);
            return false;
        }
        $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
        $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
        curl_close( $ch );
        return $response;
    }

    /*
    * 生成6位随机数字串，用于验证码使用
    */
    function code(){
        $key = '';
        $pattern='1234567890'; 
        for( $i=0; $i<6; $i++ ) {
          $key .= $pattern[mt_rand(0, 9)];
        }
        return $key;
    }
}

new YONYOUCLOUD_SMS();
?>