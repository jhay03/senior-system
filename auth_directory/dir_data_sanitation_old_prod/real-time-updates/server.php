<?php
  require __DIR__ . '/vendor/autoload.php';

  $options = array(
    'cluster' => 'ap1',
    'encrypted' => true
  );
  $pusher = new Pusher\Pusher(
    '1da9399b685a86a53f2f',
    'f2d2259405285c6d7564',
    '578579',
    $options
  );


  $id = explode(',', $_POST['raw_id']);
  $data = [];
  foreach ($id as $key => $value) {
    $data[] = array(
           "raw_id" => $value,
           "raw_name" => 'GO',
           "raw_corrected_name" => 'GO, LUIS RAYMOND',
           "raw_license" => '2264758',
           "raw_address" => 'PHC x',
           "raw_branchname" => 'MALOLOS-PLAZA',
           "raw_lbucode" => '2601'
      );
  }
  $pusher->trigger('data-sanit-101', 'client-my-event', $data);


  // for ($i=1; $i < 5; $i++) {
  //   // $data = array('aaaname'.$i,'name','name','name','name','name');
  //   // $data['message'] = 'hello lvffy';
  //   // $data['id'] = '19288200';
  //   // $data['user'] = 'AEROLLE';
  //   // $data['user1'] = 'AEROLLE';
  //   // $data['user2'] = 'AEROLLE';
  //   // $data['user3'] = 'AEROLLE';
  //   if($i == 3){
  //     $data[] = array(
  //            "raw_id" => '3552',
  //            "raw_corrected_name" => 'GO, LUIS',
  //            "raw_license" => '123456',
  //            "raw_address" => 'HEARTH CENTER',
  //            "raw_branchname" => 'PHC',
  //            "raw_lbucode" => '20001'
  //       );
  //   }else {
  //     $data[] = array(
  //            "raw_id" => '0000'.$i,
  //            "raw_corrected_name" => 'raw_corrected_name'.$i,
  //            "raw_license" => 'raw_license'.$i,
  //            "raw_address" => 'raw_address'.$i,
  //            "raw_branchname" => 'raw_branchname'.$i,
  //            "raw_lbucode" => 'raw_lbucode'.$i
  //       );
  //   }
  //
  //
  //
  //
  // }
  // $pusher->trigger('data-sanit-101', 'client-my-event', $data);

?>
