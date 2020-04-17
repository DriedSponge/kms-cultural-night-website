<?php
/**
 * Send an error to discord
 *
 * @param string $type
 * @param string $message
 * @return boolean
 */
function SendError($type,$message){
    $request = json_encode([
        "content" => "",
        "embeds" => [
            [
                "title" => "System Error (KMS) -  $type",
                "type" => "rich",
                "color" => hexdec("FF0000"),
                "description" =>  $message,
                "timestamp" => date("c"),
            ]
        ]
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

    $ch = curl_init("https://discordapp.com/api/webhooks/695335752988491839/HOhaC8FXEmYr2URVEr1xKyCQMG7CTQ0PvhrQVuUie7tO_ahYO_4Hn6Gfs49ELhlC7HLC");

    curl_setopt_array($ch, [
        CURLOPT_POST => 1,
        CURLOPT_FOLLOWLOCATION => 1,
        CURLOPT_HTTPHEADER => array("Content-type: application/json"),
        CURLOPT_POSTFIELDS => $request,
        CURLOPT_RETURNTRANSFER => 1
    ]);


    curl_exec($ch);
}
/**
 * Check if string is completly blank
 *
 * @param string $string
 * @return boolean
 */
function IsEmpty($string)
{
    if (empty($string) or ctype_space($string)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Get Info About A user From GID
 *
 * @param string $gid
 * @return array
 */
function UserInfo($gid)
{
    $query = SQLWrapper()->prepare("SELECT * FROM Users WHERE gid = :gid");
    $query ->execute([":gid" => $gid]);
    $info = $query->fetch();
    if (!empty($info)) {
        $account = array("exist" => true, "UserName" => $info['Name'], "CreationDate" => $info['CreationDate'], "Picture" => $info['Picture'], "Bio" => $info['Bio'], "RealName" => $info['RealName'], "Email" => $info['Email'], "TOS" => $info['TOS']);
        return $account;
    } else {
        $account = array("exist" => false);
        return $account;
    }
}
/**
 * Check if a user exist in the DB
 *
 * @param string $gid
 * @return boolean
 */
function UserExist($gid)
{
    $query = SQLWrapper()->prepare("SELECT Name FROM Users WHERE gid = ?");
    $query->execute([$gid]);
    $info =  $query->fetch();
    if(empty($info)) {
        return false;
    } else {
        return true;
    }
}
/**
 * Check if a user is banned
 *
 * @param string $gid
 * @return array
 */
function IsBanned($gid)
{
    $query = SQLWrapper()->prepare("SELECT * FROM Bans WHERE gid = :gid");
    $query->execute([":gid" => $gid]);
    $info = $query->fetch();
    if (!empty($info)) {
        $ban = array("banned" => true, "UserInfo" => $info['UserInfo'], "Date" => $info['Date'], "AdminInfo" => $info['AdminInfo'], "Reason" => $info['Reason'],"gid"=>$info['gid']);
        return $ban;
    } else {
        $ban = array("banned" => false);
        return $ban;
    }
}
/**
 * Check if a user name is taken
 *
 * @param string $username
 * @return array
 */
function UserNameReady($username)
{
    $query = SQLWrapper()->prepare("SELECT gid FROM Users WHERE Name = :name");
    $query->execute([":name" => $username]);
    $info = $query->fetch();
    if (empty($info)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Update a users email,photo,realname when they login
 *
 * @param string $name
 * @param string $picture
 * @param string $email
 * @param string $id
 * @return boolean
 */
function UpdateGInfo($name,$picture,$email,$id){
    try{
        $query = SQLWrapper()->prepare("UPDATE Users SET RealName = :name, Picture = :picture, Email = :email WHERE gid = :gid");
        $query->execute([":name"=>$name,"picture"=>$picture,":email"=>$email,":gid"=>$id]);
        return true;
    } catch (PDOException $e){
        return false;
        SendError("MySQL Error",$e->getMessage());
    }
}
?>