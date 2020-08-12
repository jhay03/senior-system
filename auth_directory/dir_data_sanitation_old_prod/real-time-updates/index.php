<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Real time</title>
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.15/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <style media="screen">

      table.dataTable tbody tr.realtime {
          background-color: Cyan;
      }
      .dataTables_filter { display: none; }
    </style>
  </head>
  <body>

    <div class="container">
      <div class="row">
        <br><br>
        <div class="col-lg-12">
          <div class="pull-left">
            <p>Instructions for REALTIME updating:</p>
            <p>1. Open this page to 2 or more browser.</p>
            <p>2. Select data.</p>
            <p>3. Click the "Assign to: Go, LUIS RYAMOND" button.</p>
            <p>4. Updated data will have background color of cyan and sanitized name.</p>
          </div>
          <br><br><br>
          <div class="pull-right">
            <button type="button" class="btn btn-success" id="btnGetTableData">Assign to: GO, LUIS RAYMOND</button>
          </div>

          <table id="realtime" class="table table-bordered table-hover">
            <thead>
              <tr>
                <th style="white-space: nowrap;">ID</th>
                <th style="white-space: nowrap;" >Doctor</th>
                <th style="white-space: nowrap;" >Sanitized Name</th>
                <th style="white-space: nowrap;">LN</th>
                <th style="white-space: nowrap;">Location</th>
                <th style="white-space: nowrap;">Branch Name</th>
                <th style="white-space: nowrap;">LBA</th>
              </tr>
              <tr>
                <th style="white-space: nowrap;"></th>
                <th style="white-space: nowrap;" ></th>
                <th style="white-space: nowrap;" ></th>
                <th style="white-space: nowrap;"></th>
                <th style="white-space: nowrap;"></th>
                <th style="white-space: nowrap;"></th>
                <th style="white-space: nowrap;"></th>
              </tr>
            </thead>

          </table>
        </div>
      </div>
    </div>



    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.15/js/jquery.dataTables.js"></script>

    <script src="data.js"></script>
    <script src="script.js"></script>

  </body>
</html>
