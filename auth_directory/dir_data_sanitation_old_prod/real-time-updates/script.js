const dataTable = $('#realtime').DataTable({
          data: dataSet,
          columns: [
            { title: 'RAW ID' },
            { title: 'DOCTOR' },
            { title: 'SANITIZED NAME' },
            { title: 'LICENSE' },
            { title: 'ADDRESS.' },
            { title: 'BRANCH' },
            { title: 'LBU' }
          ],
          deferRender: true,
          paging: true,
          info: true,
          ordering: false,
          scrollCollapse: true,
          responsive: true,
          autoWidth: true,
          processing: false,
          scroller:   true,
          searching: true,
          initComplete: function () {
            this.api().columns().every( function (index ) {

              if(index == 0){

                var column = this;
                var select = $('<select class="form-control select2 selectpicker" multiple="multiple" data-width="5px" disabled data-container="body" data-selected-text-format="count>1"></select>')
                  .appendTo( $(column.header()).empty() );

              }else {

                  var column = this;
                  var select = $('<select class="form-control select2 selectpicker" multiple="multiple" data-width="fit" data-container="body" data-selected-text-format="count>1"></select>')
                    .appendTo( $(column.header()).empty() )
                    .on( 'change', function () {
                      var fVal = $(this).val();
                      // var fVal = val.join("|");
                      var search = $.fn.dataTable.util.throttle(
                           function ( fVal ) {
                               column.search( fVal ? '^'+fVal+'$' : '', true, false ).draw();
                           },
                           1000
                       );
                       search( fVal );
                    } );

                  column.data().unique().sort().each( function ( d, j ) {
                    select.append( '<option title="'+d+'" value="'+d+'">'+d+'</option>' );
                  } );

                  $('.selectpicker').selectpicker({
                      liveSearch: true,
                      noneSelectedText: 'Search',

                  });

              }

            } );
          }
        });

        $("#btnGetTableData").click(function (e){
          e.preventDefault();
          var raw_id_obj = dataTable.column(0, { filter:'applied' }).data();
          var raw_id_array = $.map(raw_id_obj, function(value, index) {
             return [value];
           });
           console.log(raw_id_array);
           if(raw_id_array.length > 0){
             var token = 'BKPI2017';
             var cmd = 'update';
             var dataString = "token=" + token + "&cmd=" + cmd + "&raw_id=" + raw_id_array;
             $.ajax({
               type: "POST",
               url: "server.php",
               data: dataString,
               beforeSend: function( xhr ) {

               },
               success: function (response) {
                 console.log(response);
               },
               error: function (xhr, ajaxOptions, thrownError) {
                 console.log(xhr.status);
                 console.log(thrownError);
               }
             });
           }else {
             alert("No data to be sanitized!");
           }


        })



        var pusher = new Pusher('1da9399b685a86a53f2f', {
          cluster: 'ap1',
          encrypted: true
        });

        var channel = pusher.subscribe('data-sanit-101');
        channel.bind('client-my-event', (data) => {

          for(var i = 0; i < data.length; i++) {
              var obj = data[i];
              var raw_id = obj.raw_id;
              var temp = [];
              // temp[0] = obj.raw_id;
              temp[1] = obj.raw_name;
              temp[2] = obj.raw_corrected_name;
              temp[3] = obj.raw_license;
              temp[4] = obj.raw_address;
              temp[5] = obj.raw_branchname;
              temp[6] = obj.raw_lbucode;

              console.log(raw_id);
              var indexes = dataTable.rows().eq( 0 ).filter( function (rowIdx) {
                  temp[0] = dataTable.cell( rowIdx, 0 ).data();
                  if(dataTable.cell( rowIdx, 0 ).data() === raw_id){
                    dataTable.row(rowIdx).data(temp).invalidate();
                    dataTable.rows( rowIdx ).nodes().to$().addClass( 'realtime' );
                  }
                  return dataTable.cell( rowIdx, 0 ).data() === raw_id ? true : false;
              } );
          }

        });
