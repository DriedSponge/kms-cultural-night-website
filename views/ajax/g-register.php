<?php 
if(isset($_POST['register'])){
    header("Content-type: application/json");
    $Msg = array(
        "success"=>false,
        "SysErr"=> false,
        "Msg"=>"Something went wrong! Please try again later!"
    );
    if(isset($_POST['agree']) && isset($_POST['uname'])){
        if(isset($_SESSION['access_token'])){
            if(!UserExist($_SESSION['gid'])){
                $banned = IsBanned($_SESSION['gid']);
                if($banned['banned']==false){
                    if(IsEmpty($_POST['uname'])){
                       $Msg['unameERR']="You must enter a value for the user name!"; 
                    }else if(strlen($_POST['uname']) > 30){
                        $Msg['unameERR']="Your username must be less than 30 characters!"; 
                    }else if(strlen($_POST['uname']) < 3){
                        $Msg['unameERR']="Your username must be greater than 3 characters!"; 
                    }else if(!UserNameReady($_POST['uname'])){
                       $Msg['unameERR']="Sorry, this username is already taken!"; 
                    }
                }else{
                    $Msg['SysErr'] = true;
                    $Msg['Msg'] = "This account is banned! Reason: ".$banned['Reason'];  
                }
            }else{
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "A user is already registered with that google account!";   
            }
        }else{
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "Invalid Session!";
        }
    }else{
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Invalid post values!";
    }
    die(json_encode($Msg));
}