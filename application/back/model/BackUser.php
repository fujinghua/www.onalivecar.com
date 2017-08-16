<?php

namespace app\back\model;

use app\common\model\BackUser as BaseBackUser;
use app\back\validate\BackUserValidate;

use app\common\model\AuthAssignment;
use app\common\model\AuthItem;
use app\back\model\Ban;
use app\back\model\Department;
use app\back\model\BackUserLog;
use app\back\model\Contact;
use app\back\model\ContactRead;
use app\back\model\Service;
use app\back\model\DeleteLog;
use app\back\model\Download;
use app\back\model\ClientServer;
use app\back\model\Hot;
use app\back\model\HouseHostServer;
use app\back\model\Notice;
use app\back\model\NoticeRead;
use app\back\model\Opinion;
use app\back\model\OpinionRead;
use app\back\model\Slider;
use app\back\model\Deal;
use app\back\model\Upload;

use app\back\model\Identity;


/**
 * This is the model class for table "{{%back_user}}".
 *
 * @property integer $id
 * @property integer $is_delete
 * @property string $code
 * @property integer $department_id
 * @property string $phone
 * @property integer $phone_verified
 * @property string $email
 * @property integer $email_verified
 * @property string $username
 * @property string $password
 * @property string $nickname
 * @property string $service_name
 * @property string $real_name
 * @property string $head_url
 * @property string $sex
 * @property string $signature
 * @property string $birthday
 * @property integer $height
 * @property integer $weight
 * @property string $token
 * @property string $auth_key
 * @property string $password_reset_token
 * @property string $password_reset_code
 * @property integer $status
 * @property string $ip
 * @property string $reg_ip
 * @property string $reg_type
 * @property string $registered_at
 * @property string $logined_at
 * @property string $updated_at
 *
 * @property AuthAssignment[] $AuthAssignments
 * @property AuthItem[] $itemNames
 * @property Ban[] $Bans
 * @property AuthItem[] $itemNames0
 * @property Department $department
 * @property BackUserLog[] $backUserLogs
 * @property Contact[] $contacts
 * @property ContactRead[] $contactReads
 * @property Service[] $customerServices
 * @property DeleteLog[] $deleteLogs
 * @property Download[] $downloads
 * @property ClientServer[] $clientServers
 * @property Hot[] $hots
 * @property HouseHostServer[] $houseHostServers
 * @property Notice[] $notices
 * @property NoticeRead[] $noticeReads
 * @property Opinion[] $opinions
 * @property OpinionRead[] $opinionReads
 * @property Slider[] $sliders
 * @property Deal[] $takeOrders
 * @property Upload[] $uploads
 */
class BackUser extends BaseBackUser
{

    /**
     * @return Object|\think\Validate
     */
    public static function getValidate(){
        return BackUserValidate::load();
    }

    /**
     * @param $data
     * @param string $scene
     * @return bool
     */
    public static function check($data,$scene = ''){
        $validate = self::getValidate();

        //设定场景
        if (is_string($scene) && $scene !== ''){
            $validate->scene($scene);
        }

        return $validate->check($data);
    }
}
