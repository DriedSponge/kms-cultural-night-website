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

            if (CanPostImage($_SESSION['gid'])) {

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
                        if (!isset($Msg['FErr']) && isset($Msg['success'])) {
                            //die(print_r($uploaded));
                            $query = SQLWrapper()->prepare("INSERT INTO ImagePost (gid,Images,PostID) VALUES (?,?,?)");
                            $query->execute([$_SESSION['gid'], json_encode($uploaded), $postid]);
                            $Msg['success'] = true;
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
            $Msg['Msg'] = "You are banned!";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Not logged in!";
    }
    die(json_encode($Msg));
}
if (isset($_POST['complete'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong",
        "SysErr" => false
    );
    if (isset($_SESSION['UserName'])) {
        if (!IsBanned($_SESSION['gid'])['banned']) {
            if (isset($_POST['caption']) && isset($_POST['category']) && isset($_POST['title']) && isset($_POST['pid'])&& isset($_POST['cul'])) {
                if (GetPostOwner($_POST['pid']) == $_SESSION['gid']) {
                    if (!IsValidPostCategory($_POST['category'])) {
                        $Msg['CErr'] = "Invalid category.";
                    }
                    if (IsEmpty($_POST['caption'])) {
                        $Msg['CapErr'] = "Please enter a caption";
                    } else if (strlen($_POST['caption'] > 1000)) {
                        $Msg['CapErr'] = "Please keep your caption under 1000 characters.";
                    }      
                    if(IsEmpty($_POST['title'])){
                        $Msg['TErr'] = "A title is required.";
                    }else if (strlen($_POST['title'] > 50)) {
                        $Msg['TErr'] = "Please keep your title under 50 characters.";
                    }
                    if(IsEmpty($_POST['cul'])){
                        $Msg['CulErr'] = "Please fillout this field!";
                    }else if (strlen($_POST['cul'] > 50)) {
                        $Msg['CulErr'] = "Please keep this under 50 characters.";
                    }                  
                    if (!isset($Msg['TErr']) && !isset($Msg['CErr']) && !isset($Msg['CapErr'])&& !isset($Msg['CulErr'])) {
                        try {
                            $approvalstatus = array("Status" => 0, "Message" => "Awaiting Approval");
                            $query = SQLWrapper()->prepare("UPDATE ImagePost SET Title = :title, Category=:category,Culture=:culture,Caption=:caption,Approved=:a WHERE PostID = :pid");
                            $query->execute([":title" => $_POST['title'], ":category" => $_POST['category'],":culture"=>$_POST['cul'], ":caption" => $_POST['caption'], ":a" => json_encode($approvalstatus), ":pid" => $_POST['pid']]);
                            $Msg['success'] = true;
                        } catch (PDOException $e) {
                            $Msg['SysErr'] = true;
                            $Msg['Msg'] = "There was an error saving your post to the databse. Please try again later.";
                            SendError("MySQL Error", $e->getMessage());
                        }
                    }
                } else {
                    $Msg['SysErr'] = true;
                    $Msg['Msg'] = "You are not the owner of this post.";
                }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "Invalid post values, try refreshing the page.";
            }
        } else {
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "You are banned!";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Not logged in!";
    }
    die(json_encode($Msg));
}

if (isset($_POST['cancel'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong",
        "SysErr" => false
    );
    if (isset($_SESSION['UserName'])) {
        if (!IsBanned($_SESSION['gid'])['banned']) {
            if (isset($_POST['pid'])) {
                if (GetPostOwner($_POST['pid']) == $_SESSION['gid']) {       
                        if(DeleteImagePost($_POST['pid'])){
                            $Msg['success'] = true;
                            $Msg['Msg'] = "The post and the images associated with it have been deleted!";
                        }else{
                            $Msg['Msg'] = "There was an error canceling your post from the databse. Please try again later.";
                        }
                } else {
                    $Msg['Msg'] = "You are not the owner of this post.";
                }
            } else {
                $Msg['Msg'] = "Invalid post values, try refreshing the page.";
            }
        } else {
            $Msg['Msg'] = "You are banned!";
        }
    } else {
        $Msg['Msg'] = "Not logged in!";
    }
    die(json_encode($Msg));
}