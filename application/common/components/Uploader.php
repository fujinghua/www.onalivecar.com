<?php

namespace app\common\components;

class Uploader
{
    private static $webPath = ROOT_PATH;              //网站根目录
    private static $fileField;            //文件域名
    private static $allFile;              //全部文件上传对象
    private static $itemFile;             //当前处理的单个文件上传对象
    private static $oriName;              //原始文件名
    private static $fileName;             //新文件名
    private static $fullName;             //完整文件名,即从当前配置目录开始的URL
    private static $iconFileName;         //缩略图名
    private static $iconFullName;         //缩略图完整文件名,即从当前配置目录开始的URL
    private static $fileSize;             //文件大小
    private static $fileType;             //文件类型
    private static $stateInfo;            //上传状态信息,
    private static $stateMap = [          //上传状态映射表，国际化用户需考虑此处数据的国际化
        "SUCCESS",                        //上传成功标记
        "文件大小超出 upload_max_filesize 限制",
        "文件大小超出 MAX_FILE_SIZE 限制",
        "文件未被完整上传",
        "没有文件被上传",
        "上传文件为空",
        "NOT_FILE" => "上传文件为空",
        "POST" => "文件大小超出服务器大小限制",
        "SIZE" => "文件大小超出网站大小限制",
        "TYPE" => "不允许的文件类型",
        "DIR" => "目录创建失败",
        "IO" => "输入输出错误",
        "UNKNOWN" => "未知错误",
        "MOVE" => "文件保存时出错",
        "DIR_ERROR" => "创建目录失败"
    ];
    //上传配置信息
    private static $config = [];

    //返回信息格式
    private static $ret = [
        'code'=>'0',
        'msg'=>'上传失败',
        'images'=>[],
    ];

    /**
     * 图片AJAX上传
     * @param array $options
     * @return array
     */
    public static function action($options = [])
    {
        set_time_limit(300); //上传执行时间不能超过五分钟
        //上传配置
        $config = [
            "file"=>[],
            "savePath" => "/static/uploads/tmp",             //上传文件夹
            "fileField" => '0',                   //文件域名
            "maxSize" => 8 * 1024,                   //允许的文件最大尺寸，单位KB
            "allowFiles" => [".png", ".jpg", ".jpeg", ".gif", ".bmp"],   //允许的文件格式
            "format" => false,                   // 格式 默认为false；当值为（false 或 0*0 或 0%）不做处理;当格式为（X*Y）则处理成长为X,宽为Y的图片，当格式为（X%）则处理成原图的X%. 最大为6000*6000
            "addMark" => false,                  //是否添加水印,默认值是 true 表示添加水印
            "clear" => false,                    //清除缓存文件
            "uid" => '0',                        //使用者ID，默认0
        ];
        self::getWebPath();
        self::$config = array_merge($config, $options);
        if (isset(self::$config['format'])) {
            if (!(preg_match('/^([1-9])([\d]){0,}\*([1-9])([\d]){0,}$/i', self::$config['format']) || preg_match('/^([1-9][\d]{0,})\%$/i', self::$config['format']))) {
                self::$config['format'] = false;
            }
        }
        self::$fileField = self::$config['fileField'];
        self::$allFile = !empty(self::$config['file']) ? self::$config['file'] : (isset($_FILES[self::$fileField]) ? $_FILES[self::$fileField] : false);
        if (!self::$allFile) {
            self::$stateInfo = self::getStateInfo('NOT_FILE');
            return self::result();
        }
        self::getPath();
        self::$config['savePath'] = self::$webPath . $config['savePath'].'/'.$config['uid'];
        self::upImages();
        set_time_limit(1); // 重新恢复限制时间
        return self::$ret;
    }

    /**
     * 视频AJAX上传
     */
    public static function actionVideoupload()
    {
        $res['code'] = 1;
        $res['msg'] = '上传失败';
        //上传配置
        self::$webPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web';
        $config = array(
            "savePath" => "/statics/uploads",             //上传文件夹
            "tmpPath" => "/statics/uploads",            //上传缓存文件夹
            "maxSize" => 200 * 1024,                   //允许的文件最大尺寸，单位KB
            "allowFiles" => array(".flv", ".swf", ".mkv", ".avi", ".rm", ".rmvb", ".mpeg", ".mpg",
                ".ogg", ".ogv", ".mov", ".wmv", ".mp4", ".webm", ".mp3", ".wav", ".mid"),   //允许的文件格式
            "sort" => "default",                   //分类
            "uid" => "default",                            //uid
            "str_num" => "0",                      //URL截取的长度参数
            'addMark' => true,
        );

        //提示：需要保持 memory_limit > post_max_size > upload_max_filesize
        ini_set('upload_max_filesize', '200M');
        ini_set('post_max_size', '210M');
        ini_set('max_execution_time', '1200');
        ini_set('max_input_time', '1200');
        ini_set('memory_limit', '228M');
        $config['savePath'] = self::$webPath . $config['savePath'];
        try {
            self::$fileField = $_REQUEST['filefield'];
            $config["sort"] = $_REQUEST['sort'];
            $config["uid"] = $_REQUEST['uid'];
            $config["unique"] = $_REQUEST['unique'];
            $config["fileName"] = $_REQUEST['fileName'];
            $config["totalSize"] = $_REQUEST['totalSize'];
            $config["end"] = $_REQUEST['end'];
            $config["part"] = $_REQUEST['part'];
            self::$config = $config;
            self::$config['tmpPath'] = self::$config['savePath'] . '/' . self::$config['sort'] . '/' . self::$config['uid'] . '/tmp/' . self::$config['unique'] . '/';
            self::$stateInfo = self::$stateMap[0];
            self::upVideo();
            if ($config['end'] == 'true') {
                self::buildUpFiles();
            }
            $info = self::getFileInfo();
        } catch (Exception $e) {
            $res['code'] = 1;
            $res['msg'] = '参数异常或服务繁忙';
        }

        /**
         * 返回数据
         */
        if ($info['msg'] == 'SUCCESS') {
            $res['code'] = 0;
            $res['url'] = str_replace(self::$webPath, '/back', $info['url']);
            $res['msg'] = '上传成功';
        } else {
            $res['code'] = 1;
            $res['msg'] = $info['msg'];
        }
        return $res;
    }


    /**
     * 获取有效根目录
     */
    private static function getWebPath(){
        if (strrchr(self::$webPath, DIRECTORY_SEPARATOR) != DIRECTORY_SEPARATOR) {
            self::$webPath .= DIRECTORY_SEPARATOR;
        }
        self::$webPath .= 'public';
    }

    /**
     * 获取上传文件有效目录
     */
    private static function getPath(){
        if (substr(self::$config['savePath'],1,1) == '.') {
            self::$config['savePath'] = substr(self::$config['savePath'],1);
        }
        if (substr(self::$config['savePath'],1,1) != '/') {
            self::$config['savePath'] = '/' . self::$config['savePath'];
        }
        if (substr(self::$config['savePath'], -4) != "/tmp") {
            self::$config['savePath'] .= "/tmp";
        }
    }

    /**
     * 上传图片文件的主处理方法
     */
    private static function upImages()
    {
        //处理上传
        //是否多文件
        if(is_array(self::$allFile['name']) && is_array(self::$allFile['tmp_name'])){
            //多文件
            $ret = [];
            $images = [];
            foreach (self::$allFile['name'] as $key=>$value){
                $file = [];
                $file['name'] = self::$allFile['name'][$key];
                $file['type'] = self::$allFile['type'][$key];
                $file['size'] = self::$allFile['size'][$key];
                $file['tmp_name'] = self::$allFile['tmp_name'][$key];
                $file['error'] = self::$allFile['error'][$key];
                self::$itemFile = $file;
                $res = self::uploadImages();
                if ($res['code'] == '1'){
                    $imagesItem = [
                        'name'=>$res['name'],
                        'tmp_name'=>$res['tmp_name'],
                        'src'=>$res['src'],
                        'icon'=>$res['icon'],
                        'size'=>$res['size'],
                        'type'=>$res['type'],
                    ];
                    $images[] = $imagesItem;
                }
                $ret['code'] = $res['code'];
                $ret['msg'] = $res['msg'];
            }
            $ret['images'] = $images;
            self::$ret = $ret;
        }else{
            self::$itemFile = self::$allFile;
            $res = self::uploadImages();
            $ret = [];
            $images = [];
            if ($res['code'] == '1'){
                $imagesItem = [
                    'name'=>$res['name'],
                    'tmp_name'=>$res['tmp_name'],
                    'src'=>$res['src'],
                    'icon'=>$res['icon'],
                    'size'=>$res['size'],
                    'type'=>$res['type'],
                ];
                $images[] = $imagesItem;
            }
            $ret['code'] = $res['code'];
            $ret['msg'] = $res['msg'];
            $ret['images'] = $images;
            self::$ret = $ret;
        }
    }

    /**
     * 上传图片具体处理细节
     * @return array
     */
    private static function uploadImages(){

        self::$stateInfo = self::$stateMap[0];

        if (!self::$itemFile) {
            self::$stateInfo = self::getStateInfo('NOT_FILE');
            return self::result();
        }
        if (self::$itemFile['error']) {
            self::$stateInfo = self::getStateInfo(self::$itemFile['error']);
            return self::result();
        }
        if (!is_uploaded_file(self::$itemFile['tmp_name'])) {
            self::$stateInfo = self::getStateInfo("UNKNOWN");
            return self::result();
        }

        self::$oriName = self::$itemFile['name'];
        self::$fileSize = self::$itemFile['size'];
        self::$fileType = self::getFileExt();

        if (!self::checkSize()) {
            self::$stateInfo = self::getStateInfo("SIZE");
            return self::result();
        }
        if (!self::checkType()) {
            self::$stateInfo = self::getStateInfo("TYPE");
            return self::result();
        }

        $folder = self::getFolder(self::$config["savePath"]);

        if ($folder === false) {
            self::$stateInfo = self::getStateInfo("DIR_ERROR");
            return self::result();
        }

        self::$fullName = $folder . self::getName();
        if (self::$stateInfo == self::$stateMap[0]) {
            if (!move_uploaded_file(self::$itemFile["tmp_name"], self::$fullName)) {
                self::$stateInfo = self::getStateInfo("MOVE");
            } else {
                if (self::$config['addMark']) {
                    self::$fullName = self::addMark(self::$fullName);
                }
                if (self::$config['format']) {
                    self::$iconFileName = pathinfo(self::$fileName,PATHINFO_FILENAME) . '_icon' . self::getFileExt();
                    self::$iconFullName = $folder . self::$iconFileName;
                    $res = \app\common\components\Helper::imageChangeByFormat(self::$fullName, self::$iconFullName, self::$config['format']);
                    if($res['code'] == '0'){
                        self::$iconFileName = self::$fileName;
                        self::$iconFullName = $folder . self::$fileName;
                    }
                }
            }
        }
        return self::result();
    }

    /**
     * 返回数据
     * @return array
     */
    private static function result(){

        $info = self::getFileInfo();
        $ret = [];
        if ($info['msg'] == 'SUCCESS') {
            $ret['code'] = 1;
            $ret['name'] = $info['name'];
            $ret['tmp_name'] = $info['tmp_name'];
            $ret['src'] = str_replace(self::$webPath, '', $info['url']);
            $ret['icon'] = str_replace(self::$webPath, '', $info['url_icon']);
            $ret['size'] = $info['size'];
            $ret['type'] = $info['type'];
            $ret['msg'] = '上传成功';
        } else {
            $ret['code'] = 0;
            $ret['msg'] = $info['msg'];
        }
        return $ret;
    }

    /**
     * 上传视频文件的主处理方法
     */
    private static function upVideo()
    {
        //处理上传
        $file = self::$itemFile = $_FILES[self::$fileField];
        if (!$file) {
            self::$stateInfo = self::getStateInfo('POST');
            return;
        }
        if (self::$itemFile['error']) {
            self::$stateInfo = self::getStateInfo($file['error']);
            return;
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            self::$stateInfo = self::getStateInfo("UNKNOWN");
            return;
        }

        self::$oriName = self::$config['fileName'];
        self::$fileSize = self::$config['totalSize'];
        self::$itemFile['name'] = self::$config['fileName'];
        self::$fileType = self::getFileExt();

        if (!self::checkSize()) {
            self::$stateInfo = self::getStateInfo("SIZE");
            return;
        }
        if (!self::checkType()) {
            self::$stateInfo = self::getStateInfo("TYPE");
            return;
        }

        $folder = self::getFolder(self::$config["tmpPath"]);

        if ($folder === false) {
            self::$stateInfo = self::getStateInfo("DIR_ERROR");
            return;
        }

        self::$fullName = $folder . self::$config['part'] . '.tmp';
        if (self::$stateInfo == self::$stateMap[0]) {
            if (!move_uploaded_file($file["tmp_name"], self::$fullName)) {
                self::$stateInfo = self::getStateInfo("MOVE");
            }
        }
    }

    /**
     * 视频分批组装缓存文件
     */
    private static function buildUpFiles()
    {
        $result = true;
        $tempPath = self::$config['tmpPath'];
        $toPath = self::$config['savePath'] . '/' . self::$config['sort'] . '/' . self::$config['uid'] . '/tmp/';
        $folder = self::getFolder($toPath);
        if ($folder === false) {
            self::$stateInfo = self::getStateInfo("DIR_ERROR");
            return;
        }
        self::$fullName = $folder . self::getName();

        $fileExists = false;
        if ($file = fopen(self::$fullName, "a")) {
            for ($part = 1; $part < self::$config['part'] + 1; $part++) {
                $fromPath = $tempPath . $part . '.tmp';
                if (!file_exists($fromPath)) {
                    $fileExists = true;
                    $result = false;
                    break;
                }
                $tmpData = file_get_contents($fromPath);
                fwrite($file, $tmpData);
            }
            fclose($file);
            if ($fileExists) {
                @unlink(self::$fullName);
            }
        } else {
            $result = false;
        }

        //清除缓存
        self::deleteFolder($tempPath);

        if ($result === false) {
            self::$stateInfo = self::getStateInfo("MOVE");
            return;
        }
    }


    /**
     * @description 自动创建存储文件夹,且删除文件夹内所有文件
     * @param string $pathStr
     * @param bool $clear
     * @return bool|mixed|string
     */
    private static function getFolder($pathStr = './', $clear = false)
    {
        if (strrchr($pathStr, "/") != "/") {
            $pathStr .= "/";
        }
        if (!file_exists($pathStr)) {
            if (!mkdir($pathStr, 0777, true)) {
                return false;
            }
        } elseif ($clear) {
            if (is_dir($pathStr) && strpos($pathStr, '/static/uploads/') !== false) //$d是目录名
            {
                if ($od = opendir($pathStr)) {
                    while (($file = readdir($od)) !== false)  //读取该目录内文件
                    {
                        $delete = true;
                        $createTime = filectime($pathStr . $file);
                        //删除大于3天的文件
                        if ($createTime + 3 * 24 * 60 * 60 > time()) {
                            $delete = false;
                        }
                        if ($delete) {
                            @unlink($pathStr . $file);  //$file是文件名
                        }
                    }
                    closedir($od);
                }
            }
        }
        return $pathStr;
    }

    /**
     * 处理base64编码的图片上传
     * @param $base64Data
     * @return mixed
     */
    private static function base64ToImage($base64Data)
    {
        $img = base64_decode($base64Data);
        self::$fileName = time() . rand(1, 10000) . ".png";
        self::$fullName = self::getFolder(self::$config["savePath"]) . '/' . self::$fileName;
        if (!file_put_contents(self::$fullName, $img)) {
            self::$stateInfo = self::getStateInfo("IO");
            return;
        }
        self::$oriName = "";
        self::$fileSize = strlen($img);
        self::$fileType = ".png";
    }

    /**
     * 获取当前上传成功文件的各项信息
     * @return array
     */
    private static function getFileInfo()
    {
        return [
            "tmp_name" => self::$oriName,
            "name" => self::$fileName,
            "url" => self::$fullName,
            "url_icon" => self::$iconFullName,
            "size" => self::$fileSize,
            "type" => self::$fileType,
            "msg" => self::$stateInfo
        ];
    }

    /**
     * 上传错误检查
     * @param $errCode
     * @return string
     */
    private static function getStateInfo($errCode)
    {
        return !self::$stateMap[$errCode] ? self::$stateMap["UNKNOWN"] : self::$stateMap[$errCode];
    }

    /**
     * 重命名文件
     * @return string
     */
    private static function getName()
    {
        return self::$fileName = md5(date("YmdHis") . self::$config["uid"].rand(10000, 99999)) . self::getFileExt();
    }

    /**
     * 文件类型检测
     * @return bool
     */
    private static function checkType()
    {
        return in_array(self::getFileExt(), self::$config["allowFiles"]);
    }

    /**
     * 文件大小检测
     * @return bool
     */
    private static function checkSize()
    {
        return self::$fileSize <= (self::$config["maxSize"] * 1024);
    }

    /**
     * 获取文件扩展名
     * @return string
     */
    private static function getFileExt()
    {
        return strtolower(strrchr(self::$itemFile["name"], '.'));
    }

    /**
     * @todo : 本函数用于 将方形的图片压缩后
     *         再裁减成圆形 做成logo
     *         与背景图合并
     *         入口方法是 addMark();
     * @param string $background 背景图地址
     * @param string $mark 标志图片地址
     * @return string 返回url
     */
    public static function addMark($background, $mark = '')
    {
        //Logo
        if (empty($mark)) {
            $mark = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web/images/mark-logo.jpg';
        }
        $markimgurl = $mark;
        //背景图
        if (!file_exists($background)) {
            $res = '{"result_code":"404","msg":"图片未存在"}';
            exit($res);
        }
        //logo是否存在
        if (!file_exists($markimgurl)) {
            $res = '{"result_code":"404","msg":"图片未存在"}';
            exit($res);
        }
        $bgurl = $background;
        $imgs['dst'] = $bgurl;
        list($imgs['width'], $imgs['height']) = getimagesize($bgurl); //获取背景原图尺寸
        //第一步 压缩图片
        $imggzip = self::resize_img($markimgurl, $imgs);
        //第二步 裁减成圆角图片
        $imgs['src'] = self::alterCircle($imggzip);
        //第三步 合并图片
        $dest = self::mergerImg($imgs);
        return $dest;
    }

    //压缩图片
    private static function resize_img($url, $imgs = '', $path = '')
    {
        if (empty($path)) {
            $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web/statics/mark/';
        }
        if (!file_exists($path)) {
            self::getFolder($path);
        }
        $imgname = $path . uniqid() . '.jpg';
        $file = $url;
        list($width, $height) = getimagesize($file); //获取原图尺寸
        !empty($imgs['width']) ? $percent = ($imgs['width'] / 16 / $width) : $percent = (80 / $width);  //缩放尺寸百分百比
        //缩放尺寸
        $newwidth = $width * $percent;
        $newheight = $height * $percent;
        $src_im = imagecreatefromjpeg($file);
        $dst_im = imagecreatetruecolor($newwidth, $newheight);
        imagecopyresized($dst_im, $src_im, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($dst_im, $imgname); //输出压缩后的图片
        imagedestroy($dst_im);
        imagedestroy($src_im);
        return $imgname;
    }

    //生成圆角图片
    private static function alterCircle($url, $path = '')
    {
        if (empty($path)) {
            $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web/statics/mark/';
        }
        list($width, $height) = getimagesize($url); //获取原图尺寸
        $w = $width;
        $h = $height; // original size
        $original_path = $url;
        $dest_path = $path . uniqid() . '.png';
        $src = imagecreatefromstring(file_get_contents($original_path));
        $newpic = imagecreatetruecolor($w, $h);
        imagealphablending($newpic, false);
        $transparent = imagecolorallocatealpha($newpic, 0, 0, 0, 127);
        $r = $w / 2;
        for ($x = 0; $x < $w; $x++)
            for ($y = 0; $y < $h; $y++) {
                $c = imagecolorat($src, $x, $y);
                $_x = $x - $w / 2;
                $_y = $y - $h / 2;
                if ((($_x * $_x) + ($_y * $_y)) < ($r * $r)) {
                    imagesetpixel($newpic, $x, $y, $c);
                } else {
                    imagesetpixel($newpic, $x, $y, $transparent);
                }
            }
        imagesavealpha($newpic, true);
        // header('Content-Type: image/png');
        imagepng($newpic, $dest_path);
        imagedestroy($newpic);
        imagedestroy($src);
        unlink($url); //删除压缩图
        return $dest_path;
    }

    //php 合并图片
    private static function mergerImg($imgs, $path = '')
    {
        if (empty($path)) {
            $path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'web/statics/mark/tmp/';
        }
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                return false;
            }
        }
        $imgname = $path . self::getName();
        list($max_width, $max_height) = getimagesize($imgs['dst']);
        $dests = imagecreatetruecolor($max_width, $max_height);
        switch (exif_imagetype($imgs['dst'])) {
            case '2' : {
                $dst_im = imagecreatefromjpeg($imgs['dst']);
            }
                break;
            case '3' : {
                $dst_im = imagecreatefrompng($imgs['dst']);
            }
                break;
            default : {
                $res = '{"code":"1","msg":"暂不支持此图片格式"}';
                exit($res);
            }
                break;
        }
        imagecopy($dests, $dst_im, 0, 0, 0, 0, $max_width, $max_height);
        imagedestroy($dst_im);

        list($width, $height) = getimagesize($imgs['src']); //获取压缩图尺寸
        $src_im = imagecreatefrompng($imgs['src']);
        $src_info = getimagesize($imgs['src']);
        imagecopy($dests, $src_im, $max_width - $width - 14, $max_height - $height - 14, 0, 0, $src_info[0], $src_info[1]);
        imagedestroy($src_im);

//         var_dump($imgs);exit;
//         header("Content-type: image/jpeg");
        imagejpeg($dests, $imgname);
//        unlink($imgs['dst']);
        unlink($imgs['src']);
        copy($imgname, $imgs['dst']);
        unlink($imgname);
        $imgname = $imgs['dst'];
        return $imgname;
    }
}