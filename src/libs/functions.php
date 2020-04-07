<?php

/**
 * Check if user is an admin
 *
 * @param int $steamid
 * @return boolean
 */
function isAdmin($steamid)
{
    include("config.php");
    if ($steamid == $OWNER) {
        return true;
    } else {
        $adms =  json_decode(file_get_contents("data/managers.json"), true);
        for ($i = 0; $i <= count($adms); $i++) {
            if (isset($adms[$i]["id64"])) {


                if ($adms[$i]["id64"] == $steamid) {
                    return true;
                    break;
                }
            }
        }
    }
}


/**
 * Check if user is an owner
 *
 * @param int $steamid
 * @return boolean
 */
function isOwner($steamid)
{
    include("config.php");
    if ($steamid == $OWNER) {
        return true;
    }
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
 * Generates a users profile url from their steamid64
 *
 * @param string $string
 * @return string
 */
function StmURL($id64)
{
    $url = "https://steamcommunity.com/profiles/$id64";
    return  $url;
}
/**
 * Gets the username of a steamuser
 *
 * @param string $string
 * @return string
 */
function StmName($id64)
{
    include("config.php");
    $json = file_get_contents("http://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key=" . $STEAMAPIKEY . "&steamids=$id64");
    $apidata = json_decode($json);
    if (isset($apidata->response->players[0]->personaname)) {


        $name = $apidata->response->players[0]->personaname;
    } else {
        $name = null;
    }
    return $name;
}
