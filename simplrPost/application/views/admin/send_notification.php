<link href="<?=base_url('assets/css/plugins/slick.css')?>" rel="stylesheet" />
<link href="<?=base_url('assets/css/plugins/slick-theme.css')?>" rel="stylesheet" />
<style>
  #loader-div {
      width: 100%;
      height: 100vh;
      position: fixed;
      top: 0;
      left: 0;
      z-index: -1;
    }

  .loader {
    position: absolute;
    left: 50%;
    top: 50%;
    z-index: 10;
    width: 150px;
    height: 150px;
    margin: -75px 0 0 -75px;
    border: 16px solid #f3f3f3;
    border-radius: 50%;
    border-top: 16px solid #3498db;
    width: 120px;
    height: 120px;
    -webkit-animation: spin 2s linear infinite;
    animation: spin 2s linear infinite;
  }

  @-webkit-keyframes spin {
    0% {
      -webkit-transform: rotate(0deg);
    }

    100% {
      -webkit-transform: rotate(360deg);
    }
  }

  @keyframes spin {
    0% {
      transform: rotate(0deg);
    }

    100% {
      transform: rotate(360deg);
    }
  }
</style>
<div id="loader-div">
  <div id="loader">

  </div>
</div>
<!-- Modal -->
<div class="modal fade" id="modalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <h2 class="modal-title pb-4 text-center font-weight-bold" style="color:#1bac71" id="exampleModalLabel">Notification sent successfully</h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>
<!-- Header -->
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- Card stats -->
            <div class="row">

            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--7 wrap">
    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Send Notification</h3>
                </div>
                <div class="row px-3">
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="px-2 py-2">Country</label>
                        <div class="input-group input-group-alternative">
                            <select class="form-control" id="country">
                                <option value="0">All</option>
                                <?php
                                    foreach ($arrCountries as $country) {
                                        echo "<option value='".$country['countryId']."'>".ucfirst($country['countryName'])."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="px-2 py-2">States</label>
                        <div class="input-group input-group-alternative">
                            <select class="form-control" id="state" disabled>
                                <option value="0">All</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="px-2 py-2">Category</label>
                        <div class="input-group input-group-alternative">
                            <select class="form-control" id="category">
                                <option value="0">All</option>
                                <?php
                                    foreach ($arrPrimaryCategories as $category) {
                                        echo "<option value='".$category['categoryId']."'>".$category['categoryName']."</option>";
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group col-lg-3 col-md-3 col-sm-12">
                        <label class="px-2 py-2">Public/Private</label>
                        <div class="input-group input-group-alternative">
                            <select class="form-control" id="audience">
                                <option value="1">Users</option>
                                <option value="2">Businesses</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive card shadow">
                <table class="table align-items-center table-flush">
                   
                    <tbody>                     
                        <tr>
                            <td>
                            <form>
                                <textarea class="form-control" id="exampleFormControlTextarea1" rows="5" placeholder="Write notification here ..." style="resize:none"></textarea>
                                <span class="bg-danger text-light px-2 py-1" style="display:none" id="validationError">Please type some text to send</span>
                            </form>
                            </td>
                            <td class="text-center">
                              <button class="btn btn-icon btn-3 btn-primary" id="sendNotification">
                                    Send Notification
                                </button>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script>
    $(document).ready(function(){
        $('#exampleFormControlTextarea1').focus(function(){
            $('#validationError').css('display', 'none');
        })
        $(document).ajaxStart(function() {
            $('#loader').addClass('loader');
            $('#loader-div').css('z-index', '10');
        });

        $(document).ajaxComplete(function() {
            $('#loader').removeClass('loader');
            $('#loader-div').css('z-index', '-1');
        });

        $('#country').on('change', function(){
            if($(this).val() == 0){
                $('#state').attr('disabled', 'true');
                $('#state').val(0);
            } else{
                $.ajax({
                    url : '<?=SITE_URL?>Admin/Admin/getStates',
                    type : 'post',
                    data: {
                        'countryId' : $(this).val()
                    },
                    success : function(data){
                        data = JSON.parse(data);
                        $('#state').empty();
                        $option = "<option value='0'>All</option>";
                        for($i = 0; $i < data.length; $i++){
                            // console.log(data[$i].stateId + ' ' + data[$i].stateName);
                            $option += "<option value='"+ data[$i].stateId +"'>"+ data[$i].stateName +"</option>"
                        }
                        $('#state').html($option);
                        $('#state').removeAttr('disabled');
                    }
                })
            }
        })

        $('#sendNotification').on('click', function(e){
            e.preventDefault();
            if($('#exampleFormControlTextarea1').val().length > 0){
                $.ajax({
                    url : '<?=SITE_URL?>Admin/Admin/sendNotification',
                    type : 'post',
                    data: {
                        'countryId' : $('#country').val(),
                        'stateId' : $('#state').val(),
                        'categoryId' : $('#category').val(),
                        'audience' : $('#audience').val(),
                        'information': $('#exampleFormControlTextarea1').val()
                    },
                    success : function(data){
                        console.log(data);
                        $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                        $('#exampleFormControlTextarea1').val('');
                    } 
                })
            } else {
                $('#validationError').css('display', 'block');
            }
        })
    })
    </script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>