<?php
/**
 * Created by PhpStorm.
 * User: KelipuTe
 * Date: 2017/11/24
 * Time: 16:11
 */

namespace App\ThirdPartyLibrary\MyClass;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * QQ 邮件发送处理类
 * Class QQMailer
 * @package App\ThirdPartyLibrary\MyClass
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
    private static $PASSWORD = 'slbrdgwiekijbgae';

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
        $this->mailer->SMTPDebug = $debug ? 1 : 0;
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
        // 实例化PHPMailer核心类
        $mail = new PHPMailer();
        // 是否启用smtp的debug进行调试 开发环境建议开启 生产环境注释掉即可 默认关闭debug调试模式
        //$mail->SMTPDebug = 1;
        // 使用smtp鉴权方式发送邮件
        $mail->isSMTP();
        // smtp需要鉴权 这个必须是true
        $mail->SMTPAuth = true;
        // 链接qq域名邮箱的服务器地址
        $mail->Host = 'smtp.qq.com';
        // 设置使用ssl加密方式登录鉴权
        $mail->SMTPSecure = 'ssl';
        // 设置ssl连接smtp服务器的远程服务器端口号
        $mail->Port = 465;
        // 设置发送的邮件的编码
        $mail->CharSet = 'UTF-8';
        // 设置发件人昵称 显示在收件人邮件的发件人邮箱地址前的发件人姓名
        $mail->FromName = '冷月重工';
        // smtp登录的账号 QQ邮箱即可
        $mail->Username = '786907650@qq.com';
        // smtp登录的密码 使用生成的授权码
        $mail->Password = 'slbrdgwiekijbgae';
        // 设置发件人邮箱地址 同登录账号
        $mail->From = '786907650@qq.com';
        // 邮件正文是否为html编码 注意此处是一个方法
        $mail->isHTML(true);
        // 设置收件人邮箱地址
        $mail->addAddress('xhy_1365@sina.com');
        // 添加多个收件人 则多次调用方法即可
        //$mail->addAddress('xhy_1365@163.com');
        // 添加该邮件的主题
        $mail->Subject = 'HelloWorld';
        // 添加邮件正文
        $mail->Body = '<h1>Hello World</h1>';
        // 为该邮件添加附件
        $mail->addAttachment("image/avatar/ougen.jpg");
        // 发送邮件 返回状态
        $status = $mail->send();
        return $status;
    }*/
}