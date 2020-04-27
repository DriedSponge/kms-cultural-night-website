<?php
if (isset($_POST['upload'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong",
        "SysErr" => false
    );

    if (isset($_SESSION['UserName'])) {
        if (!IsBanned($_SESSION['gid'])['banned']) {
            
            if ($upload) {

                if (!empty($_FILES['files']['name'][0])) {
                    $files = $_FILES['files'];
                    $uploaded = array();
                    $failed = array();
                    $allowed = array("jpeg", "png", "jpg");
                    $postid =  uniqid('IP');
                    if (!file_exists('img/post/' . $postid)) {
                        mkdir('img/post/' . $postid);
                        $idir = 'img/post/' . $postid;
                    }
                    if (count($_FILES['files']['name']) <= 5) {
                        foreach ($files['name'] as $position => $file_name) {
                            $file_tmp = $files['tmp_name'][$position];
                            $file_size = $files['size'][$position];
                            $file_error = $files['error'][$position];
                            $file_ext = explode('.', $file_name);
                            $file_ext = strtolower(end($file_ext));
                            if (in_array($file_ext, $allowed)) {
                                if ($file_error === 0) {
                                    if ($file_size <= 100000000) {
                                        $filenamenew = $position . '.' . $file_ext;
                                        $file_destination = $idir . '/' . $filenamenew;
                                        if (move_uploaded_file($file_tmp, $file_destination)) {
                                            $Msg['success'] = true;
                                            $imgurl = $dir . "pimg/$postid/$filenamenew";
                                            array_push($uploaded, $imgurl);
                                        } else {
                                            $Msg['FErr'] = "There was an error uploading your files. Sorry.";
                                            break;
                                        }
                                    } else {
                                        $Msg['FErr'] = "[$file_name] is too large. Must be under 100MB";
                                    }
                                } else {
                                    $Msg['FErr'] = "[$file_name] failed to upload.";
                                }
                            } else {
                                $Msg['FErr'] = "[{$file_name}] has an unsupported file type. PNGs and JPEGs only.";
                                rmdir($idir);
                            }
                        }
                        if(!isset($Msg['FErr']) && isset($Msg['success'])){
                            //die(print_r($uploaded));
                            $query = SQLWrapper()->prepare("INSERT INTO ImagePost (gid,Images,PostID) VALUES (?,?,?,?)"); 
                            $query->execute([$_SESSION['gid'],json_encode($uploaded),$postid]);
                        }
                    } else {
                        $Msg['FErr'] = "There is a maximum of five files allowed per post.";
                    }
                } else {
                    $Msg['FErr'] = "Please select at least one file.";
                }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "You already have a post requiring additional information.";
            }
        } else {
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "You are banned! Please use the ban appeal form!";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Not logged in!";
    }
    die(json_encode($Msg));
}
