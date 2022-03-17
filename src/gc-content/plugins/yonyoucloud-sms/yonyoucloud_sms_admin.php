<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

// Catch the SMTP settings
if (isset($_POST['yonyoucloud_sms_update']) && isset($_POST['yonyoucloud_sms_nonce_update'])) {
    if (!gc_verify_nonce(trim($_POST['yonyoucloud_sms_nonce_update']), 'yonyoucloud_sms_nonce')) {
        gc_die('安全检查未通过!');
    }
    $this->_Options = array();
    $this->_Options["apicode"] = sanitize_text_field( trim( $_POST['yonyoucloud_sms_apicode'] ) );
    $this->_Options["signature"] = sanitize_text_field( trim( $_POST['yonyoucloud_sms_signature'] ) );
    $this->_Options["registration_sms"] = $_POST['registration_sms'];
    $this->_Options["debug"] = sanitize_text_field( trim( $_POST['yonyoucloud_sms_debug'] ) );

    update_option("yonyoucloud_sms_options", $this->_Options); 

    if ( empty($this->_Options["apicode"] ) ) {
        echo '<div id="message" class="updated"><p><strong>' . __("请输入用友短信的apicode!", "YONYOUCLOUD-SMS") . '</strong></p></div>';
    } elseif (empty($this->_Options["signature"])) {
        echo '<div id="message" class="updated"><p><strong>' . __("请输入短信签名!", "YONYOUCLOUD-SMS") . '</strong></p></div>';
    } else {
        echo '<div id="message" class="updated"><p><strong>' . __("设置更改成功。", "YONYOUCLOUD-SMS") . '</strong></p></div>';
    }
}

// 测试设置
if ( isset( $_POST['yonyoucloud_sms_test'] ) && isset( $_POST['yonyoucloud_sms_nonce_test'] ) ) {

    if ( ! gc_verify_nonce( trim( $_POST['yonyoucloud_sms_nonce_test'] ), 'yonyoucloud_sms_nonce' ) ) {
        gc_die('安全检查未通过!');
    }

    $mobile = sanitize_text_field( trim( $_POST['yonyoucloud_sms_mobile'] ) );

    $status = false;
    $class = 'error';


    //正则判断手机号格式
    if ( ! empty( $mobile ) && preg_match("/^1[345678]{1}\d{9}$/",$mobile)  ) {
        try {
            $result = apply_filters( 'gc_sms', $mobile );
        } catch (Exception $e) {
            $status = $e->getMessage();
        }
    } else {
        $status = __( '请输入有效的手机号', 'yonyoucloud-sms' );
    }

    if ( ! $status ) {
        if ( $result['code'] ) {
            $status = __( '短信已发送!', 'yonyoucloud-sms' );
            $class = 'success';
        } else {
            $status = $result['msg'];
        }
    }

    echo '<div id="message" class="notice notice-' . $class . ' is-dismissible"><p><strong>' . $status . '</strong></p></div>';
}

$ws_nonce = gc_create_nonce('yonyoucloud_sms_nonce');
?>
    <div class="wrap">
        <h1 class="font-weight-normal">用友-验证码短信</h1>
        <hr class="gc-header-end">
        <form action="" method="post" enctype="multipart/form-data" name="yonyoucloud_sms_form">
        <div class="card m-t-20">
            <div class="card-header">
                <h4 class="card-title">设置</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div  class="row">
                                    <p class="col-sm-3 text-dark">apicode: </p>
                                    <p class="col-sm-9">
                                        <input type="text" name="yonyoucloud_sms_apicode" value="<?php echo $this->_Options["apicode"]; ?>" size="43" class="form-control"  required/>
                                    </p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div  class="row">
                                    <p class="col-sm-3 text-dark">短信签名: </p>
                                    <p class="col-sm-9">  
                                        <input type="text" name="yonyoucloud_sms_signature" value="<?php echo $this->_Options["signature"]; ?>" size="43" class="form-control"  required />
                                    </p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div  class="row">
                                    <p class="col-sm-3 text-dark">调整默认注册方式: </p>
                                    <div class="col-sm-9">  
                                        <div class="checkbox">
                                            <input id="registration_sms" name="registration_sms" type="checkbox" <?php echo $this->_Options["registration_sms"] ? 'checked=""': ''; ?>  value="1">
                                            <label for="registration_sms">手机注册</label>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div  class="row">
                                    <p class="col-sm-3 text-dark">调试状态验证码: </p>
                                    <p class="col-sm-9">  
                                        <input type="text" name="yonyoucloud_sms_debug" value="<?php echo $this->_Options["debug"]; ?>" size="43" class="form-control"  />
                                        <span>输入6位数字，有值状态下不会触发短信，而是直接使用<b>输入的值作为验证码</b>进行调试，正式环境请清空！</span>
                                    </p>
                                </div>
                            </li>
                            <li class="modal-footer">
                                <input type="hidden" name="yonyoucloud_sms_update" value="update"/>
                                <input type="hidden" name="yonyoucloud_sms_nonce_update" value="<?php echo $ws_nonce; ?>"/>
                                <input type="submit" class="btn btn-primary" name="Submit" value="保存更改"/>
                            </li>
                        </ul> 
                    </div>  
                </div>
            </div>
        </div>
        </form>
        <form action="" method="post" enctype="multipart/form-data" name="yonyoucloud_sms_testform">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">测试你的设置</h4>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div  class="row">
                                    <p class="col-sm-3 text-dark">手机号: </p>
                                    <p class="col-sm-9">
                                        <input type="text" name="yonyoucloud_sms_mobile" value="" size="43" class="form-control"  required />
                                    </p>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div  class="row">
                                    <p class="col-sm-3 text-dark">邮件正文: </p>
                                    <p class="col-sm-9">
                                        <textarea type="text" name="yonyoucloud_sms_message" value="" cols="45" rows="3" class="form-control" readonly>【<?php echo $this->_Options["signature"]; ?>】验证码 ******，切勿将验证码泄露于他人，本条验证码有效期15分钟</textarea>
                                    </p>
                                </div>
                            </li>
                            <li class="modal-footer">
                                <input type="hidden" name="yonyoucloud_sms_test" value="test"/>
                                <input type="hidden" name="yonyoucloud_sms_nonce_test" value="<?php echo $ws_nonce; ?>"/>
                                <input type="submit" class="btn btn-primary" value="发送测试"/>
                            </li>
                         </ul> 
                    </div>  
                </div>
            </div>
        </div>
        </form>
    </div>
    