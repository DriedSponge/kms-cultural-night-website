<?php
if (isset($_POST['block'])) {
    if (isset($_POST['pid'])) {
        if (isset($_SESSION['gid'])) {
            if (IsAdmin($_SESSION['gid'])['admin']) {
                if(PostExist($_POST['pid'])){
                $post = SQLWrapper()->prepare("SELECT Title FROM ".PostType($_POST['pid'])." WHERE PostID = :pid");
                $post->execute([":pid" => $_POST['pid']]);
                $data = $post->fetch();
?>
                    <script>
                        $("#deny-modal").modal("show");
                        $("#deny-user").submit(function(e){
                            e.preventDefault()
                            var rsn = $("#reason").val()
                            $.post("<?=v($dir)?>ajax/block-post.php",{
                                confirm:1,
                                rsn:rsn,
                                pid:'<?=v($_POST['pid']);?>'
                            })
                            .done(function(data){
                                if(data.success){
                                    AlertSuccess(data.Msg)
                                    $("#deny-modal").modal("hide");
                                    <?php if(IsEmpty($_POST['table'])){ ?>
                                         setInterval(function(){
                                            location.reload()
                                         },2500)
                                        <?php }else{ ?>
                                        Load("<?= v($_POST['table']); ?>");
                                        <?php } ?>                                  
                                }else{
                                    if(data.SysErr){
                                        AlertError(data.Msg);
                                    }else{
                                        if(data.RsnErr){
                                            InValidate("#reason",data.RsnErr)
                                        }else{
                                            Validate("#reason")
                                        }
                                    }
                                }
                            })
                        })
                    </script>
                    <div class="modal" tabindex="-1" id="deny-modal" role="dialog">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Block Post</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true" style="font-size: 30px">&times;</span>
                                    </button>
                                </div>
                                <div id="deny-load"></div>
                                <form id="deny-user">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label>Reason</label>
                                            <input id="reason" maxlength="100" feedback="#reason-f" class="form-control form-control-alternative" placeholder="Please enter a reason for blocking this post">
                                            <div id="reason-f"></div>
                                        </div>
                                    </div>
                                    <div class="modal-footer text-center" style="justify-content: center">
                                        <button type="submit" class="btn btn-success">Block</button>
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
<?php
                }else{
                    AlertError("The post does not exist");
                }
            } else {
                AlertError("Unauthorized");
            }
        } else {
            AlertError("Session Expired");
        }
    } else {
        AlertError("Invalid Post Data");
    }
}

if (isset($_POST['confirm'])) {
    $Msg = array(
        "success" => false,
        "Msg" => "Something went wrong!",
        "SysErr" => false
    );
    header("Content-type: application/json");
    if (isset($_SESSION['gid'])) {
        if (IsAdmin($_SESSION['gid'])['admin']) {
            if (PostExist($_POST['pid'])) {
                        if (isset($_POST['rsn'])) {
                            if (IsEmpty($_POST['rsn'])) {
                                $Msg['RsnErr'] = "Please enter a reason.";
                            } else if (strlen($_POST['rsn']) > 100) {
                                $Msg['RsnErr'] = "Please reduce the reason to under 100 characters";
                            }
                            if (!isset($Msg['RsnErr'])) {
                                try{
                                    $approvalstatus = array("Status"=>2,"Message"=>$_POST['rsn']);
                                    $query = SQLWrapper()->prepare("UPDATE ".PostType($_POST['pid'])." SET Approved=:a WHERE PostID = :pid");
                                    $query->execute([":a"=>json_encode($approvalstatus),":pid"=>$_POST['pid']]);
                                    $Msg['success'] = true;
                                    $Msg['Msg'] = "The post has been blocked and will not appear on the site!";
                                } catch (PDOException $e)  {
                                    $Msg['SysErr'] = true;
                                    $Msg['Msg'] = "Something causes the block to fail. Please try again later.";
                                    SendError("MySQL Error", $e->getMessage());
                                }
                            }
                        } else {
                            $Msg['SysErr'] = true;
                            $Msg['Msg'] = "Invalid post values!";
                        }
            } else {
                $Msg['SysErr'] = true;
                $Msg['Msg'] = "The post you are trying to block does not exist";
            }
        } else {
            $Msg['SysErr'] = true;
            $Msg['Msg'] = "Unauthorized";
        }
    } else {
        $Msg['SysErr'] = true;
        $Msg['Msg'] = "Invalid Session";
    }
    die(json_encode($Msg));
}