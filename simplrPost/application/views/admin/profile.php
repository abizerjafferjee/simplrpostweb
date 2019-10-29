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
    z-index: 1;
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
.google-map{
    background-image:url("<?= BASE_URL ?>assets/img/green_map.png");
    
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    height:400px;
}
.bg-green_2{
    background: linear-gradient( rgba(0, 160, 91, 0.336), rgba(0, 160, 91, 0.336));
    z-index:1;
    height:400px;
    padding:0;
    margin:0;
}
.image-upload>input {
  display: none;
}
/* .updateImageMask{
  width: 100px;
  height: 100px;
  cursor: pointer;
  background-color: #E5E8E8;
  font-size: 12px;
  line-height: 150px;
  text-transform: uppercase;
  color: #000;
  text-align: center;
  border-radius: 0;
} */
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
        <h2 class="modal-title pb-4 text-center" id="exampleModalLabel"></h2>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
      </div>
    </div>
  </div>
</div>
<!-- Header -->
<div class="google-map">
<div class="bg-green_2"> </div>
<div class="header  pb-8 pt-5 pt-md-8"> 
  <div class="container-fluid">
    <div class="header-body">
      <!-- Card stats -->
      <div class="row">
      </div>
    </div>
  </div>
</div>
</div>
<!-- Page content -->
<div class="container-fluid mt--7">
  <div class="row">
    <div class="col-xl-4 order-xl-2 mb-5 mb-xl-0">
      <div class="card card-profile shadow">

        <div class="row justify-content-center">
          <div class="">
            <div class="card-profile-image">
              <img data-enlargable style='cursor: zoom-in;padding:10px;border-radius:50%;object-fit:cover' src="<?= BASE_URL . 'uploads/' . $data->profilePicURL ?>" onerror='this.onerror=null;this.src="<?= BASE_URL?>uploads/admin/admin_placeholder.jpg"' height="180px">
            </div>
            <div class="text-center py-4">
              <a href="" class="btn btn-danger" id="removeProfilePic">Remove Profile Pic</a>
            </div>
          </div>
        </div>

        <div class="card-body pt-0 pt-md-4">
          <div class="text-center">
            <div class="h3 mt-1">
              <i class="ni ni-single-02 mr-2"></i> <?= $data->userName ?>
            </div>
            <div class="h5 mt-1">
              <i class="far fa-envelope mr-2"></i> <?= $data->emailId ?>
            </div>
            <div class="h5 mt-1">
              <i class="ni ni-mobile-button mr-2"></i> <?= $data->contactNumber ?>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-xl-8 order-xl-1">
      <div class="card bg-secondary shadow">
        <div class="card-header bg-white border-0">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">My account</h3>
            </div>
          </div>
        </div>
        <div class="card-body">
          <form>
            <h6 class="heading-small text-muted mb-4">User information</h6>
            <div class="pl-lg-4">
              <input type="hidden" id="inputAdminId" class="form-control form-control-alternative" value="<?= $data->adminId ?>">
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-control-label" for="inputUserName">Username</label>
                    <input type="text" id="inputUserName" class="form-control form-control-alternative" value="<?= $data->userName ?>">
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group">
                    <label class="form-control-label" for="inputName">Name</label>
                    <input type="text" id="inputName" class="form-control form-control-alternative" value="<?= $data->name ?>">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-12">
                  <div class="form-group">
                    <label class="form-control-label" for="inputEmail">Email address</label>
                    <input type="email" id="inputEmail" class="form-control form-control-alternative" value="<?= $data->emailId ?>">
                  </div>
                </div>
              </div>
              <!-- <div class="row"> -->
                <div class="image-upload mb-2">
                  <label for="inputImage">
                    <img src="<?= BASE_URL . 'assets/img/icons/upload.png' ?>" width="100px" height="100px" id="previewImage">
                  </label>
                  <input type="file" id="inputImage" onchange="readURL(this);">
                </div>
              <!-- </div> -->
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group">
                    <a href="" class="btn btn-primary" id="updateAdminProfileButton">Save Changes</a>
                  </div>
                </div>
              </div>
            </div>
            <hr class="my-4" />

          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    $(document).ready(function() {

      $(document).ajaxStart(function() {
        $('#loader').addClass('loader');
        $('#loader-div').css('z-index', '10');
      });

      $(document).ajaxComplete(function() {
        $('#loader').removeClass('loader');
        $('#loader-div').css('z-index', '-1');
      });

      $('#updateAdminProfileButton').click(function(e) {
        e.preventDefault();
        var formData = new FormData();
        var files = $('#inputImage')[0].files[0];
        formData.append('adminId', $('#inputAdminId').val());
        formData.append('userName', $('#inputUserName').val());
        formData.append('emailId', $('#inputEmail').val());
        formData.append('name', $('#inputName').val());
        formData.append('file', files);
        $.ajax({
          url: '<?=SITE_URL?>Admin/Admin/updateAdminData',
          type: 'post',
          data: formData,
          contentType: false,
          processData: false,
          success: function(data) {
            if(data == -2){
              $('#exampleModalLabel').html('<p class="text-danger font-weight-bold">Image file type is not valid<p>');
              $('#modalCenter').modal({backdrop: 'static', keyboard: false});
            } else if(data == -1){
              $('#exampleModalLabel').html('<p class="text-danger font-weight-bold">Error occured, Please try again<p>');
              $('#modalCenter').modal({backdrop: 'static', keyboard: false});
            } else {
              window.location.reload();
            }
          }
        });
      })
      $('#removeProfilePic').click(function(e){
        e.preventDefault();
        $.ajax({
          url: '<?=SITE_URL?>Admin/Admin/removeProfilePic',
          type: 'post',
          data: {
            'adminId' : $('#inputAdminId').val()
          },
          success: function(data) {
            window.location.reload();
          }
        });
      })
    })
    $('img[data-enlargable]').addClass('img-enlargable').click(function() {
      var src = $(this).attr('src');
      $('<div>').css({
        background: 'RGBA(0,0,0,.5) url(' + src + ') no-repeat center',
        backgroundSize: 'contain',
        width: '100%',
        height: '100%',
        position: 'fixed',
        zIndex: '10000',
        top: '0',
        left: '0',
        cursor: 'zoom-out'
      }).click(function() {
        $(this).remove();
      }).appendTo('body');
    });
    function readURL(input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          $('#previewImage').attr('src', e.target.result);
          $('#previewImage').css('object-fit','cover');
          $('#previewImage').css('margin-left','0');
        }
        reader.readAsDataURL(input.files[0]);
      }
    }
  </script>