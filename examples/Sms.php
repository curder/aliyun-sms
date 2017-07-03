<?php
use Curder\AliyunCore\Profile\DefaultProfile;
use Curder\AliyunCore\DefaultAcsClient;
use Curder\AliyunCore\Regions\Endpoint;
use Curder\AliyunCore\Regions\EndpointConfig;
use Curder\AliyunCore\Regions\EndpointProvider;
use Curder\AliyunSms\Sms\Request\V20170525\SendSmsRequest;
use Curder\AliyunCore\Exception\ClientException;
use Curder\AliyunCore\Exception\ServerException;
class SmsController
{
    public function send()
    {
        define('ENABLE_HTTP_PROXY', env('ALIYUN_SMS_ENABLE_HTTP_PROXY', false));
        define('HTTP_PROXY_IP',     env('ALIYUN_SMS_HTTP_PROXY_IP', '127.0.0.1'));
        define('HTTP_PROXY_PORT',   env('ALIYUN_SMS_HTTP_PROXY_PORT', '8888'));
        $endpoint = new Endpoint("cn-hangzhou", EndpointConfig::getregionIds(), EndpointConfig::getProducDomains());
        $endpoints = array($endpoint);
        EndpointProvider::setEndpoints($endpoints);
        $iClientProfile = DefaultProfile::getProfile("cn-hangzhou", "your accessKey", "your accessSecret");
        $client = new DefaultAcsClient($iClientProfile);
        $request = new SendSmsRequest();
        $request->setSignName("验证测试");                 /*签名名称*/
        $request->setTemplateCode("SMS_11111");           /*模板code*/
        $request->setRecNum("手机号");                     /*目标手机号*/
        $request->setParamString("{\"code\":\"12345\",\"minutes\":\"5\"}");/*模板变量，数字一定要转换为字符串*/
         //选填-发送短信流水号
        $request->setOutId("1234");
        try {
            $response = $client->getAcsResponse($request);
            print_r($response);
        } catch (ClientException  $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
        } catch (ServerException  $e) {
            print_r($e->getErrorCode());
            print_r($e->getErrorMessage());
        }
    }
}