<script>

$(document).ready(function() {

    // $('.select2').select2({
    //   // minimumInputLength: 50
    //    minimumResultsForSearch: 20
    // });
    // $('#assign-to').select2();


    // --------------------------------------- ADD NEW CONDITION
    // --------------------------------------- ADD NEW CONDITION
    var c = 1;
    $("#add-new-rule").click(function(){

      // $("#display-new-rule").append('ADD NEW RULE' + c++)

                    $.ajax({
                      type: "POST",
                      url: "modals/modal_new_condition.php",
                      data: "id=" + c,
                      cache: false,
                      success: function (data) {

                          $("#display-new-rule").append(data )

                          c++


                      },
                      error: function(err) {
                        // console.lhttp://localhost/nutra-hotline/pages/rules_list.php#content-modalog(err);
                      }
                    });

    })
    // --------------------------------------- ADD NEW CONDITION
    // --------------------------------------- ADD NEW CONDITION



    // --------------------------------------- DELETE
    // --------------------------------------- DELETE
    $("#rules-table-approve a").click(function(){
            var rule_code = ($(this).data("id"))
            var condition = ($(this).data("action"))
            var action = "approval"

            if (confirm('Are you sure you want to ' + condition + ' this rule?') == true) {

                      $.ajax({
                        type: "POST",
                        url: "rules_CRUD.php",
                        data: {
                              action: action,
                              rule_code: rule_code,
                              condition: condition
                        },
                        cache: false,
                        beforeSend: function() {

                          $("#changes").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Sending Request"> <i class="fas fa-sync fa-spin"></i>&nbsp; Applying Changes...</button></div></center>');

                          $(".box-body").css("display", "none")

                        },
                        success: function (data) {

                              window.location.href= "approve.php" ;

                              // $(".display-me").html(data)
                        },
                        error: function(err) {
                          // console.lhttp://localhost/nutra-hotline/pages/rules_list.php#content-modalog(err);
                        }
                      });

            }


    })


    $(document).on('click', '#rules-table .btn-danger', function (event) {

        var rule_code = ($(this).data("id"))
        var action = "delete"
        if (confirm('Are you sure you want to delete this rule?') == true) {

                  $.ajax({
                    type: "POST",
                    url: "rules_CRUD.php",
                    data: {
                          action: action,
                          rule_code: rule_code
                    },
                    cache: false,
                    beforeSend: function() {

                      $("#changes").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Sending Request"> <i class="fas fa-sync fa-spin"></i>&nbsp; Applying Changes...</button></div></center>');

                      $(".box-body").css("display", "none")
                    },
                    success: function (data) {

                          window.location.href= "rules_list.php" ;
                          // $("#spin-table").html(data)
                          // $(".display-me").html(data)
                    },
                    error: function(err) {
                      // console.lhttp://localhost/nutra-hotline/pages/rules_list.php#content-modalog(err);
                    }
                  });

        }

    })




    $(document).on('click', '.btn-delete-criteria', function (event) {

          var p = $(this).find("p").text()

          $("#criteria" + p).html('')

    })

    // --------------------------------------- DELETE
    // --------------------------------------- DELETE

    // --------------------------------------- SUBMIT NEW RULE
    // --------------------------------------- SUBMIT NEW RULE
    // --------------------------------------- SUBMIT NEW RULE
    $(document).on('click', '#submit-new', function (event) {


      var okay = 1;
      var conditionNum = 0;
      var action = "add"
      $( ".i" ).each(function() {  if (!$( this ).val()) {   okay = 0;  } conditionNum++;   });
      $( ".c" ).each(function() {  if (!$( this ).val()) {   okay = 0  }   });
      $( ".v" ).each(function() {  if (!$( this ).val()) {   okay = 0  }   });
      $( ".conditional" ).each(function() {  if (!$( this ).val()) {   okay = 0  }   });
      var assign_to = $("#assign-to").val()
        // alert(assign_to)
      if (!assign_to) {
          // alert('PLEASE SELECT MD TO ASSIGN RULE!')
          $("#new-loading").html('<div class="alert alert-danger fade in">' +
          '<strong>Note!</strong> PLEASE SELECT MD TO ASSIGN RULE</div>')
          // $("#new-loading").html("")
          // alert('PLEASE CHECK MD ASSIGNED TO!')

          setTimeout(function(){
            // $("#new-loading").fadeOut()
            $("#new-loading").html("")
          }, 6000);

          return 0;
      }
      // var assign_to = ""

      if (okay == 1 ) {


                                  if (confirm("Add New Rule ? ") == true) {

                                              var conditional_array = [];
                                              var con = ""
                                              $( ".conditional" ).each(function() {

                                                      if ($(this).val() == "N/A") {
                                                            con = ""
                                                      } else { con = $(this).val() }
                                                      conditional_array.push(con );
                                              });

                                              var context_array = [];
                                              $( ".i" ).each(function() {
                                                      context_array.push( $( this ).val() );
                                              });

                                              var operator_array = [];
                                              $( ".c" ).each(function() {
                                                      operator_array.push( $( this ).val() );
                                              });

                                              var value_array = [];
                                              $( ".v" ).each(function() {
                                                      value_array.push( $( this ).val() );
                                              });

                                              // console.log(conditional_array)
                                              // console.log(context_array)
                                              // console.log(operator_array)
                                              // console.log(value_array)


                                              $.ajax({
                                                type: "POST",
                                                url: "rules_CRUD.php",
                                                data: {
                                                      conditional: conditional_array,
                                                      table_name: context_array,
                                                      condition: operator_array,
                                                      value: value_array,
                                                      assign_to: assign_to,
                                                      action: action
                                                },
                                                cache: false,
                                                // success: function (data) {
                                                //
                                                //       $(".display-me").html(data)
                                                // },
                                                beforeSend: function() {

                                                  $("#new-loading").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Sending Request"> <i class="fas fa-sync fa-spin"></i>&nbsp; Applying Changes...</button></div></center>');

                                                  $("#new-body").css("display", "none")
                                                  $("#submit-new").css("display", "none")
                                                  $("#add-new-rule").css("display", "none")
                                                  $(".close").css("display", "none")

                                                },
                                                success: function (data) {

                                                  var d = data.trim()
                                                  // alert(d)
                                                  $(".display-me").html(data)
                                                  return dsadsa;
                                                      if (d == "1") {

                                                        window.location.href= "rules_list.php" ;

                                                      } else {
                                                        // $("#new-loading").fadeIn()
                                                        $("#submit-new").css("display", "block")
                                                        $("#add-new-rule").css("display", "block")
                                                        $("#new-body").css("display", "block")

                                                        $("#new-loading").html('<div class="alert alert-danger fade in">' +
                                                        '<strong>Note!</strong> PLEASE CHECK MD ASSIGNED TO</div>')
                                                        // $("#new-loading").html("")
                                                        // alert('PLEASE CHECK MD ASSIGNED TO!')

                                                        setTimeout(function(){
                                                          // $("#new-loading").fadeOut()
                                                          $("#new-loading").html("")
                                                        }, 6000);

                                                      }

                                                      // $("#spin-table").html(data)
                                                      // $(".display-me").html(data)
                                                },
                                                error: function(err) {
                                                  // console.lhttp://localhost/nutra-hotline/pages/rules_list.php#content-modalog(err);
                                                }
                                              });


                                  }
      } else {

              // alert('PLEASE COMPLETE MISSING FIELD!')
              $("#new-loading").html('<div class="alert alert-danger fade in">' +
              '<strong>Note!</strong> PLEASE COMPLETE MISSING FIELD!</div>')
              // $("#new-loading").html("")
              // alert('PLEASE CHECK MD ASSIGNED TO!')

              setTimeout(function(){
                // $("#new-loading").fadeOut()
                $("#new-loading").html("")
              }, 6000);
      }

    })
    // --------------------------------------- SUBMIT NEW RULE
    // --------------------------------------- SUBMIT NEW RULE
    // --------------------------------------- SUBMIT NEW RULE






    //---------------------------------------- EDIT RULE
    //---------------------------------------- EDIT RULE
    $("#modalEdit").on('show.bs.modal', function (event) {

      var modal = $(this);
      modal.find('#content-edit-rule').html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Sending Request"> <i class="fas fa-sync fa-spin"></i>&nbsp; Loading Data...</button></div></center>');
    })

    $("#modalEdit").on('shown.bs.modal', function (event) {

          var button = $(event.relatedTarget) // Button that triggered the modal
          var id = button.data('id') // Extract info from data-* attributes


          var modal = $(this);
           ajax_request = $.ajax({
                type: "POST",
                url: "modals/modal_edit_condition.php",
                data: {
                  rule_code : id
                },
                cache: true,
                beforeSend: function(){
                    $("#update-append").html('')
                },
                success: function (data) {
                      modal.find('#content-edit-rule').html(data);
                      modal.find('#update-append').html('<div id="update-display-new-rule"></div>');

                      // $("#modalEdit #hh").html("EDITING RULE OF : ( " +  $("#user-name").html() + " )" )

                },
                error: function(err) {
                    console.log(err);
                }
            });
    })

    $(document).on('click', '.update-btn-delete-criteria', function (event) {

          var c = $(this).attr('data-id')

          $("#update-criteria" + c).html('')

    })

    //---------------------------------------- EDIT RULE
    //---------------------------------------- EDIT RULE

    $(document).on('click', '#submit-update', function (event) {

              var okay = 1;
              var conditionNum = 0;
              var action = "update"
              var rule_code = $("#rule-id-update").text()

              $( ".update-i" ).each(function() {  if (!$( this ).val()) {   okay = 0;  } conditionNum++;   });
              $( ".update-c" ).each(function() {  if (!$( this ).val()) {   okay = 0  }   });
              $( ".update-v" ).each(function() {  if (!$( this ).val()) {   okay = 0  }   });
              $( ".update-conditional" ).each(function() {  if (!$( this ).val()) {   okay = 0  }   });
              var assign_to = ""

              if (okay == 1 ) {


                                          if (confirm("Update & modify this rule ? ") == true) {

                                                      var conditional_array = [];
                                                      var con = ""
                                                      $( ".update-conditional" ).each(function() {

                                                              if ($(this).val() == "N/A") {
                                                                    con = ""
                                                              } else { con = $(this).val() }
                                                              conditional_array.push(con );
                                                      });

                                                      var context_array = [];
                                                      $( ".update-i" ).each(function() {
                                                              context_array.push( $( this ).val() );
                                                      });

                                                      var operator_array = [];
                                                      $( ".update-c" ).each(function() {
                                                              operator_array.push( $( this ).val() );
                                                      });

                                                      var value_array = [];
                                                      $( ".update-v" ).each(function() {
                                                              value_array.push( $( this ).val() );
                                                      });

                                                      // console.log(conditional_array)
                                                      // console.log(context_array)
                                                      // console.log(operator_array)
                                                      // console.log(value_array)


                                                      $.ajax({
                                                        type: "POST",
                                                        url: "rules_CRUD.php",
                                                        data: {
                                                              conditional: conditional_array,
                                                              table_name: context_array,
                                                              condition: operator_array,
                                                              value: value_array,
                                                              assign_to: assign_to,
                                                              rule_code: rule_code,
                                                              action: action
                                                        },
                                                        cache: false,
                                                        // success: function (data) {
                                                        //
                                                        //       $(".display-me").html(data)
                                                        // },
                                                        beforeSend: function() {

                                                          $("#modalEdit .modal-body").html('<br><br><center> <div class="col-xs-12 text-center"><button type="button" style="background-color: #ffffff; border-color:#ffffff; margin-top:-25px;" class="btn  btn-lrg ajax" title="Sending Request"> <i class="fas fa-sync fa-spin"></i>&nbsp; Applying Changes...</button></div></center><br>');
                                                          //
                                                          $("#submit-update").css("display", "none")
                                                          $("#update-new-rule").css("display", "none")
                                                          $("#modalEdit .close").css("display", "none")


                                                        },
                                                        success: function (data) {


                                                              window.location.href = "rules_list.php" ;

                                                              // $("#spin-table").html(data)
                                                              // $(".display-me").html(data)
                                                        },
                                                        error: function(err) {
                                                          // console.lhttp://localhost/nutra-hotline/pages/rules_list.php#content-modalog(err);
                                                        }
                                                      });


                                          }
              } else {

                      // alert('PLEASE COMPLETE MISSING FIELD!')
                      $("#update-loading").html('<div class="alert alert-danger fade in">' +
                      '<strong>Note!</strong> PLEASE COMPLETE MISSING FIELD</div>')
                      // $("#new-loading").html("")
                      // alert('PLEASE CHECK MD ASSIGNED TO!')

                      setTimeout(function(){
                        // $("#new-loading").fadeOut()
                        $("#update-loading").html("")
                      }, 6000);


              }



    })

    $("#update-new-rule").click(function(){

                    var c = $("#update-count").html()
                    c = c.replace("#update-count" , "")

                    c = Number(c) + 1
                    $("#update-count").html("#update-count" + c)

                    $.ajax({
                      type: "POST",
                      url: "modals/modal_new_condition_update.php",
                      data: "id=" + c,
                      cache: false,
                      success: function (data) {

                          $("#update-display-new-rule").append(data)

                      },
                      error: function(err) {
                        // console.lhttp://localhost/nutra-hotline/pages/rules_list.php#content-modalog(err);
                      }
                    });

    })


    // DYNAMIC SELECT
    // DYNAMIC SELECT
    $('.search-box input[type="text"]').on("keyup input", function(){
        /* Get input value on change */
        var inputVal = $(this).val();
        var resultDropdown = $(this).siblings(".result");
        $(".result").css("display", "block")
        if(inputVal.length){
            $.get("includes/backend-search.php", {term: inputVal}).done(function(data){
                // Display the returned data in browser
                resultDropdown.html(data);
            });
        } else{
            resultDropdown.empty();
        }
    });

    $('.search-box input[type="text"]').on("focusout", function(){

      setTimeout(function(){
          $(".result").css("display", "none")
       }, 1000);

    });

    // Set search input value on click of result item
    $(document).on("click", ".result p", function(){

        if ($(this).text() == "No matches found") {

          $(this).parents(".search-box").find('input[type="text"]').val("");
        } else {
          $(this).parents(".search-box").find('input[type="text"]').val($(this).text());

        }

        $(this).parent(".result").empty();
        $(".result").css("display", "none")
    });


    // DYNAMIC SELECT
    // DYNAMIC SELECT



});


</script>
