<!-- Modal -->
<!-- <form  method="post" onsubmit="return confirm('Add New Rule?');"> -->
<div class="modal fade" id="AddNewRule" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" style="width:60%">
      <!-- Modal content-->
      <div class="modal-content" >
        <div class="modal-header bg-green">
            <h3 class="modal-title" style="display: inline-block;">Add New Rule</h3>
            <button class="btn-sm close" type="button" data-dismiss="modal">Close</button>
        </div>
        <div class="modal-body">

          <div id="new-loading"></div>

          <div class="row" id="new-body">
            <div class="form-group">



              <div class="col-md-4">
                <label for="">ASSIGN TO</label>
                <!-- <select class="form-control select2" autocomplete='on' id="assign-to" name="criteria1" style="width: 100%; float:left;"> -->
                  <!-- <option disabled selected="selected" value="">ASSIGN TO...</option> -->
                  <?Php

                  // $get_md_name = mysqli_query($mysqli,
                  // "SELECT DISTINCT sanit_mdname as md
                  // FROM db_sanitation
                  // ORDER BY sanit_mdname ASC
                  // ");
                  //
                  // while ($row_md = mysqli_fetch_assoc($get_md_name)) {
                  //     $md_name = $row_md['md'] ;
                  //     echo "<option value='" . $md_name ."'>$md_name</option>";
                  // }

                  ?>
                <!-- </select> -->

                <div class="search-box">
                    <input type="text" class="form-control"  style="width:100%" autocomplete="off" required="required" id="assign-to" placeholder="ASSIGN MD..." />
                    <div class="result"></div>
                </div>



                <select class="form-control conditional" style="display:none" name="criteria1" style="width: 100%;">
                  <option selected="selected" value="N/A"></option>
                </select>


              </div>

              <div class="col-md-2">
                <label>IF</label>
                <select   class="form-control i" name="criteria1" style="width: 100%;">
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
                <select class="form-control c" name="operator1" style="width: 100%;">
                  <option disabled selected="selected" value="">Select Conditional...</option>
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
                <input type="text"  class="form-control v" style="width:100%" name="value1" value="" placeholder="Value...">
              </div>




               <div id="display-new-rule"></div>



            </div>


        </div>
          <div class="display-me"></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-default btn-sm"  id="add-new-rule" style="float:left" type="button"><i class="fa fa-plus"></i> Add Condition</button>
        <button class="btn btn-success btn-sm" id="submit-new" style="float:right;" type="button" name="button"> <i class="fa fa-save"></i> Save Rule</button>
      </div>

    </div>
  </div>
</div>
<!-- </form> -->

<div class="modal fade" id="modalEdit" tabindex="-1" role="dialog" aria-labelledby="largeModal" aria-hidden="true" data-backdrop="static" data-keyboard="false">
  <div class="modal-dialog" style="width:65%">

    <!-- Modal content-->
    <div class="modal-content" >
      <div class="modal-header bg-green">
        <h3 class="modal-title" style="display: inline-block;"><div id="hh">Edit Rule</div></h3>
        <button class="btn-sm close" type="button" data-dismiss="modal">Close</button>
      </div>
      <div class="modal-body">

        <div id="update-loading"></div>


        <div id="content-edit-rule"></div>


          <div class="display-me"></div>
      </div>
      <div class="modal-footer">
          <button class="btn btn-default btn-sm"  id="update-new-rule" style="float:left" type="button"  ><i class="fa fa-plus"></i> Add Condition</button>
          <button class="btn btn-success btn-sm" id="submit-update" style="float:right;" type="button" name="button"> <i class="fa fa-save"></i> Update Rule</button>
      </div>
    </div>

  </div>
</div>

<!-- MODAL -->

<style >

.select2-container .select2-selection--single {
  height: 34px;
}

.select2-container--default .select2-selection--single {
    /* background-color: #fff; */
    border-radius: 0px;
  }

  .select2-container {
    box-sizing: border-box;
    display: inline-block;
    margin: 0;
    position: relative;
    vertical-align: middle;
    /* margin-left: -15px; */
}

#modalEdit .modal-body {
    /* position: relative; */
    /* padding: 0px 10px 0px 10px; */
    /* margin: -5px 0px 10px 0px; */
}


input[type="text"]:disabled{background-color:white;}

input[type=text]:disabled {
    background: white;
}
</style>




<style type="text/css">
    body{
        /* font-family: Arail, sans-serif; */
    }
    /* Formatting search box */

    .search-box input[type="text"]{
        height: 32px;
        padding: 5px 10px;
        border: 1px solid #CCCCCC;
        font-size: 14px;
    }
    .result{
        position: absolute;
        z-index: 999;
        top: 100%;
        left: 0;
    }
    .search-box input[type="text"], .result{
        width: 100%;
        box-sizing: border-box;
    }
    /* Formatting result items */
    .result p{
        margin: 0;
        padding: 7px 10px;
        border: 1px solid #CCCCCC;
        border-top: none;
        cursor: pointer;
        background-color: white;
    }
    .result p:hover{
        background: #f2f2f2;
    }

</style>
