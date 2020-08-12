<?php
    include('../../connection.php');
    $uniqueID = $_GET['uniqueid'];
    $infoQuery = $mysqli -> query("SELECT * FROM auth_users_tbl WHERE auth_id='$uniqueID'");
        while($infoRes = $infoQuery -> fetch_assoc()){
?>

<div class="form-group" <?php if($_GET['f'] !== "user"){?> style='display: none;' <?php }?>>
    <label for="i1">USER CODE</label>
    <input type="hidden" class="form-control" id="modified_userid" name="modified_userid" value="<?php echo $infoRes['auth_id'];?>">
    <input type="text" class="form-control" id="modified_usercode" name="modified_usercode" value="<?php echo $infoRes['auth_usercode'];?>">
</div>
<div class="form-group" <?php if($_GET['f'] !== "user"){?> style='display: none;' <?php }?>>
    <label for="i1">USERNAME</label>
    <input type="text" class="form-control" id="modified_username" name="modified_username" value="<?php echo $infoRes['auth_username'];?>">
</div>
<div class="form-group">
    <label for="i1">FULL NAME</label>
    <input type="text" class="form-control" id="modified_fullname" name="modified_fullname" value="<?php echo $infoRes['auth_fullname'];?>">
</div>
<div class="form-group">
    <label for="i1">ROLE</label>
    <select class="form-control" id="modified_userrole" name="modified_userrole">
      <option value="TEAM LEADER" <?php if($infoRes['auth_role'] == "TEAM LEADER"){?> selected<?php }?>>TEAM LEADER</option>
      <option value="MEMBER" <?php if($infoRes['auth_role'] == "MEMBER"){?> selected<?php }?>>MEMBER</option>
    </select>
</div>
<?php }?>