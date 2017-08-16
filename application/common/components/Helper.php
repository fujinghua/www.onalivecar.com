<?php

namespace app\common\components;

use app\common\components\Configs;

/**
 * Description of Helper
 */
class Helper
{
    private static $_instance;
    /**
     * @return \app\common\components\Helper
     */
    public static function getInstance(){
        if (!self::$_instance){
            self::$_instance = new static();
        }
        return self::$_instance;
    }

    private function __construct()
    {

    }

    /**
     * @description 压缩图片 本函数用于 按照变量$format指定的格式，进行格式压缩.
     *
     * @start 入口方法是 image_png_size_add();
     * @param string $imgSrc 图片路径
     * @param string $imgDst 返回压缩后保存路径
     * @param string $format 格式 默认为600*600；当值为（0*0 或 0%）不做处理;当格式为（X*Y）则处理成长为X,宽为Y的图片，当格式为（X%）则处理成原图的X%. 最大为6000*6000
     * @return bool
     *
     * list($width,$height,$type)=getimagesize($imgSrc);
     * 索引 0 给出的是图像宽度的像素值
     * 索引 1 给出的是图像高度的像素值
     * 索引 2 给出的是图像的类型，返回的是数字，其中1 = GIF，2 = JPG，3 = PNG，4 = Sapp\common\components，5 = PSD，6 = BMP，7 = TIFF(intel byte order)，8 = TIFF(motorola byte order)，9 = JPC，10 = JP2，11 = JPX，12 = JB2，13 = SWC，14 = IFF，15 = WBMP，16 = XBM
     * 索引 3 给出的是一个宽度和高度的字符串，可以直接用于 HTML 的 <image> 标签
     * 索引 bits 给出的是图像的每种颜色的位数，二进制格式
     * 索引 channels 给出的是图像的通道值，RGB 图像默认是 3
     * 索引 mime 给出的是图像的 MIME 信息，此信息可以用来在 HTTP Content-type 头信息中发送正确的信息，如：header("Content-type: image/jpeg");
     */
    public static function imageChangeByFormat($imgSrc, $imgDst = '', $format = '600*600')
    {
        $res['code'] = '1';
        $res['msg'] = '成功';
        if (!file_exists($imgSrc)) {
            $res['code'] = '0';
            $res['msg'] = '找不到源文件';
            return $res;
        }
        $reWidth = 600;
        $reHeight = 600;
        list($width, $height, $type) = getimagesize($imgSrc);
        $newWidth = ($width > $reWidth ? $reHeight : ($width > 6000 ? 6000 : $width));
        $newHeight = ($height > $reHeight ? $reHeight : ($height > 6000 ? 6000 : $height));
        if (empty($imgDst)) {
            $imgDst = $imgSrc;
        }
        $suffix = pathinfo($imgDst, PATHINFO_EXTENSION);
        if (preg_match('/^([1-9])([\d]){0,}\*([1-9])([\d]){0,}$/i', $format)) {
            $getFormat = explode('*', $format);
            if ($getFormat[0] != 0 && $getFormat[1] != 0) {
                $reWidth = $getFormat[0];
                $reHeight = $getFormat[1];
            }
            $newWidth = ($width > $reWidth ? $reHeight : ($width > 6000 ? 6000 : $width));
            $newHeight = ($height > $reHeight ? $reHeight : ($height > 6000 ? 6000 : $height));
        }
        if (preg_match('/^([1-9][\d]{0,})\%$/i', $format)) {
            $scale = substr($format, 0, strlen($format) - 1) / 100;
            $newWidth = ($width * $scale > 6000 ? 6000 : $width * $scale);
            $newHeight = ($height * $scale > 6000 ? 6000 : $height * $scale);
        }
        switch ($type) {
            case 1: {
                $gifType = self::isGifFile($imgSrc);
                if ($gifType) {
                    $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                    $image = imagecreatefromgif($imgSrc);
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    switch ($suffix) {
                        case 'gif' : {
                            imagegif($image_wp, $imgDst);
                        }
                            break;
                        case 'jpg' : {
                            imagejpeg($image_wp, $imgDst, 75);
                        }
                            break;
                        case 'png' : {
                            imagepng($image_wp, $imgDst, 9);
                        }
                            break;
                        default : {
                            $res['code'] = '0';
                            $res['msg'] = '不支持输出扩展名为' . $suffix . '的文件';
                        }
                            break;
                    }
                    imagedestroy($image);
                    imagedestroy($image_wp);
                }
            }
                break;
            case 2: {
                $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                $image = imagecreatefromjpeg($imgSrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                switch ($suffix) {
                    case 'gif' : {
                        imagegif($image_wp, $imgDst);
                    }
                        break;
                    case 'jpg' : {
                        imagejpeg($image_wp, $imgDst, 75);
                    }
                        break;
                    case 'png' : {
                        imagepng($image_wp, $imgDst, 9);
                    }
                        break;
                    default : {
                        $res['code'] = '0';
                        $res['msg'] = '不支持输出扩展名为' . $suffix . '的文件';
                    }
                        break;
                }
                imagedestroy($image);
                imagedestroy($image_wp);
            }
                break;
            case 3: {
                $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                $image = imagecreatefrompng($imgSrc);
                imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                switch ($suffix) {
                    case 'gif' : {
                        imagegif($image_wp, $imgDst);
                    }
                        break;
                    case 'jpg' : {
                        imagejpeg($image_wp, $imgDst, 75);
                    }
                        break;
                    case 'png' : {
                        imagepng($image_wp, $imgDst, 9);
                    }
                        break;
                    default : {
                        $res['code'] = '0';
                        $res['msg'] = '不支持输出扩展名为' . $suffix . '的文件';
                    }
                        break;
                }
                imagedestroy($image);
                imagedestroy($image_wp);
            }
                break;
            case 6: {
                $image_wp = imagecreatetruecolor($newWidth, $newHeight);
                ini_set('max_execution_time', 300);
                $image = self::imageCreateFromBMP($imgSrc);
                ini_restore('max_execution_time');
                if ($image !== false) {
                    imagecopyresampled($image_wp, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
                    switch ($suffix) {
                        case 'gif' : {
                            imagegif($image_wp, $imgDst);
                        }
                            break;
                        case 'jpg' : {
                            imagejpeg($image_wp, $imgDst, 75);
                        }
                            break;
                        case 'png' : {
                            imagepng($image_wp, $imgDst, 9);
                        }
                            break;
                        default : {
                            $res['code'] = '0';
                            $res['msg'] = '不支持输出扩展名为' . $suffix . '的文件';
                        }
                            break;
                    }
                    imagedestroy($image);
                    imagedestroy($image_wp);
                } else {
                    $res['code'] = '0';
                    $res['msg'] = '读取bmp文件失败';
                }
            }
                break;
            default: {
                $res['code'] = '0';
                $res['msg'] = '只支持 gif/jpg/png/bmp 类型文件转换';
            }
                break;
        }
        return $res;
    }

    /**
     * @description 判断是否gif动画
     * @param string $image_file
     * @param string $image_file 图片路径
     * @return bool t 是 f 否
     */
    private static function isGifFile($image_file)
    {
        if (!$fp = fopen($image_file, 'rb')) {
            return false;
        }
        $image_head = fread($fp, 1024);
        fclose($fp);
        return preg_match("/" . chr(0x21) . chr(0xff) . chr(0x0b) . 'NETSCAPE2.0' . "/", $image_head) ? false : true;
    }

    /**
     * @description 读取bmp格式图片，返回GD图库资源
     * @param string $imgSrc 图片原始路径
     * @return bool| resource $image
     *
     * @example
     * $pic = '2.bmp';
     * $res = ImageCreateFromBMP($pic);
     * imagepng($res, '1.png');
     * imagejpeg($res, '1.jpeg');
     */
    public static function imageCreateFromBMP($imgSrc)
    {
        //O : overture du fichier en mode binaire
        if (!$fp = fopen($imgSrc, "rb")) {
            return false;
        }

        //1 : Chargement des ent�tes FICHIER
        $FILE = unpack("vfile_type/Vfile_size/Vreserved/Vbitmap_offset", fread($fp, 14));
        if ($FILE['file_type'] != 19778)
            return false;

        //2 : Chargement des ent�tes BMP
        $BMP = unpack('Vheader_size/Vwidth/Vheight/vplanes/vbits_per_pixel' .
            '/Vcompression/Vsize_bitmap/Vhoriz_resolution' .
            '/Vvert_resolution/Vcolors_used/Vcolors_important', fread($fp, 40));
        $BMP['colors'] = pow(2, $BMP['bits_per_pixel']);
        if ($BMP['size_bitmap'] == 0)
            $BMP['size_bitmap'] = $FILE['file_size'] - $FILE['bitmap_offset'];
        $BMP['bytes_per_pixel'] = $BMP['bits_per_pixel'] / 8;
        $BMP['bytes_per_pixel2'] = ceil($BMP['bytes_per_pixel']);
        $BMP['decal'] = ($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] -= floor($BMP['width'] * $BMP['bytes_per_pixel'] / 4);
        $BMP['decal'] = 4 - (4 * $BMP['decal']);
        if ($BMP['decal'] == 4)
            $BMP['decal'] = 0;

        //3 : Chargement des couleurs de la palette
        $PALETTE = array();
        if ($BMP['colors'] < 16777216) {
            $PALETTE = unpack('V' . $BMP['colors'], fread($fp, $BMP['colors'] * 4));
        }

        //4 : Cr�ation de l'image
        $IMG = fread($fp, $BMP['size_bitmap']);
        $VIDE = chr(0);

        $res = imagecreatetruecolor($BMP['width'], $BMP['height']);
        $P = 0;
        $Y = $BMP['height'] - 1;
        while ($Y >= 0) {
            $X = 0;
            while ($X < $BMP['width']) {
                if ($BMP['bits_per_pixel'] == 24)
                    $COLOR = unpack("V", substr($IMG, $P, 3) . $VIDE);
                elseif ($BMP['bits_per_pixel'] == 16) {
                    $COLOR = unpack("n", substr($IMG, $P, 2));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 8) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, $P, 1));
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 4) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 2) % 2 == 0)
                        $COLOR[1] = ($COLOR[1] >> 4);
                    else
                        $COLOR[1] = ($COLOR[1] & 0x0F);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } elseif ($BMP['bits_per_pixel'] == 1) {
                    $COLOR = unpack("n", $VIDE . substr($IMG, floor($P), 1));
                    if (($P * 8) % 8 == 0)
                        $COLOR[1] = $COLOR[1] >> 7;
                    elseif (($P * 8) % 8 == 1)
                        $COLOR[1] = ($COLOR[1] & 0x40) >> 6;
                    elseif (($P * 8) % 8 == 2)
                        $COLOR[1] = ($COLOR[1] & 0x20) >> 5;
                    elseif (($P * 8) % 8 == 3)
                        $COLOR[1] = ($COLOR[1] & 0x10) >> 4;
                    elseif (($P * 8) % 8 == 4)
                        $COLOR[1] = ($COLOR[1] & 0x8) >> 3;
                    elseif (($P * 8) % 8 == 5)
                        $COLOR[1] = ($COLOR[1] & 0x4) >> 2;
                    elseif (($P * 8) % 8 == 6)
                        $COLOR[1] = ($COLOR[1] & 0x2) >> 1;
                    elseif (($P * 8) % 8 == 7)
                        $COLOR[1] = ($COLOR[1] & 0x1);
                    $COLOR[1] = $PALETTE[$COLOR[1] + 1];
                } else {
                    return false;
                };
                imagesetpixel($res, $X, $Y, $COLOR[1]);
                $X++;
                $P += $BMP['bytes_per_pixel'];
            }
            $Y--;
            $P += $BMP['decal'];
        }
        //Fermeture du fichier
        fclose($fp);

        return $res;
    }

    /**
     * @description 函数:调整图片尺寸或生成缩略图
     * 允许接受的图片类型 ('img/gif','img/jpg','img/jpeg','img/bmp','img/pjpeg','img/x-png');
     * @param string $Image 需要调整的图片(含路径)
     * @param int $Dw =450  调整时最大宽度;缩略图时的绝对宽度
     * @param int $Dh =450  调整时最大高度;缩略图时的绝对高度
     * @param int $Type =1  1,调整尺寸; 2,生成缩略图
     * @return bool
     */
    public static function Img($Image, $Dw = 450, $Dh = 450, $Type = 1)
    {
        $path = 'img/';//路径
        $phtypes = ['img/gif', 'img/jpg', 'img/jpeg', 'img/bmp', 'img/pjpeg', 'img/x-png'];
        IF (!File_Exists($Image)) {
            Return False;
        }
        //如果需要生成缩略图,则将原图拷贝一下重新给$Image赋值
        IF ($Type != 1) {
            Copy($Image, Str_Replace(".", "_x.", $Image));
            $Image = Str_Replace(".", "_x.", $Image);
        }
        //取得文件的类型,根据不同的类型建立不同的对象
        $ImgInfo = GetImageSize($Image);
        Switch ($ImgInfo[2]) {
            Case 1:
                $Img = @ImageCreateFromGIF($Image);
                Break;
            Case 2:
                $Img = @ImageCreateFromJPEG($Image);
                Break;
            Case 3:
                $Img = @ImageCreateFromPNG($Image);
                Break;
        }
        //如果对象没有创建成功,则说明非图片文件
        IF (Empty($Img)) {
            //如果是生成缩略图的时候出错,则需要删掉已经复制的文件
            IF ($Type != 1) {
                Unlink($Image);
            }
            Return False;
        }
        //如果是执行调整尺寸操作则
        IF ($Type == 1) {
            $w = ImagesX($Img);
            $h = ImagesY($Img);
            $width = $w;
            $height = $h;
            IF ($width > $Dw) {
                $Par = $Dw / $width;
                $width = $Dw;
                $height = $height * $Par;
                IF ($height > $Dh) {
                    $Par = $Dh / $height;
                    $height = $Dh;
                    $width = $width * $Par;
                }
            } ElseIF ($height > $Dh) {
                $Par = $Dh / $height;
                $height = $Dh;
                $width = $width * $Par;
                IF ($width > $Dw) {
                    $Par = $Dw / $width;
                    $width = $Dw;
                    $height = $height * $Par;
                }
            } Else {
//                $width = $width;
//                $height = $height;
            }
            $nImg = ImageCreateTrueColor($width, $height);   //新建一个真彩色画布
            ImageCopyReSampled($nImg, $Img, 0, 0, 0, 0, $width, $height, $w, $h);//重采样拷贝部分图像并调整大小
            ImageJpeg($nImg, $Image);     //以JPEG格式将图像输出到浏览器或文件
            Return True;
            //如果是执行生成缩略图操作则
        } Else {
            $w = ImagesX($Img);
            $h = ImagesY($Img);
            $width = $w;
            $height = $h;
            $nImg = ImageCreateTrueColor($Dw, $Dh);
            IF ($h / $w > $Dh / $Dw) { //高比较大
                $width = $Dw;
                $height = $h * $Dw / $w;
                $IntNH = $height - $Dh;
                ImageCopyReSampled($nImg, $Img, 0, -$IntNH / 1.8, 0, 0, $Dw, $height, $w, $h);
            } Else {   //宽比较大
                $height = $Dh;
                $width = $w * $Dh / $h;
                $IntNW = $width - $Dw;
                ImageCopyReSampled($nImg, $Img, -$IntNW / 1.8, 0, 0, 0, $width, $Dh, $w, $h);
            }
            ImageJpeg($nImg, $Image);
            Return True;
        }
    }

    /**
     * @description 生成打印显示二维码
     * @param string $value
     * @param bool $logo
     */
    public static function actionQrcodeOutByPaint($value = ' ', $logo = true)
    {
        $backend = dirname(__DIR__);
        $prefix = $backend . DIRECTORY_SEPARATOR . 'web/';
        include_once $backend . DIRECTORY_SEPARATOR . 'components/qrcode/phpqrcode.php';
        $errorCorrectionLevel = 'H';//容错级别
        $matrixPointSize = 34;//生成图片大小
        $margin = 2; //外围大小
        $saveandprint = true; //保存
        $out = $prefix . './images/qrcode.png'; //输出二维码文件地址
        $logoFile = $prefix . './images/qrcode_for_YouTeeFit_App_logo.png';//准备好的logo图片
        //生成二维码图片
        $QR = \app\common\components\QRcode::png($value, $out, $errorCorrectionLevel, $matrixPointSize, $margin, $saveandprint);

        //合并logo
        if (!($logo === false || $logo === 0 || $logo === null || $logo === '0') && file_exists($logoFile)) {
            $QR = $out; //已经生成的原始二维码图
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logoData = imagecreatefromstring(file_get_contents($logoFile));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logoData);//logo图片宽度
            $logo_height = imagesy($logoData);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logoData, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            //输出图片
            imagepng($QR, $out); //覆盖之前已经生成的原始二维码图
        }
        Header("Content-type: image/png");
        $src = str_replace($prefix, '', $out);
        echo '<img src="' . $src . '">';
    }

    /**
     * @description 生成二维码以图片头文件输出
     * @param string $value
     * @param bool $logo
     */
    public static function actionQrcodeOutByImage($value = ' ', $logo = true)
    {
        $backend = dirname(__DIR__);
        $prefix = $backend . DIRECTORY_SEPARATOR . 'web/';
        include_once $backend . DIRECTORY_SEPARATOR . 'components/qrcode/phpqrcode.php';
        $errorCorrectionLevel = 'H';//容错级别
        $matrixPointSize = 34;//生成图片大小
        $margin = 2; //外围大小
        $saveandprint = true; //保存
        $out = $prefix . './images/qrcode.png'; //输出二维码文件地址
        $logoFile = $prefix . './images/qrcode_for_YouTeeFit_App_logo.png';//准备好的logo图片
        //生成二维码图片
        $QR = \app\common\components\QRcode::png($value, $out, $errorCorrectionLevel, $matrixPointSize, $margin, $saveandprint);

        //合并logo
        if (!($logo === false || $logo === 0 || $logo === null || $logo === '0')) {
            $QR = $out; //已经生成的原始二维码图
            $QR = imagecreatefromstring(file_get_contents($QR));
            if (file_exists($logoFile)) {
                $logoData = imagecreatefromstring(file_get_contents($logoFile));
                $QR_width = imagesx($QR);//二维码图片宽度
                $QR_height = imagesy($QR);//二维码图片高度
                $logo_width = imagesx($logoData);//logo图片宽度
                $logo_height = imagesy($logoData);//logo图片高度
                $logo_qr_width = $QR_width / 5;
                $scale = $logo_width / $logo_qr_width;
                $logo_qr_height = $logo_height / $scale;
                $from_width = ($QR_width - $logo_qr_width) / 2;
                //重新组合图片并调整大小
                imagecopyresampled($QR, $logoData, $from_width, $from_width, 0, 0, $logo_qr_width,
                    $logo_qr_height, $logo_width, $logo_height);
                //输出图片
//                imagepng($QR, $out); //覆盖之前已经生成的原始二维码图
            }
        }
        //输出图片
        Header("Content-type: image/png");
        ImagePng($QR); // 使用GD方法输出
    }

    /**
     * @description 生成二维码
     * @param string $value
     * @param string $out 生成保存地址
     * @param bool|string $logo
     * @return string
     */
    public static function GenerateQrcodeImage($value = ' ', $out = './images/qrcode.png', $logo = './images/qrcode_for_YouTeeFit_App_logo.png')
    {
        $suffix = pathinfo($out, PATHINFO_EXTENSION);
        if ($suffix != 'png') {
            return false;
        }
        $backend = dirname(__DIR__);
        $prefix = $backend . DIRECTORY_SEPARATOR . 'web/';
        include_once $backend . DIRECTORY_SEPARATOR . 'components/qrcode/phpqrcode.php';
        $errorCorrectionLevel = 'H';//容错级别
        $matrixPointSize = 34;//生成图片大小
        $margin = 2; //外围大小
        $saveandprint = true; //保存
        if ($out == './images/qrcode.png') {
            $out = $prefix . $out; //输出二维码文件地址
        } else {
            if (!is_dir(pathinfo($out, PATHINFO_DIRNAME))) {
                if (strstr($out,'/back/') !== false){
                    $out =  str_replace('/back/', '/backend/web/', $out); //只替换一次
                }
                if (!is_dir(pathinfo($out, PATHINFO_DIRNAME))) {
                    self::getFolder(pathinfo($out, PATHINFO_DIRNAME));
                }
            }
        }
        $logoFile = $prefix . $logo;//准备好的logo图片
        //生成二维码图片
        $QR = \app\common\components\QRcode::png($value, $out, $errorCorrectionLevel, $matrixPointSize, $margin, $saveandprint);

        //输出图片
        if (!($logo === false || $logo === 0 || $logo === null || $logo === '0') && file_exists($logoFile)) {
            $QR = $out; //已经生成的原始二维码图
            $QR = imagecreatefromstring(file_get_contents($QR));
            $logoData = imagecreatefromstring(file_get_contents($logoFile));
            $QR_width = imagesx($QR);//二维码图片宽度
            $QR_height = imagesy($QR);//二维码图片高度
            $logo_width = imagesx($logoData);//logo图片宽度
            $logo_height = imagesy($logoData);//logo图片高度
            $logo_qr_width = $QR_width / 5;
            $scale = $logo_width / $logo_qr_width;
            $logo_qr_height = $logo_height / $scale;
            $from_width = ($QR_width - $logo_qr_width) / 2;
            //重新组合图片并调整大小
            imagecopyresampled($QR, $logoData, $from_width, $from_width, 0, 0, $logo_qr_width,
                $logo_qr_height, $logo_width, $logo_height);
            //输出图片
            imagepng($QR, $out); //覆盖之前已经生成的原始二维码图
        }
        return $out;
    }

    /**
     * @description 读取特定文件内容
     * @param string $importFileName
     * @param array $options
     * @param array $sheetName
     * @param string $importFileType //    Excel5|Excel2007|Excel2003XML|OOCalc|SYLK|Gnumeric|CSV
     * @return array
     */
    public static function importByPhpExcel($importFileName,$options = [],$sheetName = [], $importFileType = 'Excel2007')
    {
        $ret = [];
        include_once dirname(__FILE__) . '/phpexcel/PHPExcel.php';
        $importFileType = \PHPExcel_IOFactory::identify($importFileName);
        $objReader = \PHPExcel_IOFactory::createReader($importFileType);
        if (!empty($sheetName)){
            $objReader->setLoadSheetsOnly($sheetName);       // 加载单个工作表，传入工作表名字
        }
        $objReader->setLoadAllSheets();      // 加载所有的工作表
        $objPHPExcel = $objReader->load($importFileName);
        $objPHPExcel->getSheetNames();       // 获取所有工作表的名字数组

        foreach ($objPHPExcel->getAllSheets() as  $key => $value){
            $currentSheet = $value;
            $highestRow = $currentSheet->getHighestRow(); // 取得总行数
            $highestColumn = $currentSheet->getHighestColumn(); // 取得总列数
//            $arr = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'F', 7 => 'G', 8 => 'H', 9 => 'I', 10 => 'J', 11 => 'K', 12 => 'L', 13 => 'M', 14 => 'N', 15 => 'O', 16 => 'P', 17 => 'Q', 18 => 'R', 19 => 'S', 20 => 'T', 21 => 'U', 22 => 'V', 23 => 'W', 24 => 'X', 25 => 'Y', 26 => 'Z'];
            $result = [];
            // 一次读取一列
            for($rowIndex=1;$rowIndex<=$highestRow;$rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
                for($colIndex='A';$colIndex<=$highestColumn;$colIndex++){
                    $addr = $colIndex.$rowIndex;
                    $cell = $currentSheet->getCell($addr)->getValue();
//                    $cell = $currentSheet->getCellByColumnAndRow($colIndex, $rowIndex)->getValue();
                    if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                        $cell = $cell->__toString();
                    }
                    $result[$rowIndex][$colIndex] = $cell;
                }
            }
            $ret[] = $result;
        }

        return $ret;
    }

    /**
     * @description 导出Excel文件
     * @param array $data
     * @param string $fileName
     * @param string $description
     * @param string $tb_name
     * @param string $tb_id
     * @param string $tb_category
     * @param string $title
     * @param string $keywords
     * @param string $category
     * @return bool|integer
     */
    public static function ExportToExcel($data, $fileName, $description, $tb_name, $tb_id, $tb_category, $title = '', $keywords = '', $category = '')
    {
        if (!is_array($data) && empty($data)) {
            return false;
        }

        if (($res = self::getDownload($tb_name, $tb_id, $tb_category)) > 0) {
            return $res;
        }

        include_once dirname(__FILE__) . '/phpexcel/PHPExcel.php';
        $objPHPExcel = new \PHPExcel();
        if (empty($description)) {
            $description = 'You Tee Fit + 数据 - ' . date('Y-m-d H:i:s');
        } else {
            $description = 'You Tee Fit + ' . $description;
        }
        if (empty($fileName)) {
            $fileName = date('YmdHis') . '_YouTeeFit+_数据';
        } else {
            $fileName = date('YmdHis') . '_' . $fileName . '_YouTeeFit+';
        }
        $subject = '';
        if (empty($title)) {
            $title = $description;
            $subject = $description;
        }
        $creator = Configs::$app->user->identity->username . ' _ http://www.youteefit.com';
        if (empty($keywords)) {
            $keywords = 'You Tee Fit';
        }
        if (empty($category)) {
            $category = 'You Tee Fit';
        } else {
            $category .= ' You Tee Fit';
        }
        //系列数
        $series = ["A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z"];

        // Set properties
        $objPHPExcel->getProperties()->setCreator($creator)
            ->setLastModifiedBy($creator)
            ->setTitle($title)
            ->setSubject($subject)
            ->setDescription($description)
            ->setKeywords($keywords)
            ->setCategory($category);

        // Add some data
        $objPHPExcel->setActiveSheetIndex(0);

        //添加头部 初始化列数
        $cHeader = 0;
        if (isset($data['header']) && isset($data['data'])) {
            $cHeader = count($data['header']);
            $data = [$data];
        }
        foreach ($data as $key => $value) {
            if (isset($value['header'])) {
                if (count($value['header']) > $cHeader) {
                    $cHeader = count($value['header']);
                }
            }
        }
        $captions = $series;

        //列数超过26列，则增加[A-Z]系列
        if ($cHeader >= 26) {
            $multiple = ceil($cHeader / 26);
            for ($mi = 2; $mi <= $multiple; $mi++) {
                $prefix = '';
                for ($ki = 1; $ki < $mi; $ki++) {
                    $prefix .= $series[$ki - 1];
                }
                foreach ($series as $sValue) {
                    $captions[] = $prefix . $sValue;
                }
            }
        }

        $i = 1;
        foreach ($data as $key => $value) {
            if (isset($value['header'])) {
                $cHeader = count($value['header']);
                //合并两行并且显示该数据概述
                if (isset($value['description'])){
                    $description = $value['description'];
                }
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':' . $captions[$cHeader - 1] . ($i+1));
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i.'', $description);
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.'')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $i += 2;

                $header = $value['header'];
                for ($ci = 0; $ci < $cHeader; $ci++) {
                    $objPHPExcel->getActiveSheet()->SetCellValue($captions[$ci] . $i, $header[$ci]);
                }
                $i++;
            }
            if (isset($value['data'])) {
                //添加数据
                $count = count($value['data']) + $i;
                if (empty($value['data'])){
                    $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':' . $captions[$cHeader - 1] . ($i+1));
                    $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i.'', '没有数据');
                    $objPHPExcel->getActiveSheet()->getStyle('A'.$i.'')->applyFromArray([
                        'font' => [
                            'bold' => true
                        ],
                        'alignment' => [
                            'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                            'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        ],
                    ]);
                    $i += 2;
                }
                foreach ($value['data'] as $k => $v) {
                    $j = 0;
                    foreach ($v as $_k => $_v) {
                        $objPHPExcel->getActiveSheet()->SetCellValue($captions[$j] . $i, $_v);
                        $j++;
                    }
                    if ($i < $count) {
                        $i++;
                    }
                }
            }else{
                $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':' . $captions[$cHeader - 1] . ($i+1));
                $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i.'', '没有数据');
                $objPHPExcel->getActiveSheet()->getStyle('A'.$i.'')->applyFromArray([
                    'font' => [
                        'bold' => true
                    ],
                    'alignment' => [
                        'horizontal' => \PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PHPExcel_Style_Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $i += 2;
            }
            $i++;
            $objPHPExcel->getActiveSheet()->mergeCells('A'.$i.':' . $captions[$cHeader - 1] . ($i+1));
            $objPHPExcel->getActiveSheet()->SetCellValue('A'.$i.'', '  ');
            $i += 2;
        }

        // Save Excel 2007 file
        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        //生成储地址
        $backend = dirname(__DIR__);
        $prefix = $backend . DIRECTORY_SEPARATOR . 'web/statics/excel/' . Configs::$app->user->id . '/';
        $filePath = $prefix . $fileName . '.xlsx';
        self::getFolder(pathinfo($filePath, PATHINFO_DIRNAME), true);
        $objWriter->save($filePath);
        $url = str_replace($backend . DIRECTORY_SEPARATOR . 'web/', '/back/', $filePath);
        $res = self::createDownloadList($url, $description, $tb_name, $tb_id, $tb_category);
        return $res;
    }


    /**
     * @description 推送的Curl方法
     * @param $url
     * @param array $param
     * @param string $method
     * @param array $header
     * @param int $timeout
     * @param bool $returnCookie
     * @param bool $CA
     * @return mixed
     */
    public static function Curl($url,$param = [], $method = 'post', $header = [],$timeout = 60, $returnCookie = false, $CA = true)
    {

        $cacert = getcwd() . '/cacert.pem'; //CA根证书
        $SSL = substr($url, 0, 8) == "https://" ? true : false;

        if (strtolower($method) == 'get'){
            $newParam = '';
            if (is_array($param)){
                foreach ($param as $key => $value){
                    $newParam .= '&'.$key.'='.$value;
                }
            }
            $param = $newParam;
            if (stristr($url,'?') !== false){
                $url .= $param;
            }else{
                $url .= '?'.ltrim($newParam,'&');
            }
        }

        $curlPost = $param;
        $ch = curl_init();                                      //初始化curl
        curl_setopt($ch, CURLOPT_URL, $url);                 //抓取指定网页

        if (is_numeric($timeout) && $timeout != '0'){
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout-2);
        }

        if ($SSL && $CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);   // 只信任CA颁布的证书
            curl_setopt($ch, CURLOPT_CAINFO, $cacert); // CA根证书（用来验证的网站证书是否是CA颁布）
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // 检查证书中是否设置域名，并且是否与提供的主机名匹配
        } else if ($SSL && !$CA) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 信任任何证书
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1); // 检查证书中是否设置域名
        }

        curl_setopt($ch, CURLOPT_HEADER, 0);                    //设置header
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);           // 增加 HTTP Header（头）里的字段
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);            //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Expect:']); //避免data数据过长问题
        if(strtolower($method) == 'post') {
            curl_setopt($ch, CURLOPT_POST, 1);  //post提交方式
            curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($curlPost)); //data with URLEncode
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);        // 终止从服务端进行验证
        $data = curl_exec($ch);                                 //运行curl

        //var_dump(curl_error($ch));                   //查看报错信息

        curl_close($ch);

        if($returnCookie){
            list($header, $body) = explode("\r\n\r\n", $data, 2);
            preg_match_all("/Set\-Cookie:([^;]*);/", $header, $matches);
            $info['cookie']  = substr($matches[1][0], 1);
            $info['content'] = $body;
            $ret = $info;
        }else{
            $ret = $data;
        }

        return $ret;
    }


    /**
     * @description 下载文件
     * @param resource|string $file_path
     * @param string $reName
     * @return bool
     */
    public static function Download($file_path, $reName = '')
    {
        header("Content-type:text/html;charset=utf-8");
        $file_name = pathinfo($file_path, PATHINFO_BASENAME);
        if (!empty($reName)) {
            $file_name = $reName;
        }

        //用以解决中文不能显示出来的问题
        $file_name = mb_convert_encoding($file_name, 'UTF-8', 'GB2312');

        //首先要判断给定的文件存在与否
        if (!file_exists($file_path)) {
            return false;
        }
        $fp = fopen($file_path, "r");
        $file_size = filesize($file_path);

        //下载文件需要用到的头
        header("Content-type: application/octet-stream");
        header("Accept-Ranges: bytes");
        header("Accept-Length:" . $file_size);
        header('Content-Transfer-Encoding: binary');
        header("Content-Disposition: attachment; filename=" . $file_name);
        header('Content-Type: application/octet-stream; name=' . $file_name);
        $buffer = 1024;
        $file_count = 0;
        //向客户端返回数据
        while (!feof($fp) && $file_count < $file_size) {
            $file_con = fread($fp, $buffer);
            $file_count += $buffer;
            echo $file_con;
        }
        fclose($fp);
        return true;
    }

    /**
     * @description 添加下载清单
     * @param string $file_path
     * @param string $title
     * @param string $tb_name
     * @param string $tb_id
     * @param string $tb_category
     * @return int
     */
    private static function createDownloadList($file_path, $title, $tb_name, $tb_id, $tb_category)
    {
        $uid = Configs::$app->user->id;
        $title = substr($title, 0, 50);
        if (($model = \backend\models\Download::findOne(['uid' => $uid, 'tb_name' => $tb_name, 'tb_id' => $tb_id])) === null) {
            $model = new \backend\models\Download();
            $model->uid = $uid;
            $model->title = $title;
            $model->tb_name = $tb_name;
            $model->tb_id = $tb_id;
            $model->tb_category = $tb_category;
        }

        $model->url = $file_path;
        $model->created_at = date('Y-m-d H;i:s');
        $model->save();
        $res = $model->id;

        //删除三天前的下载清单
        \backend\models\Download::deleteAll(['<=', 'created_at', date('Y-m-d H:i:s', time() - 3 * 24 * 60 * 60)]);

        return $res;
    }

    /**
     * @description 获取是否已经下载列表是否已经有该结果
     * @param string $tb_name
     * @param string $tb_id
     * @param string $tb_category
     * @return integer
     */
    private static function getDownload($tb_name, $tb_id, $tb_category)
    {
        $uid = Configs::$app->user->id;
        $res = 0;
        $where = ['and', ['uid' => $uid, 'tb_name' => $tb_name, 'tb_id' => $tb_id, 'tb_category' => $tb_category], ['>', 'created_at', date('Y-m-d H:i:s', time() - 3 * 24 * 60 * 60)]];
        $model = \backend\models\Download::find()->where($where)->all();
        if (count($model) > 0) {
            $model = $model[0];
            $backend = dirname(__DIR__);
            $url = str_replace('/back/', $backend . DIRECTORY_SEPARATOR . 'web/', $model->url);
            $url = iconv("utf-8", "gb2312", $url);
            if (file_exists($url)) {
                $res = $model->id;
            }
        }
        return $res;
    }

    /**
     * @description 删除文件夹及其文件夹下所有文件
     * @param string $pathStr
     * @return bool
     */
    public static function deleteFolder($pathStr)
    {
        if (!file_exists($pathStr)) {
            if (strstr($pathStr,'/back/') !== false){
                $pathStr = preg_replace('/\/back\//', '/backend/web/', $pathStr, 1); //只替换一次
            }
            if (!file_exists($pathStr)){
                return false;
            }
        }

        //$d不是statics目录下的文件不给予删除
        if (strstr($pathStr,'/statics/') === false){
            return false;
        }

        if (!is_dir($pathStr)) {
            return false;
        }

        //先删除目录下的文件：
        $dh = opendir($pathStr);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $pathStr . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    self::deleteFolder($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($pathStr)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @description 自动创建存储文件夹,且删除文件夹内所有文件
     * @param string $pathStr 文件夹路径
     * @param bool $clear 是否清除该文件夹路径下的文件,当为true 时，会检查是否排除$except里面指定的文件不删除
     * @param array $except 当需要删除时会排除$except里面指定的文件不删除, if $clear is true, it will check the except file in array ,when the condition can is to delete the file
     * @return bool|string
     */
    public static function getFolder($pathStr = './', $clear = false, $except = [])
    {
        if (strstr($pathStr, '/back/') !== false){
            $pathStr = preg_replace('/\/back\//', '/backend/web/', $pathStr, 1); //只替换一次
        }
        if (strrchr($pathStr, "/") != "/") {
            $pathStr .= "/";
        }
        if (!file_exists($pathStr)) {
            if (!mkdir($pathStr, 0777, true)) {
                return false;
            }
        } elseif ($clear) {
            if (is_dir($pathStr) && strpos($pathStr, '/statics/')) //$d是目录名
            {
                if ($od = opendir($pathStr)) {
                    while (($file = readdir($od)) !== false)  //读取该目录内文件
                    {
                        $delete = true;
                        $createTime = filectime($pathStr . $file);
                        if ($createTime + 3 * 24 * 60 * 60 > time()) {
                            $delete = false;
                        }
                        foreach ($except as $value) {
                            if ($value == $file) {
                                $delete = false;
                            }
                            if ($value == $pathStr . $file) {
                                $delete = false;
                            }
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
     * @description 数组转换成字符串(兼容中文),为了保存！
     * @param array $data
     * @return string
     */
    public static function arrayToString($data = [])
    {
        if (!is_array($data)){
            return '';
        }
        $ret = self::arrayImplode($data);
        return $ret;
    }

    private static function arrayImplode($data)
    {
        $encode = false;
        if (is_array($data)) {
            $encode = true;
            foreach ($data as $k => $v) {
                if (!is_scalar($v)) {
                    if (is_array($v)) {
                        $data[$k] = self::arrayImplode($v);
                    } elseif (is_object($data)) {
                        $data->$k = self::arrayImplode($v);
                    }
                }
            }
        }
        if ($encode) {
            $tmp = '';
            foreach ($data as $kk => $vv) {
                if (substr($vv, 0, 1) == '[') {
                    $tmp .= "'" . $kk . "' => " . $vv . ", ";
                } else {
                    $tmp .= "'" . $kk . "' => '" . $vv . "', ";
                }
            }
            $data = "[" . rtrim($tmp, ', ') . "]";
        }
        return $data;
    }


    /**
     * @description 系列化textarea换行符
     * @param $text
     * @param string $key
     * @return array
     */
    public static function formatTextToArray($text, $key = 'item')
    {
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win')) {
            $crlf = "\r\n";
        } elseif (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac')) {
            $crlf = "\r"; // for very old MAC OS
        } else {
            $crlf = "\n";
        }
        $res = [];
        $text = explode($crlf, $text);
        foreach ($text as $value) {
            $value = str_replace('\r\n',' ',$value);
            $value = str_replace(PHP_EOL,' ',$value);
            $value = str_replace('\r',' ',$value);
            $res[][$key] = $value;
        }
        return $res;
    }

    /**
     * @description 系列化组合字符串
     * @param $str
     * @param $format
     * @param string $key
     * @return array
     */
    public static function formatStrToArray($str, $format, $key = 'item')
    {
        $res = [];
        $str = explode($format, $str);
        foreach ($str as $value) {
            $res[][$key] = $value;
        }
        return $res;
    }


    /**
     * xml字符串转数组
     * @param string $xml      xml字符串   请确保xml结构完全正确规范
     * @return array
     */
    function xmlToArray($xml){
        //将xml字符串转换为对象
        $obj = simplexml_load_string($xml);
        //将对象转换成json字符串再将json字符串转为数组
        $array = json_decode(json_encode($obj),true);

        return $array;
    }

    /**
     * @description 设置缓存
     * @param $html
     * @param $userId
     * @param $route
     * @param $dependency
     * @param null $root
     * @param int $duration
     * @param bool $refresh
     */
    public static function setCache($html, $userId, $route, $dependency, $root = null, $duration = 0, $refresh = false)
    {
        $tag = Configs::$app->id;
        $key = [$userId, $route, $dependency, $root, $tag];
        $cache = Configs::$app->cache;
        if ($duration !== 0 && intval($duration) === 0) {
            $duration = Configs::$app->params['cacheDuration'];
        }
        if (intval($duration) < 0) {
            $duration = Configs::$app->params['cacheDuration'];
        }

        if ($refresh || (($cache->get($key)) === false)) {
            if ($cache !== null) {
                $cache->set($key, $html, $duration);
            }
        }
    }

    /**
     * @description 读取缓存
     * @param $userId
     * @param $route
     * @param $dependency
     * @param null $root
     * @return mixed
     */
    public static function getCache($userId, $route, $dependency, $root = null)
    {
        $tag = Configs::getIdentity()->getModelName();
        $key = [$userId, $route, $dependency, $root, $tag];
        $cache = Configs::$app->cache;
        return $cache->get($key);
    }


    /**
     * 抛出异常
     * @param null $statusCode
     * @param null $message
     * @param array $headers
     * @param null $code
     */
    public static function HttpException($statusCode = null, $message = null, array $headers = [], $code = null){
        if (empty($statusCode)){
            $statusCode = '404';
        }
        if (empty($code)){
            $code = $statusCode;
        }
        if (empty($message)){
            $message = '请求不存在';
        }
        if (empty($headers)){
            $headers = ['code'=>$code,'msg'=>$message,'info'=>$message];
        }
        throw new \think\Exception\HttpException($statusCode,$message,null,$headers,$code);
    }

    public static function deleteLog($create_uid, $delete_uid, $table_name,$table_id, $data = null){
        $model = new \backend\models\DeleteLog();
        if (strlen($table_name) >= 50){
            return false;
        }
        $model->create_uid = $create_uid;
        $model->delete_uid = $delete_uid;
        $model->table_name = $table_name;
        $model->table_id = $table_id;
        $model->created_at = date('Y-m-d H:i:s');
        if (!empty($data)){
            if (is_scalar($data)){
                $model->data = $data;
            }
            if (is_array($data)){
                $model->data = self::ch_json_encode($data);
            }
        }else{
            $username = '';
            $dUser = \backend\models\Admin::findOne($delete_uid);
            if ($dUser){
                $username = $dUser->username;
            }
            $res = ['action'=>'删除','create_username'=>Configs::$app->user->identity->username,'delete_username'=>$username];
            $model->data = self::ch_json_encode($res);
        }
        if ($model->save()){
            return true;
        }
        return false;
    }

    /**
     *数组转换成JSON(兼容中文)
     */
    public static function ch_json_encode($data)
    {
        $ret = self::ch_urlencode($data);
        $ret = json_encode($ret);
        return urldecode($ret);
    }

    /**
     * @param array |\think\Model |mixed  $resultSet
     * @return array
     */
    public static function toArray($resultSet){
        $ret = [];
        if (empty($resultSet) || !(is_array($resultSet) || is_object($resultSet))){
            return $ret;
        }
        $isTrueArray = false;
        if ($resultSet instanceof \think\Model){
            $ret = $resultSet->toArray();
        }else if (is_array($resultSet)){
            foreach ($resultSet as $model){
                if ($model instanceof \think\Model){
                    $ret[] = $model->toArray();
                }else{
                    $isTrueArray = true;
                    break;
                }
            }
        }
        if ($isTrueArray){
            $ret = $resultSet;
        }
        return $ret;
    }

    /**
     * 借助URL编码中转对中文转URL编码
     */
    private static function ch_urlencode($data)
    {
        if (is_array($data) || is_object($data)) {
            foreach ($data as $k => $v) {
                if (is_scalar($v)) {
                    if (is_array($data)) {
                        $data[$k] = urlencode($v);
                    } else if (is_object($data)) {
                        $data->$k = urlencode($v);
                    }
                } elseif (is_array($data)) {
                    if (empty($v)) {
                        $data[$k] = $v;
                    } else {
                        $data[$k] = self::ch_urlencode($v);
                    }
                } elseif (is_object($data)) {
                    $data->$k = self::ch_urlencode($v);
                }
            }
        }
        return $data;
    }

    /**\@description 获取客户端IP
     * @return string|null
     */
    public static function get_client_ip(){
        $IP = null;
        if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
            $IP = getenv('HTTP_CLIENT_IP');
        }elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
            $IP = getenv('HTTP_X_FORWARDED_FOR');
        }elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
            $IP = getenv('REMOTE_ADDR');
        }elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
            $IP = $_SERVER['REMOTE_ADDR'];
        }
        return $IP;
    }

    /**
     * @description 解析编码成中文 配合函数 unicode2utf8 使用
     * @param $s
     * @return mixed
     */
    public static function uni_decode($s) {
        preg_match_all('/\&\#([0-9]{2,5})\;/', $s, $html_uni);
        preg_match_all('/[\\\%]u([0-9a-f]{4})/ie', $s, $js_uni);
        $source = array_merge($html_uni[0], $js_uni[0]);
        $js = array();
        for($i=0;$i<count($js_uni[1]);$i++) {
            $js[] = hexdec($js_uni[1][$i]);
        }
        $utf8 = array_merge($html_uni[1], $js);
        $code = $s;
        for($j=0;$j<count($utf8);$j++) {
            $code = str_replace($source[$j], self::unicode2utf8($utf8[$j]), $code);
        }
        return $code;//$s;//preg_replace('/\\\u([0-9a-f]{4})/ie', "chr(hexdec('\\1'))",  $s);
    }

    /**
     * @description 解析编码成中文 配合函数 uni_decode 使用
     * @param $c
     * @return string
     */
    private static function unicode2utf8($c) {
        $str="";
        if ($c < 0x80) {
            $str.=chr($c);
        } else if ($c < 0x800) {
            $str.=chr(0xc0 | $c>>6);
            $str.=chr(0x80 | $c & 0x3f);
        } else if ($c < 0x10000) {
            $str.=chr(0xe0 | $c>>12);
            $str.=chr(0x80 | $c>>6 & 0x3f);
            $str.=chr(0x80 | $c & 0x3f);
        } else if ($c < 0x200000) {
            $str.=chr(0xf0 | $c>>18);
            $str.=chr(0x80 | $c>>12 & 0x3f);
            $str.=chr(0x80 | $c>>6 & 0x3f);
            $str.=chr(0x80 | $c & 0x3f);
        }
        return $str;
    }

    /**
     * @description 随机中文
     * @param int $length
     * @return string
     */
    public static function getZhCode($length = 4)
    {
        // 生成随机中文
        $zhSet = '们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借';
        $code = '';
        $length = intval($length);
        if ($length<1){
            $length = 4;
        }
        for ($i = 0; $i < $length; $i++) {
            $code .= iconv_substr($zhSet, floor(mt_rand(0, mb_strlen($zhSet, 'utf-8') - 1)), 1, 'utf-8');
        }
        return $code;
    }

    /**
     * 将中文编码成拼音
     * @param string $utf8Data utf8字符集数据
     * @param string $sRetFormat 返回格式 [head:首字母|all:全拼音]
     * @return string
     */
    public static function getZhPinYin($utf8Data, $sRetFormat = 'all'){
        return \app\common\components\ChineseToPinyin::encode($utf8Data,$sRetFormat);
    }
}