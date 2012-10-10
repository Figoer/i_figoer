<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4 foldmethod=marker: */
/**
 * php cURL
 *
// {{{ 使用说明
 * ----curl_setopt()，设置选项，常用包括：
 * 	CURLOPT_URL：目标URL
 * 	CURLOPT_PORT：目标端口
 * 	CURLOPT_RETURNTRANSFER：把输出转化为字符串，而不是直接输出到屏幕
 * 	CURLOPT_HTTPHEADER：请求头信息，参数是一数组，如“基于浏览器的重定向”例子所示
 * 	CURLOPT_FOLLOWLOCATION: 跟随重定向
 * 	CURLOPT_FRESH_CONNECT：强制重新获取内容，而不是从缓存
 * 	CURLOPT_HEADER：包含头部
 * 	CURLOPT_NOBODY：输出中不包含网页主体内容
 * 	CURLOPT_POST：进行post表单提交
 * 	CURLOPT_POSTFIELDS：POST提交的字段，参数是一数组，如“ 用POST方法发送数据 ”所示
 * 	CURLOPT_PROXY：代理设置，IP 和 端口
 * 	CURLOPT_PROXYUSERPWD：代理设置，用户名和密码
 * 	CURLOPT_PROXYTYPE：代理类型，http 或 socket
 * 	更多选项：http://www.programfan.com/doc/php_manual/function.curl-setopt.html
 * ----curl_getinfo($ch)，获取信息，包括：
 *	“url” //资源网络地址
 *	“content_type” //内容编码
 *	“http_code” //HTTP状态码
 *	“header_size” //header的大小
 *	“request_size” //请求的大小
 *	“filetime” //文件创建时间
 *	“ssl_verify_result” //SSL验证结果
 *	“redirect_count” //跳转技术  
 *	“total_time” //总耗时
 *	“namelookup_time” //DNS查询耗时
 *	“connect_time” //等待连接耗时
 *	“pretransfer_time” //传输前准备耗时
 *	“size_upload” //上传数据的大小
 *	“size_download” //下载数据的大小
 *	“speed_download” //下载速度
 *	“speed_upload” //上传速度
 *	“download_content_length”//下载内容的长度
 *	“upload_content_length” //上传内容的长度  
 *	“starttransfer_time” //开始传输的时间
 *	“redirect_time”//重定向耗时
// }}}
 */
// {{{ 基于浏览器的重定向

// 测试用的URL 
$urls = array ( 
    "http://www.cnn.com" , 
    "http://www.mozilla.com" , 
    "http://www.facebook.com" 
) ; 
// 测试用的浏览器信息 
$browsers = array ( 
    "standard" => array ( 
        "user_agent" => "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)" ,
        "language" => "en-us,en;q=0.5" 
        ) , 
    "iphone" => array ( 
        "user_agent" => "Mozilla/5.0 (iPhone; U; CPU like Mac OS X; en) AppleWebKit/420+ (KHTML, like Gecko) Version/3.0 Mobile/1A537a Safari/419.3" ,
        "language" => "en" 
        ) , 
    "french" => array ( 
        "user_agent" => "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; GTB6; .NET CLR 2.0.50727)" ,
        "language" => "fr,fr-FR;q=0.5" 
        ) 
) ; 
foreach ( $urls as $url ) { 
    echo "URL: $url /n " ; 
    foreach ( $browsers as $test_name => $browser ) { 
        $ch = curl_init ( ) ; 
        // 设置 url 
        curl_setopt ( $ch , CURLOPT_URL, $url ) ; 
        // 设置浏览器的特定header 
        curl_setopt ( $ch , CURLOPT_HTTPHEADER, array ( 
                "User-Agent: {$browser['user_agent']} " , 
                "Accept-Language: {$browser['language']} " 
            ) ) ; 
        // 页面内容我们并不需要 
        curl_setopt ( $ch , CURLOPT_NOBODY, 1 ) ; 
        // 只需返回HTTP header 
        curl_setopt ( $ch , CURLOPT_HEADER, 1 ) ; 
        // 返回结果，而不是输出它 
        curl_setopt ( $ch , CURLOPT_RETURNTRANSFER, 1 ) ; 
        $output = curl_exec ( $ch ) ; 
        curl_close ( $ch ) ; 
        // 有重定向的HTTP头信息吗? 
        if ( preg_match ( "!Location: (.*)!" , $output , $matches ) ) { 
            echo "$test_name : redirects to $matches[1] /n " ; 
        } else { 
            echo "$test_name : no redirection/n " ; 
        } 
    } 
    echo "/n /n " ; 
}

// }}}
// {{{ 用GET方法发送数据

$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
curl_setopt($ch, CURLOPT_URL,"https://api.weibo.com/2/statuses/public_timeline.json?access_token=2.00KonpnB4UIk9Ef8084faeablqPSlB");//GET的URL
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
$result = curl_exec ($ch);
curl_close ($ch);
var_dump($result);

// }}}
// {{{ 用POST方法发送数据

$url = "http://localhost/post_output.php" ; 
$post_data = array ( 
    "foo" => "bar" , 
    "query" => "Nettuts" , 
    "action" => "Submit" 
) ; 
$ch = curl_init ( ) ; 
curl_setopt ( $ch , CURLOPT_URL, $url ) ; 
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER, 1 ) ; 
// 我们在POST数据哦！ 
curl_setopt ( $ch , CURLOPT_POST, 1 ) ; 
// 把post的变量加上 
curl_setopt ( $ch , CURLOPT_POSTFIELDS, $post_data ) ; 
$output = curl_exec ( $ch ) ; 
curl_close ( $ch ) ; 
echo $output ;  

// }}}
// {{{ 文件上传

$url = "http://localhost/upload_output.php" ; 
$post_data = array ( 
    "foo" => "bar" , 
    // 要上传的本地文件地址 
    "upload" => "@C:/wamp/www/test.zip" 
) ; 
$ch = curl_init ( ) ; 
curl_setopt ( $ch , CURLOPT_URL, $url ) ; 
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER, 1 ) ; 
curl_setopt ( $ch , CURLOPT_POST, 1 ) ; 
curl_setopt ( $ch , CURLOPT_POSTFIELDS, $post_data ) ; 
$output = curl_exec ( $ch ) ; 
curl_close ( $ch ) ; 
echo $output ;

// }}}
// {{{ 翻墙

$ch = curl_init ( ) ; 
curl_setopt ( $ch , CURLOPT_URL, 'http://www.example.com' ) ; 
curl_setopt ( $ch , CURLOPT_RETURNTRANSFER, 1 ) ; 
// 指定代理地址 
curl_setopt ( $ch , CURLOPT_PROXY, '11.11.11.11:8080' ) ; 
// 如果需要的话，提供用户名和密码 
curl_setopt ( $ch , CURLOPT_PROXYUSERPWD, 'user:pass' ) ; 
$output = curl_exec ( $ch ) ; 
curl_close ( $ch ) ;

// }}}


