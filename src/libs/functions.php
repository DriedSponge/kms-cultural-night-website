<?php

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
    $query = SQLWrapper()->prepare("SELECT * FROM Users WHERE gid = :gid")->execute([":gid" => $gid]);
    $info = $query->fetch();
    if (!empty($info)) {
        $account = array("exist" => true, "Name" => $info['Name'], "CreationDate" => $info['CreationDate'], "Picture" => $info['Picture'], "Bio" => $info['Bio'], "RealName" => $info['RealName'], "ID" => $info['ID']);
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
    $query = SQLWrapper()->prepare("SELECT ID FROM Users WHERE gid = ?");
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
    $query = SQLWrapper()->prepare("SELECT * FROM Bans WHERE gid = :gid")->execute([":gid" => $gid]);
    $info = $query->fetch();
    if (!empty($info)) {
        $ban = array("banned" => true, "UserInfo" => $info['UserInfo'], "Date" => $info['Date'], "AdminInfo" => $info['AdminInfo'], "ID" => $info['ID'], "Reason" => $info['Reason']);
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
    $query = SQLWrapper()->prepare("SELECT ID FROM Users WHERE Name = :name")->execute([":name" => $username]);
    $info = $query->fetch();
    if (empty($info)) {
        return true;
    } else {
        return false;
    }
}
?>