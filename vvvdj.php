<?php
//上传到根目录,服务器组设置为 /vvvvdj.php?id= 歌曲地址写VVVDJ 播放ID 服务器API地址如失效联系 QQ173753438.
    if (!$_GET["id"]) {
exit('参数空,如：vvvdj.php?id=185371');
    }
function urlsafe_b64encode($string)
{
    $data = base64_encode($string);
    $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
    return $data;
}
function getIp()
{
    if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) {
        $ip = getenv("HTTP_CLIENT_IP");
    } else {
        if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) {
            $ip = getenv("HTTP_X_FORWARDED_FOR");
        } else {
            if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) {
                $ip = getenv("REMOTE_ADDR");
            } else {
                if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) {
                    $ip = $_SERVER['REMOTE_ADDR'];
                } else {
                    $ip = "unknown";
                }
            }
        }
    }
    return $ip;
}
function Liujie_curl($url, $post = "")
{
    $ip = getIp();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);//超时时间30秒，自己设置
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_USERAGENT, "Dalvik/1.6.0 (Linux; U; Android 4.1.2; DROID RAZR HD Build/9.8.1Q-62_VQW_MR-2)");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-FORWARDED-FOR:' . $ip, 'CLIENT-IP:' . $ip));//构造IP
    if ($post) {
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    }
    $c = curl_exec($ch);
    curl_close($ch);
    return $c;
}
$vvvdj = Liujie_curl('https://m.vvvdj.com/play/'.$_GET["id"].'.html');
preg_match_all('/function DeCode()(.*?)\'\\);/is', $vvvdj, $LiuJie_r);
$djurlq = urlsafe_b64encode($LiuJie_r[0][0]);
$vvvdjurl = Liujie_curl('http://djapi.idophoto.com.cn/jiexi/vvvdj/index.php', array("code" => $djurlq));
Header("Location: {$vvvdjurl}");