<?php
    ini_set("session.cache_expire",1800);
    header("Progma:no-cache");
    header("Cache-Control:no-cache");
    session_name("loginfo");
    session_start();
   // $svalue = 9999;

    isset($_REQUEST['svalue']) && $svalue = $_REQUEST['svalue'];
    
    if($svalue ){
      setCookie('loginfo', '', time()-2000, '/');
      $_SESSION = array();
      $id = ""; $password = "";
      session_unset();
      session_destroy();
      $jb_login = false;
    }
    else {
      if(isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $password = $_SESSION['password'];
        $jb_login = true;
      }
      else if(isset( $_REQUEST[ 'id' ]) && isset( $_REQUEST[ 'password' ])) { 
        $id = $_REQUEST[ 'id' ];
        $password = $_REQUEST[ 'password' ];
        $_SESSION['id'] = $id;
        $_SESSION['password'] = $password;
        $jb_login = true;
      }
      
      if($jb_login){
        $dbid = sprintf("db%s", $id);
        $sqlid = sprintf("%s", $id);
        try{
		$conn = mysqli_connect("localhost", $sqlid, $password, $dbid);
		//throw new Exception("DB login failed", 1);
        }
        catch(Exception $e){
          $msg = "아이디 또는 비밀번호를 확인하세요.";
          setCookie('loginfo', '', time()-2000, '/');
          $_SESSION = array();
          session_unset();
          session_destroy();
	  $jb_login = false;
	  //$s = $e->getMessage();
	  //echo $s;
        }
      }
    }
    
?>
