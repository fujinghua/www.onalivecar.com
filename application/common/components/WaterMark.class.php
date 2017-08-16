<?php
/**
 * 水印
 *
 */
class WaterMark extends Think{
	private $sourceImage; //源图片
	private $waterPos; //水印位置
	private $waterImage; //水印图片
	private $text; //水印文字
	private $textFont; //文字大小
	private $textColor; //文字color
	
	private $imginfo=array();//副本图片信息集
	private $position=array();//水印坐标 x,y

	private $is_waterimg=false;//是否图片水印
	
	private $message=array(); //收集信息
	private $error;//错误信息

	/**
	 *构造：源图片路径,水印坐标，水印图片路径，水印文字，文字大小，文字颜色
	 */
	public function __construct($sourceImage,$waterPos=0,$waterImage="",$text="",$textFont=5,$textColor = "#FF0000") {
		$this->sourceImage = $sourceImage;
		$this->waterPos = $waterPos;
		$this->waterImage = $waterImage;
		$this->waterText = $text;
		$this->textFont = $textFont;
		$this->textColor = $textColor;
	}
	/**
	 * 标记
	 */
	public function mark(){
		$this->get_sourceimg();
		$this->get_waterimg();
		$this->get_position();
		$this->create_img();
		$this->get_error();
		if($this->error) return false;
		
		return true;
	}
	/**
	 * 从源图片创建一个图片副本
	 */
	private function get_sourceimg() {
		//读取源图片
		if (!empty($this->sourceImage) && file_exists($this->sourceImage)) {
			$this->imginfo['source'] = getimagesize($this->sourceImage);//源图片信息
			$this->imginfo['s_img_w'] = $this->imginfo['source'][0]; //源图片的宽
			$this->imginfo['s_img_h'] = $this->imginfo['source'][1]; //源景图片的高
			$info="";
			switch ($this->imginfo['source'][2]){ //通过源图片的格式创建源图的副本
				case 1 :
					$info = imagecreatefromgif($this->sourceImage);
					break;
				case 2 :
					$info = imagecreatefromjpeg($this->sourceImage);
					break;
				case 3 :
					$info = imagecreatefrompng($this->sourceImage);
					break;
				default :
					return $this->message['s_notin']=TRUE;
			}
			$this->imginfo['s_img']=$info;//源图副本信息
			//$this->message['map'].="图片副本->".$this->imginfo['s_img']."<br>";
		}else {
			return $this->message['s_undefined']=TRUE;
		}
	}
	
	/**
	 * 从水印图片创建一个水印副本
	 */
	private function get_waterimg() {
		if (!empty ($this->waterImage) && file_exists($this->waterImage)) { //判断图片文件是否存在
			$this->is_waterimg = TRUE;
			$this->imginfo['water']= getimagesize($this->waterImage);//水印图片信息
			$this->imginfo['w_img_w'] = $this->imginfo['water'][0]; //取得水印图片的宽
			$this->imginfo['w_img_h'] = $this->imginfo['water'][1]; //取得水印图片的高
			switch ($this->imginfo['water'][2]) {//通过水印图片的格式 创建水印的副本
				case 1 :
					$info = imagecreatefromgif($this->waterImage);
					break;
				case 2 :
					$info = imagecreatefromjpeg($this->waterImage);
					break;
				case 3 :
					$info = imagecreatefrompng($this->waterImage);
					break;
				default :
					return $this->message['w_notin']=TRUE;
			}
			$this->imginfo['w_img']=$info;//水印副本信息
		}else{
		//	return $this->message['w_undefined']=TRUE;
		}
		//$this->message['map'].="水印副本->";
	}
		
	/**
	 * 获取水印坐标
	 */
	private function get_position() {
		//水印位置
		if ($this->is_waterimg) {//图片水印
			$w = $this->imginfo['w_img_w'];
			$h = $this->imginfo['w_img_h'];
		}else { //文字水印
			$temp = imagettfbbox(ceil($this->textFont * 2.5), 0, "ublic/static/images/simhei.ttf", $this->waterText); //取得使用 TrueType 字体的文本的范围
			$w = $temp[2] - $temp[6];
			$h = $temp[3] - $temp[7];
			unset($temp);
		}
		if (($this->imginfo['s_img_w'] < $w) || ($this->imginfo['s_img_h'] < $h)) {
			return $this->message['s_nosize']=TRUE;
		}
		$source_w=$this->imginfo['s_img_w'];
		$source_h=$this->imginfo['s_img_h'];
		//坐标按下标顺序依次为：随机,为顶端居左,为顶端居中,为顶端居右,为中部居左,为中部居中,为中部居右,为底端居左,为底端居中,为底端居右
		$the_x=array(rand(0,$source_w-$w),0,($source_w-$w)/2,$source_w-$w,0,($source_w-$w)/2,$source_w-$w,0,($source_w-$w)/2,$source_w-$w-10,);
		$the_y=array(rand(0,$source_h-$h),0,0,0,($source_h-$h)/2,($source_h-$h)/2,($source_h-$h)/2,$source_h-$h,$source_h-$h,$source_h-$h-10,);
	
		//水印位置
		$this->position['x']=$the_x[$this->waterPos];
		$this->position['y']=$the_y[$this->waterPos];
	}
	/**
	 * 创建图片
	 */
	private function create_img(){
		//设定图像的混色模式
		imagealphablending($this->imginfo['s_img'], true);
		if($this->is_waterimg){//图片水印
		   imagecopy($this->imginfo['s_img'], $this->imginfo['w_img'],$this->position['x'],$this->position['y'], 0, 0, $this->imginfo['w_img_w'],$this->imginfo['w_img_h']);//拷贝水印到目标文件
		}else{//文字水印
		  if( !empty($this->textColor) && (strlen($this->textColor)==7) ){
			  $R = hexdec(substr($this->textColor,1,2));
			  $G = hexdec(substr($this->textColor,3,2));
			  $B = hexdec(substr($this->textColor,5));
		  }else{
			  return $this->message['no_textcolor']=TRUE;
		  }
//		  imagestring($this->imginfo['s_img'], $this->textFont, $this->position['x'], $this->position['y'], $this->waterText, imagecolorallocate($this->imginfo['s_img'], $R, $G, $B));
		  imagettftext($this->imginfo['s_img'],10,0,0,10,imagecolorallocate($this->imginfo['s_img'], $R, $G, $B),"Public/static/images/simhei.ttf",$this->waterText);
		} 
		 //生成水印后的图片
		 @unlink($this->sourceImage);  /*******删除源图片******/
		 switch($this->imginfo['source'][2])//取得背景图片的格式
		 {
			 case 1:
				 imagegif($this->imginfo['s_img'],$this->sourceImage);
				 break;
			 case 2:
				 imagejpeg($this->imginfo['s_img'],$this->sourceImage,100);
				 break;
			 case 3:
				 imagepng($this->imginfo['s_img'],$this->sourceImage);
				 break;
			 default:
			 	$this->message['notin']=TRUE;
		 }
		 //$this->message['map'].="创建图片->";
	}
	/**
	 * 提取异常
	 */
	public function get_error(){
		$error='';
		if($this->message['s_notin']){
			$error.="源图副本：源图片暂只支持GIF、JPG、PNG格式|";
		}
		if($this->message['w_notin']){
			$error.="水印副本：水印图片暂只支持GIF、JPG、PNG格式|";
		}
		if($this->message['s_undefined']){
			$error.="源图片不存在|";
		}
		if($this->message['w_undefined']){
			$error.="水印图片不存在|";
		}
		if($this->message['s_nosize']){
			$error.="源图片宽度不够,水印压制失败|";
		}
		if($this->message['notin']){
			$error.="生成图片：源图片暂只支持GIF、JPG、PNG格式|";
		}
		if($this->message['no_textcolor']){
			$error.="生成图片：水印文字颜色格式不正确|";
		}
		
		return $this->error=$error;
	}
	
	
	public function show(){
		echo "<br>路径[".$this->sourceImage."]位置".$this->waterPos."水印".$this->waterImage."".$this->waterText."".$this->textFont."".$this->textColor;
		//echo $this->message['map']."<br>".$this->waterImage;
	}
}
?>