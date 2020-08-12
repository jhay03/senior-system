<?php
	include('../../connection.php');
	//echo $_GET['district'];
	$uniqueID = $_GET['uniqueid'];
	if($_GET['district'] !== ""){
		$district = explode(', ',$_GET['district']);
	}else{
		$district = array();
	}
?>
<div class="form-group">
  <label for="i1">Choose District</label>
  <input type="hidden" name="userid" value="<?php echo $_GET['uniqueid'];?>">
  <select id="district" name="district[]" class="selectpicker show-tick form-control" multiple="multiple" data-placeholder="Select District" data-live-search="true">
    <?php
      // WHERE final_district NOT IN(SELECT district_name FROM district_assignment)
      if($_GET['district'] !== ""){
      	$districtQuery = $mysqli -> query("SELECT * FROM final_district_view WHERE final_district NOT IN(SELECT district_name FROM district_assignment WHERE user_code!='$uniqueID')");
      }else{
      	$districtQuery = $mysqli -> query("SELECT * FROM final_district_view WHERE final_district NOT IN(SELECT district_name FROM district_assignment)");
      }
          while($districtRes = $districtQuery -> fetch_assoc()){
    ?>
    <option value="<?php echo $districtRes['final_district'].','.$districtRes['final_unsanitized_row'];?>"
    		data-subtext="<b class='text-blue'><?php echo number_format((float)$districtRes['final_unsanitized_row'],0);?></b> rows
    		| Total Amount : <b class='text-blue'><?php echo number_format((float)$districtRes['final_amount'],2);?></b>"
    		<?php if(in_array($districtRes['final_district'], $district)){?> selected <?php }?>>
    		<b><?php echo $districtRes['final_district'];?></b>
    </option>
  <?php }?>
  </select>
</div>
