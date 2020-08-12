<?php
//ini_set('memory_limit', '-1');
//set_time_limit(0);
  session_start();
  include('../../connection.php');
  if(!isset($_SESSION['authUser'])){
    header('Location:../../logout.php');
  }

  $district = explode(',', $_GET['district']);
  $district = implode("','",$district);
  date_default_timezone_set('Asia/Manila');
  $current_date = date("m/d/Y");
  $counter = 0;
  // Added Query fixes 11/22/2018
  $rows = array();
  $statusQuery = $mysqli -> query("SELECT raw_id,raw_doctor,
                                          raw_license,raw_address,
                                          raw_branchname,raw_lbucode,
                                          raw_hospcode,raw_sarcode,
                                          raw_amount,raw_corrected_name,
                                          sanitized_by,date_sanitized
                                          FROM mdc_senior.sanitation_result1
                                          WHERE raw_district IN('$district')
                                          GROUP BY raw_corrected_name,raw_license,raw_address");
  // $statusQuery = $mysqli2 -> query("SELECT raw_id,raw_doctor,raw_license,raw_address,raw_branchname,raw_lbucode,raw_hospcode,raw_sarcode,raw_amount,raw_corrected_name,sanitized_by,date_sanitized FROM sanitation_result1 WHERE raw_district IN('$district')");
      while($dataRes = $statusQuery -> fetch_assoc()){
         $rows[] = array("id"=>$dataRes['raw_id'],"md"=>str_replace("'","\'",str_replace('"','\"',$dataRes['raw_doctor'])),"md2"=>str_replace("'","\'",str_replace('"','\"',$dataRes['raw_corrected_name'])),"license"=>str_replace("'","\'",$dataRes['raw_license']),
         "address"=>str_replace("'","\'",str_replace('"','\"',$dataRes['raw_address'])),"branch"=>str_replace("'","\'",$dataRes['raw_branchname']),
         "lba"=>str_replace("'","\'",$dataRes['raw_lbucode']),"hcode"=>str_replace("'","\'",$dataRes['raw_hospcode']),
         "scode"=>str_replace("'","\'",$dataRes['raw_sarcode']),
         "amount"=>str_replace("'","\'",$dataRes['raw_amount']),
         "by"=>str_replace("'","\'",$dataRes['sanitized_by']),
         "date"=>str_replace("'","\'",$dataRes['date_sanitized']),
         "counter"=>$counter);
         $counter++;
        }
?>
    <div class="table-responsive" style="overflow:hidden !important;">
    <table id="example2" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
              <th style="white-space: nowrap;">ID</th>
              <th style="white-space: nowrap;">Doctor</th>
              <th style="white-space: nowrap;">Sanitized Name</th>
              <th style="white-space: nowrap;">License #</th>
              <th style="white-space: nowrap;">Location</th>
              <th style="white-space: nowrap;">Branch Name</th>
              <th style="white-space: nowrap;">LBA</th>
              <th style="white-space: nowrap;">Hospital Code</th>
              <th style="white-space: nowrap;">SAR Code</th>
              <th style="white-space: nowrap;">Amount</th>
              <th style="white-space: nowrap;">Sanitized by</th>
              <th style="white-space: nowrap;">Sanitized Date</th>
              <th style="white-space: nowrap;"></th>
            </tr>
            <tr>
              <td style="white-space: nowrap;">ID</td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;">Sanitized Name</td>
              <td style="white-space: nowrap;">LN</td>
              <td style="white-space: nowrap;">Location</td>
              <td style="white-space: nowrap;">Branch Name</td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;"></td>
              <td style="white-space: nowrap;"></td>
            </tr>
        </thead>
       <!-- <tfoot style="display: table-header-group;">-->
       <!--     <tr>-->
       <!--       <th style="white-space: nowrap;">ID</th>-->
       <!--       <th style="white-space: nowrap;" >Doctor</th>-->
       <!--       <th style="white-space: nowrap;" >Sanitized Name</th>-->
       <!--       <th style="white-space: nowrap;">LN</th>-->
       <!--       <th style="white-space: nowrap;">Location</th>-->
       <!--       <th style="white-space: nowrap;">Branch Name</th>-->
       <!--       <th style="white-space: nowrap;">LBA</th>-->
       <!--       <th style="white-space: nowrap;">Hospital Code</th>-->
       <!--       <th style="white-space: nowrap;">SAR Code</th>-->
       <!--       <th style="white-space: nowrap;">Amount</th>-->
       <!--       <th style="white-space: nowrap;">Sanitized by</th>-->
       <!--       <th style="white-space: nowrap;">Sanitized Date</th>-->
       <!--       <th style="white-space: nowrap;"></th>-->
       <!--     </tr>-->
       <!--</tfoot>-->
      <tbody>

      </tbody>
    </table>
    </div>
<script>
$(document).ready(function() {
     $('#example2 thead tr td').each( function (i) {
        var title = $('#example2 thead tr td').eq( $(this).index() ).text();
        if(title == "Sanitized Name" || title == "LN" || title == "Location" || title == "Branch Name" || title == "ID"){
            var filterID = title.replace(" ","");
          //$(this).html( '<input class="form-control input-sm" type="text" placeholder="Search..." style="width: 100% !important;" data-index="'+i+'" />' );
          $(this).html( '<div class="input-group input-group-sm" style="width: 100% !important;">'
                        + '<input type="text" class="form-control input-sm" id="'+filterID+'" style="min-width:100px;height:25px !important" data-index="'+i+'">'
                        + '<span class="input-group-btn">'
                        + '<button id="search'+filterID+'" type="button" class="btn btn-info btn-flat" data-index="'+i+'" data-fidentifier="'+filterID+'" style="height: 25px !important;padding: 0px 10px !important;"><i class="fa fa-search"></i></button>'
                        + '<button id="cancel'+filterID+'" type="button" class="btn btn-danger btn-flat" data-index="'+i+'" data-fidentifier="'+filterID+'" style="height: 25px !important;padding: 0px 10px !important;"><i class="fa fa-times"></i></button>'
                        + '</span></div>' );
        }
     });

    var data = [];
    <?php
        $i = 0;
        foreach($rows as $realRow){
    ?>
        data.push( [ "<?php echo $realRow['id'];?>",
                     "<?php echo $realRow['md'];?>",
                     "<span id='<?php echo $realRow['counter']."correctmd";?>'><?php echo $realRow['md2'];?></span>",
                     "<?php echo $realRow['license'];?>", "<?php echo $realRow['address'];?>",
                     "<?php echo $realRow['branch'];?>",
                     "<?php echo $realRow['lba'];?>",
                     "<?php echo $realRow['hcode'];?>",
                     "<?php echo $realRow['scode'];?>",
                     "<?php echo $realRow['amount'];?>",
                     "<?php echo $realRow['by'];?>",
                     "<span id='<?php echo $realRow['counter']."date";?>'><?php echo $realRow['date'];?></span>",
                     "<?php echo $realRow['counter'];?>" ] );
    <?php $i++;}?>
        var table = $('#example2').DataTable( {
            orderCellsTop: true,
            data:           data,
            deferRender:    true,
            scrollY:        400,
            scrollX:        "100%",
            scrollCollapse: true,
            scroller:       true,
            //paging:false,
            //pageLength:1000,
            //lengthChange:false,
            columnDefs: [ {"targets": [ 12 ], "visible": false, "searchable": false },
                    { "orderable": false, "targets": [0,1,4,5,6,7,8,9,10,11]},
                    { type: 'natural', targets: 3 },
                    {
                    		'targets': 10,
                    		'createdCell':  function (td, cellData, rowData, row, col) {
                       			$(td).attr('id',row + 'by');
                    		}
                 		}
                  ],
          rowCallback: function(row, data, index){

            if(data[10] != ''){
              $('td', row).css('background-color', 'Cyan');
            }
          },
          initComplete: function () {
            // this.api().columns([2,3]).every( function (index ) {

            //   var column = this;
            //   var that = this;

            //   var select = $('<input type="text" class="form-control" placeholder="Search"/>')
            //     .appendTo( $(column.header()).empty() )
            //     .bind( 'keyup change click', function (e) {
            //       if ( that.search() !== this.value ) {
            //         that
            //             .search( this.value )
            //             .draw();
            //           }
            //           e.stopPropagation();
            //     } );

            // } );
            $( "#btnSearchDataSanitation" ).prop( "disabled", false );
            $('#btnSearchDataSanitation').html('Search');
            $( "#btnGetTableData" ).prop( "disabled", false );
            $( "#btnAssignOther" ).prop( "disabled", false );
            $( "#btnAssignOtherInline" ).prop( "disabled", false );
            // countRow(district_list);
          }
        } );

        $('#example2 tbody').on( 'click', 'tr', function (e) {
          $(this).toggleClass('selected');

        });
        table.order( [ 3, 'asc' ] ).draw();
        var search = $.fn.dataTable.util.throttle(
            function ( val ) {
                table.search( val ).draw();
            },
            50
        );
        $( table.table().container() ).on( 'click', 'thead .btn-info', function () {
                var filterIdentifier = $(this).data('fidentifier');
                //alert(filterIdentifier);
                var textvalue = $('#' + filterIdentifier).val();
                //if(charlength >= 4){
                    table
                        .column( $(this).data('index') )
                        //.search( this.value )
                        .search( textvalue )
                        .draw();
                //}
            } );
          $( table.table().container() ).on( 'click', 'thead .btn-danger', function () {
                  var filterIdentifier = $(this).data('fidentifier');
                  //alert(filterIdentifier);
                  $('#' + filterIdentifier).val("");
                  //var textvalue = this.value;
                  var textvalue = $('#' + filterIdentifier).val();
                  var charlength = textvalue.length;
                  if(charlength == 0){
                      table
                          .column( $(this).data('index') )
                          //.search( this.value )
                          .search( textvalue )
                          .draw();
                  }
              } );

            $( table.table().container() ).on( 'keyup', 'thead input', function (e) {
                    var textvalue = this.value;
                    var charlength = textvalue.length;
                    //alert(textvalue);
                    //if(charlength !== 0){
                      e.preventDefault();
                      //alert(e.keyCode);
                      // Number 13 is the "Enter" key on the keyboard
                      if (e.keyCode === 13 || charlength == 0) {
                        //alert(textvalue);
                        table
                            .column( $(this).data('index') )
                            //.search( this.value )
                            .search( textvalue )
                            .draw();
                      }
                    //}

                } );
} );
</script>
