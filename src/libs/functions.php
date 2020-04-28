<?php

use Google\Auth\CacheTrait;

/**
 * Send an error to discord
 *
 * @param string $type
 * @param string $message
 * @return boolean
 */
function SendError($type, $message)
{
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
    $query->execute([":gid" => $gid]);
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
    if (empty($info)) {
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
        $ban = array("banned" => true, "UserInfo" => $info['UserInfo'], "Date" => $info['Date'], "AdminInfo" => $info['AdminInfo'], "Reason" => $info['Reason'], "gid" => $info['gid']);
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
 * Write a secure error to the DB
 *
 * @param string $msg
 * @return string $id
 */
function NewError($msg)
{
    $id = uniqid("E", false);
    $query = SQLWrapper()->prepare("INSERT INTO Errors (ID, Msg, EndStamp) VALUES (?,?,?)");
    $query->execute([$id, $msg, time() + 300]);
    return $id;
}
/**
 * Get an error msg from the db
 *
 * @param string $id
 * @return string msg
 */
function GetError($id)
{
    $query = SQLWrapper()->prepare("SELECT Msg,EndStamp FROM Errors WHERE ID = :id");
    $query->execute([":id" => $id]);
    $errorMSG = $query->fetch();
    if (isset($errorMSG['EndStamp'])) {
        if (time() > $errorMSG['EndStamp']) {
            return null;
        } else {
            return $errorMSG['Msg'];
        }
    } else {
        return NULL;
    }
}
/**
 * Check if a user is an admi
 *
 * @param string $id
 * @return array 
 */
function IsAdmin($id)
{
    $array = array(
        "116367054307199743956",
        "104684477093479828612"
    );
    if (in_array($id, $array)) {
        $admin = array(
            "admin" => true,
            "badge" => '<span  title="Administrator" class="text-center badge badge-admin">Admin</span>',
            "color" => '#0099ff'
        );
        return $admin;
    } else {
        $admin = array(
            "admin" => false,
            "badge" => null
        );
        return $admin;
    }
}
/**
 * Check if a user is  super admin
 *
 * @param string $id
 * @return boolean 
 */
function IsSuperAdmin($id)
{
    $array = array(
        "116367054307199743956",
        "104684477093479828612"
    );
    if (in_array($id, $array)) {
        return true;
    } else {
        return false;
    }
}

/**
 * Check if a user is a verified NSD User from Email
 *
 * @param string $identifier
 * @param boolean $usingemail
 * @return array 
 */
function IsNsd($identifier, $usingemail)
{
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "off" ? "https" : "http";
    $host = $_SERVER['SERVER_NAME'];
    $dir = stripslashes("$protocol://$host" . dirname($_SERVER['PHP_SELF']) . "/");
    $array = array(
        "apps.nsd.org",
        "nsd.org"
    );
    if (!$usingemail) {
        $query = SQLWrapper()->prepare("SELECT Email FROM Users WHERE gid = :gid");
        $query->execute([":gid" => $identifier]);
        $email = $query->fetch()['Email'];
    } else {
        $email = $identifier;
    }
    $domain = substr(strrchr($email, "@"), 1);
    if (in_array($domain, $array)) {
        $array = array(
            "nsd" => true,
            "badge" => '<span  title="Verifed NSD User" class="text-center badge badge-nsd">NSD</span>',
            "color" => '#0099ff'
        );
        return $array;
    } else {
        $array = array(
            "nsd" => false,
            "badge" => null
        );
        return $array;
    }
}
/**
 * Us this function to display a date. Makes it easy to have the same formatting across the site
 *
 * @param string $stamp
 * @return string 
 */
function FormatDate($stamp)
{
    $date = date("n/j/Y g:i A", $stamp);
    return $date;
}
/**
 * Visible Error Alert For  Returning HTML
 * @param string $msg
 */
function AlertError($msg)
{
    $script = '<script> AlertError("' . $msg . '") </script>';
    echo $script;
}
/**
 * Visible Success Alert For Returning HTML
 * @param string $msg
 */
function AlertSuccess($msg)
{
    $script = '<script> AlertSuccess("' . $msg . '") </script>';
    echo $script;
}
/**
 * Set a users restrictions
 *
 * @param array $restrictions
 * @param string $gid
 * @return boolean 
 */
function ApplyRestrictions($restrictions, $gid)
{
    $restrict = json_encode($restrictions);
    try {
        $query = SQLWrapper()->prepare("UPDATE Users SET Restrictions = :res WHERE gid = :gid");
        $query->execute([":res" => $restrict, "gid" => $gid]);
        return true;
    } catch (PDOException $e) {
        SendError("MySQL Error (KMS)", $e->getMessage());
        return false;
    }
}
/**
 * Fetch a users restrictions
 *
 * @param string $gid
 * @return array 
 */
function FetchRestrictions($gid)
{
    $query = SQLWrapper()->prepare("SELECT Restrictions FROM Users WHERE gid = :gid");
    $query->execute(["gid" => $gid]);
    $data = $query->fetch();
    if ($data == null) {
        return null;
    } else {
        if (IsSuperAdmin($gid)) {
            $restrictions  = array(
                "UserNameChange" => false,
                "BioChange" => false,
                "PictureChange" => false
            );
            return $restrictions;
        } else {
            $restrictions = json_decode($data['Restrictions'], true);
            return $restrictions;
        }
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
function UpdateGInfo($name, $picture, $email, $id)
{
    try {
        if (FetchRestrictions($id)['PictureChange']) {
            $query = SQLWrapper()->prepare("UPDATE Users SET RealName = :name, Email = :email WHERE gid = :gid");
            $query->execute([":name" => $name, ":email" => $email, ":gid" => $id]);
        } else {
            $query = SQLWrapper()->prepare("UPDATE Users SET RealName = :name, Picture = :picture, Email = :email WHERE gid = :gid");
            $query->execute([":name" => $name, "picture" => $picture, ":email" => $email, ":gid" => $id]);
        }

        return true;
    } catch (PDOException $e) {
        return false;
        SendError("MySQL Error", $e->getMessage());
    }
}
/**
 * Validate a username
 *
 * @param string $string
 * @return string 
 */
function UserNameValidate($string)
{
    if (IsEmpty($string)) {
        return "You must enter a value for the user name.";
    } else if (strlen($string) > 30) {
        return "Usernames must be less than 30 characters.";
    } else if (strlen($string) < 3) {
        return "Usernames must be greater than 3 characters.";
    } else if (preg_match('[\s]', $string)) {
        return "Spaces are not allowed in usernames.";
    } else if (!UserNameReady($string)) {
        return "Sorry, this username is already taken";
    } else {
        return null;
    }
}
/**
 * Ban a user
 *
 * @param string $gid
 * @param string $AdminID
 * @param string $Reason
 * @return boolean 
 */
function BanUser($gid, $AdminID, $Reason)
{
    if (!IsBanned($gid)['banned']) {
        try {

            $UserInfo = json_encode(UserInfo($gid));
            $AdminInfo = json_encode(UserInfo($AdminID));
            $query = SQLWrapper()->prepare("INSERT INTO Bans (gid,AdminID,Reason,UserInfo,AdminInfo) VALUES (?,?,?,?,?)");
            $query->execute([$gid, $AdminID, $Reason, $UserInfo, $AdminInfo]);
            return true;
        } catch (PDOException $e) {
            return false;
            SendError("MySQL Error", $e->getMessage());
        }
    } else {
        return false;
    }
}
/**
 * u=Unban a user
 *
 * @param string $gid
 * @return boolean 
 */
function UnBanUser($gid)
{
    if (IsBanned($gid)['banned']) {
        try {
            $query = SQLWrapper()->prepare("DELETE FROM Bans WHERE gid = ?");
            $query->execute([$gid]);
            return true;
        } catch (PDOException $e) {
            SendError("MySQL Error", $e->getMessage());
            return false;
        }
    } else {
        return false;
    }
}
function v($var)
{
    return htmlspecialchars($var);
}
function dir_is_empty($dir)
{
    $handle = opendir($dir);
    while (false !== ($entry = readdir($handle))) {
        if ($entry != "." && $entry != "..") {
            closedir($handle);
            return FALSE;
        }
    }
    closedir($handle);
    return TRUE;
}
function delete_directory($dirname)
{
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}
function CanPostImage($gid)
{
    $query = SQLWrapper()->prepare("SELECT postid,Images FROM ImagePost WHERE gid = :gid AND  Title  IS NULL AND  Category  IS NULL AND  Caption  IS NULL");
    $query->execute([":gid" => $gid]);
    $data = $query->fetch();
    if ($data == null) {
        return true;
    } else {
        if ($data['Images'] == null) {
            $del = SQLWrapper()->prepare("DELETE FROM ImagePost WHERE PostID = :id");
            $del->execute([":id" => $data['postid']]);
            return false;
        } else {
            return false;
        }
    }
}
function GetPostOwner($pid)
{
    $query = SQLWrapper()->prepare("SELECT gid FROM ImagePost WHERE PostID = :pid ");
    $query->execute([":pid" => $pid]);
    $data = $query->fetch();
    return $data['gid'];
}
function IsValidPostCategory($string)
{
    $Categories = array("Food", "Music", "Sports", "Gatherings", "Other");
    if (in_array($string, $Categories)) {
        return true;
    } else {
        return false;
    }
}
function DeleteImagePost($pid)
{
    try {
        $query = SQLWrapper()->prepare("DELETE FROM ImagePost WHERE PostID = :pid");
        $query->execute([":pid" => $pid]);
        delete_directory("img/post/$pid");
        return true;
    } catch (PDOException $e) {
        SendError("MySQL Error", $e->getMessage());
        return false;
    }
}
