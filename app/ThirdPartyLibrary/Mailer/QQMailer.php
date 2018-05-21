<?php
/**
 * Created by PhpStorm.
 * User: KelipuTe
 * Date: 2017/11/24
 * Time: 16:11
 */

namespace App\ThirdPartyLibrary\Mailer;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * QQ 邮件发送处理类
 * Class QQMailer
 * @package App\ThirdPartyLibrary\Mailer
 */
class QQMailer
{
    /**
     * QQ 邮箱的服务器地址
     * @var string
     */
    public static $HOST = 'smtp.qq.com';

    /**
     * smtp 服务器的远程服务器端口号
     * @var int
     */
    public static $PORT = 465;

    /**
     * 使用 ssl 加密方式登录
     * @var string
     */
    public static $SMTP = 'ssl';

    /**
     * 设置发送的邮件的编码
     * @var string
     */
    public static $CHARSET = 'UTF-8';

    /**
     * 授权登录的账号
     * @var string
     */
    private static $USERNAME = '786907650@qq.com';

    /**
     * 授权登录的密码
     * @var string
     */
    private static $PASSWORD = 'ywbitbdktmmvbfbj';

    /**
     * 发件人的昵称
     * @var string
     */
    private static $NICKNAME = '冷月重工';

    /**
     * QQMailer constructor.
     * @param bool $debug [调试模式]
     */
    public function __construct($debug = false)
    {
        $this->mailer = new PHPMailer();
        $this->mailer->SMTPDebug = $debug ? 1 : 0; // 是否开启 debug 模式
        $this->mailer->isSMTP();
    }

    /**
     * @return PHPMailer
     */
    public function getMailer()
    {
        return $this->mailer;
    }

    private function loadConfig()
    {
        /* Server Settings  */
        $this->mailer->SMTPAuth = true; // 开启 SMTP 认证
        $this->mailer->Host = self::$HOST; // SMTP 服务器地址
        $this->mailer->Port = self::$PORT; // 远程服务器端口号
        $this->mailer->SMTPSecure = self::$SMTP; // 登录认证方式
        /* Account Settings */
        $this->mailer->Username = self::$USERNAME; // SMTP 登录账号
        $this->mailer->Password = self::$PASSWORD; // SMTP 登录密码
        $this->mailer->From = self::$USERNAME; // 发件人邮箱地址
        $this->mailer->FromName = self::$NICKNAME; // 发件人昵称
        /* Content Setting  */
        $this->mailer->isHTML(true); // 邮件正文是否为 HTML
        $this->mailer->CharSet = self::$CHARSET; // 发送的邮件的编码
    }

    /**
     * Add attachment
     * @param $path [附件路径]
     */
    public function addFile($path)
    {
        $this->mailer->addAttachment($path);
    }

    /**
     * Send Email
     * @param $email [收件人]
     * @param $title [主题]
     * @param $content [正文]
     * @return bool [发送状态]
     */
    public function send($email, $title, $content)
    {
        $this->loadConfig();
        $this->mailer->addAddress($email); // 收件人邮箱
        $this->mailer->Subject = $title; // 邮件主题
        $this->mailer->Body = $content; // 邮件信息
        return (bool)$this->mailer->send(); // 发送邮件
    }

    /*public function testSendEmail(){
        $mail = new PHPMailer(); // 实例化PHPMailer核心类
        $mail->SMTPDebug = 1; // 是否启用 smtp 的 debug 进行调试，开发环境建议开启，生产环境注释掉即可，默认关闭 debug 调试模式
        $mail->isSMTP(); // 使用 smtp 鉴权方式发送邮件
        $mail->SMTPAuth = true; // smtp 需要鉴权，这个必须是 true
        $mail->Host = 'smtp.qq.com'; // 链接 qq 域名邮箱的服务器地址
        $mail->SMTPSecure = 'ssl'; // 设置使用 ssl 加密方式登录鉴权
        $mail->Port = 465; // 设置 ssl 连接 smtp 服务器的远程服务器端口号
        $mail->CharSet = 'UTF-8'; // 设置发送的邮件的编码
        $mail->FromName = '冷月重工'; // 设置发件人昵称，显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->Username = '786907650@qq.com'; // smtp 登录的账号，QQ 邮箱即可
        $mail->Password = 'slbrdgwiekijbgae'; // smtp 登录的密码，使用生成的授权码
        $mail->From = '786907650@qq.com'; // 设置发件人邮箱地址，同登录账号
        $mail->isHTML(true); // 邮件正文是否为 html 编码，注意此处是一个方法
        $mail->addAddress('xhy_1365@sina.com'); // 设置收件人邮箱地址
        //$mail->addAddress('xhy_1365@163.com'); // 添加多个收件人，则多次调用方法即可
        $mail->Subject = 'HelloWorld'; // 添加该邮件的主题
        $mail->Body = '<h1>Hello World</h1>'; // 添加邮件正文
        $mail->addAttachment("image/avatar/ougen.jpg"); // 为该邮件添加附件
        $status = $mail->send(); // 发送邮件，返回状态
        return $status;
    }*/
}