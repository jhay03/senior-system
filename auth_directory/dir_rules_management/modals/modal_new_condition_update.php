<?Php

// print_r($_POST);

?>

  <div class="" id="update-criteria<?Php echo $_POST['id'] ; ?>">

    <!-- <div class="form-group"> -->

            <div class="col-md-4">
              <label>&nbsp;</label>

                <select   class="form-control  update-conditional" name="criteria1" style="width: 100%;">
                  <option selected="selected" value="and">AND</option>
                  <option value="or">OR</option>
                </select>

            </div>

              <div class="col-md-2">
                    <label>IF</label>
                    <select id="." class="form-control  update-i" name="criteria1" style="width: 100%;">
                            <option disabled selected="selected" value="">Select Option...</option>
                            <?Php

                            $option_list = array('MD NAME', 'LICENSE NUMBER', 'LBA', 'BRANCH NAME' , 'ADDRESS') ;
                            $option_list_value = array('raw_doctor', 'raw_license', 'raw_lburebate', 'raw_branchname' , 'raw_address') ;


                            $c = 0;
                            foreach ($option_list as $row_md) {
                                echo "<option value='" . $option_list_value[$c++] ."'>$row_md</option>";
                            }

                            ?>

                    </select>
              </div>

              <div class="col-md-2">

                    <label>IS</label>
                    <select class="form-control  update-c" name="operator1" style="width: 100%;">
                      <option disabled selected="selected" value="">Select Condition...</option>
                    <?Php

                    $option_list = array('EQUAL TO', 'LIKE') ;
                    $option_list_value = array('=', 'LIKE') ;

                    $c = 0;
                    foreach ($option_list as $options) {
                        echo "<option value='" . $option_list_value[$c++] ."'>$options</option>";
                    }

                    ?>

                    </select>

              </div>

              <div class="col-md-4">

                    <label>&nbsp;</label>
                    <div class="input-group">
                        <input type="text" class="form-control update-v" style="width:100%" name="value1" value="" placeholder="Value...">

                        <div class="input-group-addon">
                          <a href="#" class="update-btn-delete-criteria"
                          data-id="<?php echo $_POST['id']; ?>"
                          style="display:inline-block !important; color: red">
                          <i class="fa fa-trash-alt"></i> <?Php echo "<p style='display:none'>" . $_POST['id'] . "</p>"; ?></a>
                        </div>


                    </div>


              </div>


    <!-- </div> -->


</div>
