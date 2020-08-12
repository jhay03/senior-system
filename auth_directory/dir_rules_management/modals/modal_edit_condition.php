<?php

require("../../../connection.php") ;

$rule_code = $_POST['rule_code'] ;


        $get_rules = mysqli_query($mysqli,
        $s="SELECT
                details_column_name as column_name,
                details_value_optr as operator,
                details_value as value,
                details_condition_optr as c,
                rule_assign_to as assigned
        FROM rules_details as a
        LEFT JOIN rules_tbl as b
              ON a.rule_code = b.rule_code
        WHERE b.rule_code = '$rule_code'
        ") ;

        // echo "<pre>$s</pre>";

        echo '<div class="">';
        $counter = 1;
        while ($row_get_rules = mysqli_fetch_assoc($get_rules) ) {

                if ($counter == 1) {
                 echo "<p id='user-name' style='display:none'><u>" . $row_get_rules['assigned']. "</u></p>";
                 // echo "<p id='user-name'><center><h3><u>" . $row_get_rules['assigned']. "</u></h3></center></p>";
                 echo "<div class='col-md-4'>";
                 echo "<label>ASSIGNED TO</label>";
                 echo "<input type='text' class='form-control' readonly value='" . $row_get_rules['assigned'] . "' style='background-color: white !important'>";
                 echo "</div>";
                 // echo "<br>";
                }
                $orig_column_name = $row_get_rules['column_name'] ;
                $column_name = str_replace("raw_" , "" , $row_get_rules['column_name']) ;
                $operator = $row_get_rules['operator'] ;
                $value = $row_get_rules['value'] ;
                $condition = $row_get_rules['c'] ;


                if ($counter > 1) {
                  echo "<div id='update-criteria" . $counter . "'>";
                }

                if ($counter == 1) {

                    echo '
                      <label>&nbsp;</label>
                      <select   class="form-control update-conditional" style="display:none" name="criteria1" style="width: 100%;">
                        <option selected="selected" value="N/A"></option>
                      </select>

                    ';

                } else {
                    echo '<div class="col-md-4">
                            <label>&nbsp;</label>
                              <select   class="form-control select2 update-conditional" name="criteria1" style="width: 100%;">';

                              if ($condition == "or") {
                                echo '<option value="or" selected="selected" >OR</option>';
                                echo '<option value="and" >AND</option>';

                              } else {
                                echo '<option value="and" selected="selected" >AND</option>';
                                echo '<option value="or" >OR</option>';

                              }



                                echo '
                              </select>
                          </div>';
                }




                        echo '<div class="col-md-2">
                        <label>IF</label>
                               <select class="form-control update-i" name="criteria1" style="width: 100%;">
                                <option disabled value="">Select Option...</option>';

                                $option_list = array('MD NAME', 'LICENSE NUMBER', 'LBA', 'BRANCH NAME' , 'ADDRESS') ;
                                $option_list_value = array('raw_doctor', 'raw_license', 'raw_lburebate', 'raw_branchname' , 'raw_address') ;


                                $c = 0;
                                foreach ($option_list as $row_md) {

                                    if ($orig_column_name == $option_list_value[$c]) {
                                      echo "<option value='" . $orig_column_name ."' selected='selected'>$row_md</option>";

                                    } else {

                                      echo "<option value='" . $option_list_value[$c] ."'>$row_md</option>";

                                    }
                                    $c++;
                                }


                        echo '</select>
                        </div>';



                        echo '  <div class="col-md-2">
                        <label>IS</label>
                               <select class="form-control update-c" name="criteria1" style="width: 100%;">
                                <option disabled value="">Select Option...</option>';

                                $option_list = array('EQUAL TO', 'LIKE') ;
                                $option_list_value = array('=', 'LIKE') ;

                                $c = 0;
                                foreach ($option_list as $options) {

                                    if ($operator == $option_list_value[$c]) {
                                      echo "<option value='" . $operator ."' selected='selected'>$options</option>";

                                    } else {

                                      echo "<option value='" . $option_list_value[$c] ."'>$options</option>";

                                    }
                                    $c++;
                                }


                        echo '</select>
                        </div>';



                        echo '   <div class="col-md-4">';

                        if ($counter > 1) {
                          echo '  <label>&nbsp;</label>';
                        }
                        echo '


                                    <div class="input-group">

                                        <input type="text" class="form-control update-v" style="width:100%" name="value1" value="' . $value .'" placeholder="Value...">';


                                          if ($counter > 1) {
                                          // echo '  <div class="input-group">';
                                          echo '  <div class="input-group-addon">';
                                          echo ' <a href="#" class="update-btn-delete-criteria"
                                          data-id="' . $counter .'"
                                          style="display:inline-block !important; color: red"><i class="fa fa-trash-alt"></i><p style="display:none">' . $counter. '</p></a>';
                                          echo '</div>';

                                          }

                                        echo '</div>
                              </div>';


                             echo "<div class='clearfix'></div>";


            if ($counter > 1) {
              echo "</div>";
            }

        $counter++;
        }

        echo '<div id="update-append">
              <div id="update-display-new-rule"></div>
        </div>';


        echo '</div>';

        $counter--;
        echo "<div class='clearfix'></div>";
        echo "<p style='display:none' id='rule-id-update'>$rule_code</p>";
        // echo "<p   id='update-count'>$counter</p>";
        echo "<p style='display:none' id='update-count'>$counter</p>";
        // echo "<div class='display-me'>HAHA</div>";
?>
