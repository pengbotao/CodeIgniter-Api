# 一、系统简介

本项目基于CodeIgniter框架，主要为提供API接口快速初始化，只需关注实现业务逻辑即可。着重实现以下几点：

- 接口协议封装，支持两种接口协议，支持GET和POST请求
- 模型层方便读取多个数据库
- 支持记录SQL日志和API日志，方便进行接口调试
- 代码流程清晰。增加服务层、API调用层，并可根据需要进行扩展
- 其他一些常用方法封装。

**说明：** 本项目不是直接生成代码，而是提供一套API接口的模版，用项目中的代码即可快速实现API项目初始化。可配合[CodeIgniter-CodeHelper](https://github.com/pengbotao/CodeIgniter-CodeHelper)生成模型。


# 二、安装说明

## 2.1 配置站点

拷贝代码到web目录下，并配置Nginx站点，如：

```
server
{
    listen       80;
    server_name api.local;
    index index.html index.php;
    root  /Users/peng/workspace/CodeIgniter-Api/public;
    location /
    {
        if (!-e $request_filename)
        {
            rewrite . /index.php last;
        }
    }
    location ~ .*\.(php|php5)?$
    {
        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi.conf;
    }
}
```

目录`application/cache`、`application/logs`给写权限。

## 2.2 配置数据库

`doc/demo.sql`为示例SQL，初始化之后调整`config/database.php`中的配置，确保数据库配置正确。

## 2.3 访问接口

`demo/demo.index.php`为示例接口，切换到该目录下。执行：

```
pengbotao:demo peng$ php demo.index.php 
http://api.local/demo
{"id":1555209269,"client":{"caller":"test","ext":""},"encrypt":"md5","sign":"37cca052f85eac8dacce69924bdd06fa","data":{"user_id":1,"t":1555209269}}

HTTP/1.1 200 OK
Server: nginx/1.10.3
Date: Sun, 14 Apr 2019 02:34:34 GMT
Content-Type: application/json;charset=utf-8
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.1.2

{"id":"20190414103434650294","status":{"code":0,"msg":"\u64cd\u4f5c\u6210\u529f"},"data":{"user":{"user_id":"1","username":"demo","status":"1","created_ts":"2019-04-14 10:13:15"},"order":[{"order_id":"1","user_id":"1","order_title":"\u8ba2\u5355\u6807\u9898","created_ts":"2019-04-14 10:14:30"}]}}
```

`demo`下实现了GET和POST两种方式访问，`http_request("demo", $data);`将`http_request`改为`http_get`即为GET请求。默认为POST请求。

```
pengbotao:demo peng$ php demo.index.php 
http://api.local/demo?_id=1555209344&_caller=test&_encrypt=simple&_sign=c18df4f6ee5fe0c5c376a6e24918b61f&user_id=1&t=1555209344

HTTP/1.1 200 OK
Server: nginx/1.10.3
Date: Sun, 14 Apr 2019 02:35:49 GMT
Content-Type: application/json;charset=utf-8
Transfer-Encoding: chunked
Connection: keep-alive
X-Powered-By: PHP/7.1.2

{"id":"20190414103549563372","status":{"code":0,"msg":"\u64cd\u4f5c\u6210\u529f"},"data":{"user":{"user_id":"1","username":"demo","status":"1","created_ts":"2019-04-14 10:13:15"},"order":[{"order_id":"1","user_id":"1","order_title":"\u8ba2\u5355\u6807\u9898","created_ts":"2019-04-14 10:14:30"}]}}
```

## 三、配置说明

- `application/config`下的`deveopment`、`production`、`testing`目录（目录不存在可自行创建）对应`index.php`中的不同环境，可将`database.php`等需要修改的配置文件移到对应目录下，当框架环境对应的目录里存在配置文件时会优先读取。
- `application/constants.php`中可以配置打开或关闭日志、多数据库对应常量。日志存放在`apaplication/logs`目录下。
- `application/caller.php`为请求相关配置信息。
- `application/models`可由`CodeIgniter-CodeHelper`中直接生成后使用。
- 签名流程在`application/service/api_sign_service.php`中，可根据需要调整。
- 接口文档示例和签名文档在`doc`目录
