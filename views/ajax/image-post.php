<?php
if (isset($_POST['post'])) {
    header("Content-type: application/json");
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong",
        "SysErr" => false
    );
    if (isset($_SESSION['UserName'])) {
        if (!IsBanned($_SESSION['gid'])['banned']) {
            if (isset($_POST['c']) && isset($_POST['caption'])) {
                $Categories = array("Food", "Music", "Sports", "Gatherings", "Other");
                if (!in_array($_POST['c'], $Categories)) {
                    $Msg['CErr'] = "Invalid category.";
                }

                if (IsEmpty($_POST['caption'])) {
                    $Msg['CapErr'] = "Please enter a message";
                } else if (strlen($_POST['caption'] > 1000)) {
                    $Msg['CapErr'] = "Please keep your message under 1500 characters.";
                }
                if (!empty($_FILES['files']['name'][0])) {
                    $files = $_FILES['files'];
                    $uploaded = array();
                    $failed = array();
                    $allowed = array("jpeg", "png", "jpg");
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
                                        $filenamenew = uniqid("IMG") . '.' . $file_ext;
                                        if (!file_exists('img/post/' . $_SESSION['gid'])) {
                                            mkdir('img/post/' . $_SESSION['gid']);
                                        }
                                        $file_destination = 'img/post/' . $_SESSION['gid'] . '/' . $filenamenew;
                                        if (move_uploaded_file($file_tmp, $file_destination)) {
                                        } else {
                                            $Msg['FErr'] = "There was an error uploading your files. Sorry.";
                                        }
                                    } else {
                                        $Msg['FErr'] = "[$file_name] is too large. Must be under 100MB";
                                    }
                                } else {
                                    $Msg['FErr'] = "[$file_name] failed to upload.";
                                }
                            } else {
                                $Msg['FErr'] = "[{$file_name}] has an unsupported file type. PNGs and JPEGs only.";
                            }
                        }
                    } else {
                        $Msg['FErr'] = "There is a maximum of five files allowed per post.";
                    }
                } else {
                    $Msg['FErr'] = "Please select at least one file.";
                }
                if (!isset($Msg['FErr']) && !isset($Msg['CapErr']) && !isset($Msg['CErr'])) {
                }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "Invalid post values, try refreshing the page!";
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
