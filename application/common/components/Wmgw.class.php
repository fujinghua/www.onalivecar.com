<?php
class Wmgw extends Think {
	var $method;
	var $cookie;
	var $post;
	var $header;
	var $ContentType;
	var $errno;
	var $errstr;
	function __construct() {
		$this->method = 'GET';
		$this->cookie = '';
		$this->post = '';
		$this->header = '';
		$this->errno = 0;
		$this->errstr = '';
    }
	/**
	 * @author:xiandong
	 * @刘斌企业短信通
	 * 
	 * @parm	string	$tel      发送号码
	 * @parm	string	$content  发送内容
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @parm    string  $date     定时时间(时间戳)
	 * @return  string  $str      成功数|失败数
	 **/
	public function liubinSend($tel,$content,$pass,$username,$date) {

		$num=0;$errNum=0;
		$tel=explode(",",$tel);
		$content= iconv ( "utf-8", "gbk", $content );
		$ct=count($tel);
		$nt=intval($ct/50);		
		if($date){
			$atDate=date("YmdHis",$date);
		}else{
			$atDate=date("YmdHis",time());
		}
		$a=$ct%50;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 50);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(",",$ac[$i]);
				$n = count($ac[$i]);
				$value=$this->GetSMS("http://terry800.gicp.net:8088/sendsms.asp?us_name=$username&us_pwd=$pass&send_phone=".$tel_s."&send_content=".$content."&send_time=".$atDate);
				if(strpos($value,"|")){
					$u=explode("|",$value);
					$k = $u[1]+$u[2];
					if(($u[0] == 0) && $k){
						$num+=$u[1];
						$errNum+=$u[2];
					}else{
						$errNum+=$n;
					}
			    }
				file_put_contents("sendlb.php",$num."|".$errNum);
			}
		}else{
			$tel_s=implode(",",$tel);
			$value=$this->GetSMS("http://terry800.gicp.net:8088/sendsms.asp?us_name=$username&us_pwd=$pass&send_phone=".$tel_s."&send_content=".$content);
			if(strpos($value,"|")){
				$u=explode("|",$value);
				$k = $u[1]+$u[2];
				if(($u[0] == 0) && $k){
					$num = $u[1];
					$errNum = $u[2];
				}else{
					$errNum = $ct;
				}
			}
		}
		$str = $num ."|". $errNum;
		return($str);
	}
	/**
	 * @author:xiandong
	 * @刘斌短信余额查询
	 * 
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      剩余短信条数
	 **/
	public function liubinCount($pass,$username){
		$url="http://terry800.gicp.net:8088/Get_Account_Balance.asp?us_name=$username&us_pwd=$pass";
		$xml=$this->GetSMS($url);
		$str = $xml."条";
		return $str;
	}

	/**
	 * @author:xiandong
	 * @清远企业短信
	 * 
	 * @parm	string	$tel      发送号码
	 * @parm	string	$content  发送内容
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @parm    int     $userid   用户ID
	 * @parm    string  $date     定时时间(时间戳)
	 * @return  string  $str      成功条数|失败条数
	 **/
   public function qingyuanSend($tel,$content,$pass,$username,$userid,$date='') {
		$num=0;
		$errNum=0;
		$uid = $userid;
        $userName=$username;
		$userPass=$pass;
		$strMsg=$content;
		if($date){
			$AtDate=date("Y-m-d H:i:s",$date);
		}
		$tel=explode(",",$tel);
		$ct=count($tel);
        $nt=intval($ct/1000);
		$a=$ct%1000;
		if($a!=0){
          $nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 1000);
			for($i=0;$i<count($ac);$i++){
			    $tel_s=implode(",",$ac[$i]);
				$n = count($ac[$i]);
			    $client = new SoapClient ( 'http://61.143.160.139:8080/smssoap?WSDL' );
				$obj=$client->SendMessage($uid,$userName,$userPass,$strMsg,$tel_s,$AtDate);
				//$value = $obj->State."|".$obj->Groupid."|".$obj->Errmsg;
				if($obj->State>0){
					$num += $n;
				}else{
					$errNum += $n;
				}
			}
		 }else{
			$tel_s=implode(",",$tel);
			$client = new SoapClient ( 'http://61.143.160.139:8080/smssoap?WSDL' );
			$obj=$client->SendMessage($uid,$userName,$userPass,$strMsg,$tel_s,$AtDate);
			if($obj->State>0){
				$num = $ct;
			}else{
				$errNum = $ct;
			}
		 }
		 $str = $num."|".$errNum;
		 return $str;
	}

	/**
	 * @author:xiandong
	 * @清远企业短信余额查询
	 * 
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @parm    int     $userid   用户ID
	 * @return  string  $str      剩余短信条数
	 **/
	public function qingyuanCount($pass,$username,$userid){
		$client = new SoapClient ( 'http://61.143.160.139:8080/smssoap?WSDL' );
		$value=$client->GetBalance($userid,$username,$pass);
		$str = $value->Balance."条";
		return $str;
	}

	/**
	 * @author:xiandong
	 * @济南博今科技有限公司
	 * 
	 * @parm	string	$tel      发送号码
	 * @parm	string	$content  发送内容
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      成功条数|失败条数
	 **/
    public function jinanbojinSend($tel,$content,$pass,$username){
		$num=0;$errNum=0;
		$size=50;
		$commandID=3;
		$username=$username;
		$password=$pass;
		$needReport=0;
		$digiH2F="true";
		$plit="false";
		$content=iconv("utf-8","gbk",$content);
		$tel=explode(",",$tel);
		$ct=count($tel);
		$nt=intval($ct/$size);
		$a=$ct%$size;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, $size);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(",",$ac[$i]);
				$n = count($ac[$i]);
				$data=array(
					"commandID"=>$commandID,
					"username"=>$username,
					"password"=>$password,
					"mobile"=>$tel_s,
					"content"=>$content,
					"needRepost"=>$needRepost,
					"digiH2F"=>$digiH2F,
					"split"=>$plit
				);
				$re=$this->postSMS("http://61.156.38.47:8080/CPDXT/SendSms",$data);
				if(strpos ($re, "return=0") !== false ){
					$num += $n;
				}else{
					$errNum += $n;
				}
			}
		}else{
			$tel_s=implode(",",$tel);
			$data=array(
			"commandID"=>$commandID,
			"username"=>$username,
			"password"=>$password,
			"mobile"=>$tel_s,
			"content"=>$content,
			"needRepost"=>$needRepost,
			"digiH2F"=>$digiH2F,
			"split"=>$plit
			);
			$re=$this->postSMS("http://61.156.38.47:8080/CPDXT/SendSms",$data); 
			if(strpos($re, "return=0") !== false ){
				$num = $ct;
			}else{
				$errNum = $ct;
			}
		}
		$str = $num ."|". $errNum;
		return($str);
	}
	/**
	 * @author:xiandong
	 * @济南博今科技有限公司余额
	 * 
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      剩余钱
	 **/
    public function jinanbojinCount($pass,$username){
		$commandID=2;
		$username=$username;
		$password=$pass;
		$data=array(
			"commandID"=>$commandID,
			"username"=>$username,
			"password"=>$password
		);
		$value=$this->postSMS("http://61.156.38.47:8080/CPDXT/SendSms",$data);
		$num=explode(";",$value);
		if(is_array($num)){
			$str=str_replace("money=","",$num[1])."元";
		}else{
			$str=$num;
		}
		return $str;
    }

	/**
	 * @author:xiandong
	 * @王军0278网关
	 * 
	 * @parm	string	$tel      发送号码
	 * @parm	string	$content  发送内容
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      成功条数|失败条数
	 **/
    public function wangjunSend($tel,$content,$pass,$username){
		$num=0;$errNum=0;
		$size=10000;
		$password=iconv("utf-8","gb2312",$pass);
		$username=iconv("utf-8","gb2312",$username);
		$content=iconv("utf-8","gb2312",$content);
		$tel=explode(",",$tel);
		$ct=count($tel);
		$nt=intval($ct/$size);
		$a=$ct%$size;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, $size);
			for($i=0;$i<count($ac);$i++){
			    $tel_s=implode("|",$ac[$i]);
				$n = count($ac[$i]);
				$data=array(
					"userName"=>$username,
					"Password"=>$password,
					"sms"=>$content,
					"tel"=>$tel_s
				);
				$obj=$this->postSMS("http://218.242.44.52:8888/sendsms.aspx",$data);
				if($obj>=0){
					$num += $n;
				}else{
					$errNum += $n;
				}
				file_put_contents("sendwj.php",$num."|".$errNum);
			}
		 }else{
			$tel_s=implode("|",$tel);
			$data=array(
				"userName"=>$username,
				"Password"=>$password,
				"sms"=>$content,
				"tel"=>$tel_s
			);
			$obj=$this->postSMS("http://218.242.44.52:8888/sendsms.aspx",$data);
			if($obj>=0){
				$num = $ct;
			}else{
				$errNum = $ct;
			}
		 }
		 $str = $num."|".$errNum;
		 return($str);
	}
	/**
	 * @author:xiandong
	 * @王军0278网关余额
	 * 
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      剩余条数
	 **/
    public function wangjunCount($pass,$username){
		$password=iconv("utf-8","gb2312",$pass);
		$username=iconv("utf-8","gb2312",$username);
		$data=array(
			"userName"=>$username,
			"Password"=>$password
		);
		$value=$this->postSMS("http://218.242.44.52:8888/userinfo.aspx",$data);
		if((int)$value>=0){
			$str=(int)$value."条";
		}else{
			$str="查询信息存在问题";
		}
		return $str;
    }

	/**
	 * @author:xiandong
	 * @秋秋商信通接口网关
	 * 
	 * @parm	string	$tel      发送号码
	 * @parm	string	$content  发送内容
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      成功条数|失败条数
	 **/
	public function shangxintongSend($tel,$content,$pass,$username) {
		$num=0;$errNum=0;
		$tel=explode(",",$tel);
		$channel=iconv("utf-8","gbk",'中山电信');
		$username=iconv("utf-8","gbk",$username);
		$password=iconv("utf-8","gbk",$pass);
		$content=iconv("utf-8","gbk",$content);
		$ct=count($tel);
		$nt=intval($ct/100);		
		$a=$ct%100;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 100);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(";",$ac[$i]);
				$n = count($ac[$i]);
				$re=$this->GetSMS("http://211.154.151.238:8070/SendMess.aspx?user=$username&pwd=$pass&channel=$channel&phonelist=".$tel_s."&cont=".$content);
				$xml = simplexml_load_string($re);
				if($xml->status == 1){
					$num+=$n;
				}else{
					$errNum+=$n;
				}
				file_put_contents("sendqq.php",$num."|".$errNum);
			}
		}else{
			$tel_s=implode(";",$tel);
			$re=$this->GetSMS("http://211.154.151.238:8070/SendMess.aspx?user=$username&pwd=$pass&channel=$channel&phonelist=".$tel_s."&cont=".$content);
			$xml = simplexml_load_string($re);
			if($xml->status == 1){
				$num+=$ct;
			}else{
				$errNum+=$ct;
			}
		}
		$str = $num ."|". $errNum;
		return($str);
	}
	/**
	 * @author:xiandong
	 * @秋秋商信通余额查询
	 * 
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      剩余短信条数
	 **/
	public function shangxintongCount($pass,$username){
		$channel=iconv("utf-8","gbk",'中山电信');
		$username=iconv("utf-8","gbk",$username);
		$password=iconv("utf-8","gbk",$pass);
		$url="http://211.154.151.238:8070/GetUserCount.aspx?user=$username&pwd=$password&channel=$channel";
		//print_r($url);exit;
		$xml=$this->GetSMS($url);
		$xml=iconv("gbk","utf-8",$xml);
		$str = $xml."条";
		return $str;
	}

	/**
	 * @author:xiandong
	 * @卢王平企信通
	 * 
	 * @parm	string	$tel      发送号码
	 * @parm	string	$content  发送内容
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      成功数|失败数
	 **/
	public function luwangpingSend($tel,$content,$pass,$username) {
		$num=0;$errNum=0;
		$tel=explode(",",$tel);
		$content= urlencode(iconv ( "utf-8", "gb2312", $content ));
		$ct=count($tel);
		$nt=intval($ct/10000);		
		if($date){
			$atDate=date("YmdHis",$date);
		}
		$a=$ct%10000;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 10000);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(",",$ac[$i]);
				$n = count($ac[$i]);
				$value=$this->GetSMS("http://ent.dxqf518.cn:8085/ent/sendsms.jsp?srcmobile=$username&password=$pass&objmobile=".$tel_s."&smstext=".$content."&SendType=1");
				if($value==1){
					$num+=$n;
			    }else{
					$errNum += $n;
				}
			}
		}else{
			$tel_s=implode(",",$tel);
			$value=$this->GetSMS("http://ent.dxqf518.cn:8085/ent/sendsms.jsp?srcmobile=$username&password=$pass&objmobile=".$tel_s."&smstext=".$content."&SendType=1");
			if($value==1){
				$num=$ct;
			}else{
				$errNum=$ct;
			}
		}
		$str = $num ."|". $errNum;
		return($str);
	}
	/**
	 * @author:xiandong
	 * @卢王平短信余额查询
	 * 
	 * @parm    string  $pass     用户密码
	 * @parm    string  $username 用户名
	 * @return  string  $str      剩余短信条数
	 **/
	public function luwangpingCount($pass,$username){
		$url="http://ent.dxqf518.cn:8085/ent/getmoney.jsp?srcmobile=$username&password=$pass";
		$xml=$this->GetSMS($url);
		$xml = explode(':',$xml);
		$str = $xml[1]."条";
		return $str;
	}


	//默认网关发信
	public function sendfunc($tel,$msg,$pass,$userId) {
		$tela = explode ( ",", $tel );
		$iMobiCount = count ( $tela );
		$params = array ("userId" => $userId, "password" => $pass, "pszMobis" => $tel, "pszMsg" => $msg, "iMobiCount" => $iMobiCount );
		$client = new SoapClient ( 'http://webs.montnets.com/yds/wmgw.asmx?WSDL' );
		try {
			$result = $client->MongateCsSendSmsEx ( $params );
			return ($result->MongateCsSendSmsExResult);
		} catch ( SoapFault $e ) {
			print_r ( $e->getMessage () );
			exit ();
		}
	}
	
	//默认网关余额查询
	public function get_account($pass,$username){
		$params = array ("userId" => $username, "password" => $pass );
		$client = new SoapClient ( 'http://113.128.141.62:5858/SendWebService.asmx?WSDL' );
		try {
			$result=$client->MongateQueryBalance($params);
			echo($result->MongateQueryBalanceResult."条");
		}catch(SoapFault $e){
			print_r ( $e->getMessage () );
			exit ();
		}
	}

	//北京易通---1--一次最多50
	public function duanxin360($tel,$content,$pass,$username) {
		//$content=iconv("utf-8","gbk",$content);
		$tel=explode(",",$tel);
		$ct=count($tel);
		$nt=intval($ct/50);
		$a=$ct%50;
		if($a!=0){
			$nt=$nt+1;
		}else{
			$nt=1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 50);
			for($i=0;$i<=count($ac);$i++){
				$tel_s=implode(",",$ac[$i]);
				$value =$this->GetSMS ( "http://www.duanxin360.com/msgsend.ashx?USERNAME=$username&PASSWORD=$pass&MOBILE=".$tel_s."&CONTENT=".$content."&SEQ=1" ); 
			}
		}else{
			$tel_s=implode(",",$tel);
			$value =$this->GetSMS ( "http://www.duanxin360.com/msgsend.ashx?USERNAME=$username&PASSWORD=$pass&MOBILE=".$tel_s."&CONTENT=".$content."&SEQ=1" ); 
		}	     
		//发送
		return($value);
	}

	//中国企业短信通------3
	public function qxt520($tel,$content,$pass,$username) {
		$tel=explode(",",$tel);
		$content= iconv ( "utf-8", "gbk", $content );
		$zh=iconv("utf-8","gbk",$username);
		$mm=iconv("utf-8","gbk",$pass);
		$ct=count($tel);
		$nt=intval($ct/50);
		$a=$ct%50;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 50);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(",",$ac[$i]);
				$tel_s=iconv ( "utf-8", "gbk", $tel_s);
				$value=$this->GetSMS("http://sms.qxt520.com/smsComputer/smsComputersend.asp?zh=".$zh."&mm=".$mm."&hm=".$tel_s."&nr=".$content."&dxlbid=3");
			}
		}else{
			$tel_s=implode(",",$tel);
			$tel_s=iconv ( "utf-8", "gbk", $tel_s);
			$value=$this->GetSMS("http://sms.qxt520.com/smsComputer/smsComputersend.asp?zh=".$zh."&mm=".$mm."&hm=".$tel_s."&nr=".$content."&dxlbid=3");
		}
		return($value);
	}
	//广州联迅信息科技有限公司------3
	public function lianxun($tel,$conten,$pass,$username) {
		$content=iconv("utf-8","gbk",$content);
		$content=str_replace("•",".",$content);
		$tel=explode(",",$tel);
		$pass=md5($pass);
		$ct=count($tel);
		$nt=intval($ct/50);
		$a=$ct%50;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 50);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(";",$ac[$i]);
				$value=$this->GetSMS("http://211.155.25.158:8080/LSBsms/smsInterface.do?method=sendsms&name=$username&password=".$pass."&troughid=&priorityid=&timing=&mobile=".$tel_s."&content=".$content."&splitsuffix=1");
			}
		}else{
			$tel_s=implode(";",$tel);
			$value=$this->GetSMS("http://211.155.25.158:8080/LSBsms/smsInterface.do?method=sendsms&name=$username&password=".$pass."&troughid=&priorityid=&timing=&mobile=".$tel_s."&content=".$content."&splitsuffix=1");
		}
		return($value);
	}
    //广州联迅信息科技有限公司余额
	public function lianxunyuer($pass,$username){
		$match="/<count>(.*?)<\/count>/i";
		$pass=md5($pass);
		$url="http://211.155.25.158:8080/LSBsms/smsInterface.do?method=remaincount&name=$username&password=".$pass;
		$xml=$this->GetSMS($url);
		preg_match($match,$xml,$matches);
		echo($matches[1][0]."点数");
	}
	//北京百悟科技------3
	public function beijingbaiwu($tel,$content,$pass,$username) {
		$md5Pass = md5($pass);
		$tel=explode(",",$tel);
		$ct=count($tel);
		$nt=intval($ct/50);
		$a=$ct%50;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 50);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(";",$ac[$i]);
				$value=$this->GetSMS("http://218.241.153.202:8080/post_sms.do?id=$username&MD5_td_code=".$md5Pass."&mobile=".$tel_s."&msg_content=".$content."&mesg_id=&extend=");
			}
		}else{
			$tel_s=implode(";",$tel);
			$value=$this->GetSMS("http://218.241.153.202:8080/post_sms.do?id=$username&MD5_td_code=".$md5Pass."&mobile=".$tel_s."&msg_content=".$content."&mesg_id=&extend=");
		}
		return($value);
	}
    //北京百悟科技余额查询
   public function beijingbaiwukejiyuer($pass,$username){
		$md5Pass = md5($pass);
		$url="http://218.241.153.202:8080/get_balance.do";
		$value=$this->GetSMS($url."?id=$username&pwd=".$md5Pass);
		echo($value."元");
	  
   }
	//广州海翼信息技术有限公司
   public function guangzhouhaiyi($tel,$content,$pass,$username) {
		$num="";
		$strLoginCode=$username;
		$strPass=$pass;
		$strMsg=$content;
		$bConfirm="1";
		$AtDate=date("Y-m-d");
		$tel=explode(",",$tel);
		$ct=count($tel);
		$nt=intval($ct/1000);
		$a=$ct%50;
		if($a!=0){
			$nt=$nt+1;
		}
		if($nt>1){
			$ac=array_chunk($tel, 1000);
			for($i=0;$i<count($ac);$i++){
				$tel_s=implode(";",$ac[$i]);
				$client = new SoapClient ( 'http://113.106.110.118:9501/WebService/EntInterface.asmx?WSDL' );
				try {
					$obj=$client->SendMessage($strLoginCode,$strPass,$num,$tel_s,$strMsg,$bConfirm,$AtDate);
				} catch ( SoapFault $e ) {
					// print_r ( $e->getMessage () );
					//exit ();
				}
			}
		}else{
			$tel_s=implode(";",$tel);
			$client = new SoapClient ( 'http://113.106.110.118:9501/WebService/EntInterface.asmx?WSDL' );
			try {
				//print_r($client->__getFunctions());
				//print_r($client->__getTypes());
				$obj=$client->SendMessage($strLoginCode,$strPass,$num,$tel_s,$strMsg,$bConfirm,$AtDate);
			} catch ( SoapFault $e ) {
				// print_r ( $e->getMessage () );
				// exit ();
			}	
		}
		return $obj;
	}

	/*******************************************************/
	//模拟提交
	public function formsubmit($argv, $posturl, $host, $post, $method) {
		
		foreach ( $argv as $key => $value ) {
			$params [] = $key . '=' . $value;
		}
		$params = implode ( '&', $params );
		
		if ($method == "POST") {
			$header = $method . " " . $posturl . " HTTP/1.1\r\n";
		} else {
			$header = $method . " " . $posturl . "?" . $params . " HTTP/1.0\r\n";
		}
		if ($method == "POST") {
			$header .= "Accept:*/*\r\n";
			$header .= "Host:" . $host . "\r\n";
			
			$header .= "Content-Type: application/x-www-form-urlencoded\r\n";
			
			$header .= "Accept-Language:   zh-cn\r\n ";
			$header .= "Content-Length: " . strlen ( $params ) . "\r\n";
			$header .= "Connection: Close\r\n";
			$header .= $params;
		}
		
		$fp = @fsockopen ( $host, $post, $errno, $errstr, 30 );
		if (! $fp) {
			echo "$errstr ($errno)<br />\n";
		} else {
			stream_set_blocking($fp, true);
			stream_set_timeout($fp, 2);
			fwrite ( $fp, $header );
			$status = stream_get_meta_data($fp);
			if(!$status['timed_out']) {
			  while ( ! feof ( $fp ) ) {
				  echo fgets ( $fp );
			  }
			}
		
		}
	
	}
	//get方法
	function GetSMS($url) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url ); //设置访问的url地址
		//curl_setopt($ch,CURLOPT_HEADER,1);                //是否显示头部信息
		curl_setopt ( $ch, CURLOPT_TIMEOUT, 5 ); //设置超时
		curl_setopt ( $ch, CURLOPT_USERAGENT, _USERAGENT_ ); //用户访问代理 User-Agent
		curl_setopt ( $ch, CURLOPT_REFERER, _REFERER_ ); //设置 referer
		curl_setopt ( $ch, CURLOPT_FOLLOWLOCATION, 1 ); //跟踪301
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 ); //返回结果
		$r = curl_exec ( $ch );
		curl_close ( $ch );
		return $r;
	
	}

	//request
   function post($url, $data = array(), $referer = '', $limit = 0, $timeout = 30, $block = TRUE) {
		$this->method = 'POST';
		$this->ContentType = "Content-Type: application/x-www-form-urlencoded\r\n";
		if($data) {
			$post = '';
			foreach($data as $k=>$v) {
				$post .= $k.'='.rawurlencode($v).'&';
			}
			$this->post .= substr($post, 0, -1);
		}
		return $this->request($url, $referer, $limit, $timeout, $block);
	}

	//request
	public function postSMS($url,$data='')
	{
		$row = parse_url($url);
		$host = $row['host'];
		$port = $row['port'] ? $row['port']:8080;
		$file = $row['path'];
		while (list($k,$v) = each($data)) 
		{
			$post .= rawurlencode($k)."=".rawurlencode($v)."&";	//转URL标准码
		}
		$post = substr( $post , 0 , -1 );
		$len = strlen($post);
		$fp = fsockopen( $host ,$port, $errno, $errstr, 10);
		if (!$fp) {
			return "$errstr ($errno)\n";
		} else {
			$receive = '';
			$out = "POST $file HTTP/1.1\r\n";
			$out .= "Host: $host\r\n";
			$out .= "Content-type: application/x-www-form-urlencoded\r\n";
			$out .= "Connection: Close\r\n";
			$out .= "Content-Length: $len\r\n\r\n";
			$out .= $post;	
			//stream_set_blocking($fp, true);
			//stream_set_timeout($fp, 2);
			fwrite($fp, $out);
			//$status = stream_get_meta_data($fp);
			//if(!$status['timed_out']) {
			  while (!feof($fp)) {
				 $receive .= fgets($fp, 128);
			  }
			//}
			fclose($fp);
			$receive = explode("\r\n\r\n",$receive);
			unset($receive[0]);
			return implode("",$receive);
		}
	}

}
?>