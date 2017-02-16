<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/19 0019
 * Time: 下午 5:15
 */
namespace Admin\Controller;
use Common\Controller\PublicController;
use Lib\Jpush\Jpush;
class JpushController extends PublicController
{
    public function index(){
        $this->display();
    }
    //全部推送
    public function push(){
        //组装需要的参数
        $receive = 'all';//全部
    //	$receive = array('tag'=>array('2401','2588','9527'));//标签
    //	$receive = array('alias'=>array('93d78b73611d886a74*****88497f501'));//别名
        $content = $_POST['content'];
        $m_type = $_POST['type'];//推送附加字段的类型(可不填) http,tips,chat....
        $m_txt = 'http://www.show999.com/'; //推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $m_time = '600';        //离线保留时间

        $jpush =  new Jpush();

        $jpush->send_pub($receive,$content,$m_type,$m_txt,$m_time);
    }

    //目标推送 --暂未做
    public function push_alone(){
        //组装需要的参数
        $receive = 'all';//全部
        //	$receive = array('tag'=>array('2401','2588','9527'));//标签
        //	$receive = array('alias'=>array('93d78b73611d886a74*****88497f501'));//别名
        $content = $_POST['content'];
        $m_type = 'public';//推送附加字段的类型(可不填) http,tips,chat....
        $m_txt = 'http://www.show999.com/'; //推送附加字段的类型对应的内容(可不填) 可能是url,可能是一段文字。
        $m_time = '600';        //离线保留时间

        $jpush =  new Jpush();

        $jpush->send_pub($receive,$content,$m_type,$m_txt,$m_time);
    }

}