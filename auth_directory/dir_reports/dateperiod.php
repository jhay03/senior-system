<!---------Trend Options---------->
<div id="year_trend" style="display:none;width:100%">
  <div class="box box-widget">
          <div class="box-body">
              <span style="display:inline-block; text-align:center;" class="text-blue">
                  <b>FROM</b>
              </span>
              <span style="display:inline-block; text-align:center;">
                  <strong>:</strong>
              </span>
              <span style="display:inline-block;">
                  <select class="form-control input-sm" id="yt_start">
                      <optgroup label="Select Year">
                          <?php
                              $cutoff = 2016;
                              $now = date('Y');
                              for ($y=$cutoff; $y<=$now; $y++) {
                          ?>
                              <option value="<?php echo $y;?>"><?php echo $y;?> </option>
                          <?php }?>
                      </optgroup>
                  </select>
              </span>
              <span class="separator"></span>
              <span style="display:inline-block; text-align:center;" class="text-blue">
                   <b>TO</b>
              </span>
              <span style="display:inline-block; text-align:center;">
                  <strong>:</strong>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="yt_end">
                      <optgroup label="Select Year">
                          <?php
                              $cutoff = 2016;
                              $now = date('Y');
                              for ($y=$cutoff; $y<=$now; $y++) {
                          ?>
                              <option value="<?php echo $y;?>"><?php echo $y;?> </option>
                          <?php }?>
                      </optgroup>
                  </select>
              </span>
              <span class="separator"></span>
              <span style="display:inline-block">
                  <input type='hidden' value='true' name="yt_btn"/>
                  <a href="#" class="btn btn-info btn-sm" onclick="showResultYear();"><i class="fa fa-search"></i> Generate Report</a>
              </span>
    </div>
  </div>
</div>

<div style="display:none; " id="quarter_trend">
  <div class="box box-widget">
          <div class="box-body">
              <span style="display:inline-block; text-align:center;" class="text-blue">
                  <b>FROM</b>
              </span>
              <span style="display:inline-block; text-align:center;">
                  <strong>:</strong>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="qt_start">
                      <optgroup label="Select Quarter">
                          <option value="Q1">Quarter 1</option>
                          <option value="Q2">Quarter 2</option>
                          <option value="Q3">Quarter 3</option>
                          <option value="Q4">Quarter 4</option>
                      </optgroup>
                  </select>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="qty_start">
                      <optgroup label="Select Year">
                          <?php
                              $cutoff = 2016;
                              $now = date('Y');
                              for ($y=$cutoff; $y<=$now; $y++) {
                          ?>
                              <option value="<?php echo $y;?>"><?php echo $y;?> </option>
                          <?php }?>
                      </optgroup>
                  </select>
              </span>
              <span class="separator"></span>
              <span style="display:inline-block; text-align:center;" class="text-blue">
                   <b>TO</b>
              </span>
              <span style="display:inline-block; text-align:center;">
                  <strong>:</strong>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="qt_end">
                      <optgroup label="Select Quarter">
                          <option value="Q1">Quarter 1</option>
                          <option value="Q2">Quarter 2</option>
                          <option value="Q3">Quarter 3</option>
                          <option value="Q4">Quarter 4</option>
                      </optgroup>
                  </select>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="qty_end">
                      <optgroup label="Select Year">
                          <?php
                              $cutoff = 2016;
                              $now = date('Y');
                              for ($y=$cutoff; $y<=$now; $y++) {
                          ?>
                              <option value="<?php echo $y;?>"><?php echo $y;?> </option>
                          <?php }?>
                      </optgroup>
                  </select>
              </span>
              <span class="separator"></span>
              <span style="display:inline-block">
                  <a href="#" class="btn btn-info btn-sm" onclick="showResultQuarter();"><i class="fa fa-search"></i> Generate Report</a>
              </span>
    </div>
</div>
</div>

<div id="month_trend" style="display:none; ">
  <div class="box box-widget">
          <div class="box-body">
              <span style="display:inline-block; text-align:center;" class="text-blue">
                  <b>FROM</b>
              </span>
              <span style="display:inline-block; text-align:center;">
                  <strong> : </strong>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="mt_start">
                      <optgroup label="Select Month">
                          <option value="January">January</option>
                          <option value="February">February</option>
                          <option value="March">March</option>
                          <option value="April">April</option>
                          <option value="May">May</option>
                          <option value="June">June</option>
                          <option value="July">July</option>
                          <option value="August">August</option>
                          <option value="September">September</option>
                          <option value="October">October</option>
                          <option value="November">November</option>
                          <option value="December">December</option>
                      </optgroup>
                  </select>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="mty_start">
                      <optgroup label="Select Year">
                          <?php
                              $cutoff = 2016;
                              $now = date('Y');
                              for ($y=$cutoff; $y<=$now; $y++) {
                          ?>
                              <option value="<?php echo $y;?>"><?php echo $y;?> </option>
                          <?php }?>
                      </optgroup>
                  </select>
              </span>
              <span class="separator"></span>
              <span style="display:inline-block; text-align:center;" class="text-blue">
                   <b>TO</b>
              </span>
              <span style="display:inline-block; text-align:center;">
                  <strong>:</strong>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="mt_end">
                      <optgroup label="Select Month">
                          <option value="January">January</option>
                          <option value="February">February</option>
                          <option value="March">March</option>
                          <option value="April">April</option>
                          <option value="May">May</option>
                          <option value="June">June</option>
                          <option value="July">July</option>
                          <option value="August">August</option>
                          <option value="September">September</option>
                          <option value="October">October</option>
                          <option value="November">November</option>
                          <option value="December">December</option>
                      </optgroup>
                  </select>
              </span>
              <span style="display:inline-block">
                  <select class="form-control input-sm" id="mty_end">
                      <optgroup label="Select Year">
                          <?php
                              $cutoff = 2016;
                              $now = date('Y');
                              for ($y=$cutoff; $y<=$now; $y++) {
                          ?>
                              <option value="<?php echo $y;?>"><?php echo $y;?> </option>
                          <?php }?>
                      </optgroup>
                  </select>
              </span>
              <span class="separator"></span>
              <span style="display:inline-block">
                  <input type='hidden' value='true' name="mt_btn"/>
                  <a href="#" class="btn btn-info btn-sm" onclick="showResultMonth()"><i class="fa fa-search"></i> Generate Report</a>
              </span>
    </div>
</div>
</div>
