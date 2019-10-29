<link href="<?=base_url('assets/css/plugins/slick.css')?>" rel="stylesheet" />
<link href="<?=base_url('assets/css/plugins/slick-theme.css')?>" rel="stylesheet" />
<link rel="stylesheet" href="<?=base_url('assets/css/plugins/summernote/dist/summernote-bs4.css')?>">
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
                <h2 class="modal-title pb-4 text-center font-weight-bold" style="color:#1bac71" id="exampleModalLabel">Content Updated Successfully</h2>
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
    <div class="row" style="display:none">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">About Simplr Post</h3>
                </div>
                <div class="row px-3">
                    <div class="col-lg-12 col-md-12">
                        <form  method="post" class="myform" enctype="multipart/form-data" accept-charset="utf-8">
                            <textarea class="summernoteExample" name="text">
                            </textarea>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">About Simplr Post</h3>
                </div>
                <div class="row px-3">
                    <div class="col-lg-12 col-md-12">
                        <form  method="post" class="myform" enctype="multipart/form-data" accept-charset="utf-8">
                            <textarea class="summernoteExample" name="text" id="aboutUs"><?= $about['content'] ?>
                            </textarea>
                            <div class="py-5 text-center">
                                <input type="submit" class="btn btn-primary" value="Update" id="updateAboutUs">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Privacy Policy</h3>
                </div>
                <div class="row px-3">
                    <div class="col-lg-12 col-md-12">
                        <form method="post" class="myform" enctype="multipart/form-data" accept-charset="utf-8">
                            <textarea class="summernoteExample" name="text" id="privacyPolicy"><?= $privacyPolicy['content'] ?>
                            </textarea>
                            <div class="py-5 text-center">
                                <input type="submit" class="btn btn-primary" value="Update" id="updatePrivacyPolicy">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row mt-4">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Terms & Conditions</h3>
                </div>
                <div class="row px-3">
                    <div class="col-lg-12 col-md-12">
                        <form method="post" class="myform" enctype="multipart/form-data" accept-charset="utf-8">
                            <textarea class="summernoteExample" name="text" id="termsConditions"><?= $termsConditions['content'] ?>
                            </textarea>
                            <div class="py-5 text-center">
                                <input type="submit" class="btn btn-primary" value="Update" id="updateTermsConditions">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Height Modal Right -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url() ?>assets/js/modalData.js"></script>
    <script src="<?= base_url('assets/js/plugins/slick.min.js') ?>"></script>

    <script type="text/javascript" src="<?=base_url('assets/js/plugins/summernote/dist/summernote-bs4.min.js')?>"></script>
    <script type="text/javascript">
        /*Summernote editor*/
        $('.summernoteExample').summernote({
            height: 300,
            tabsize: 2,
            fontNames: ['Serif', 'Sans', 'Arial', 'Arial Black', 'Courier', 'Courier New', 'Comic Sans MS', 'Helvetica', 'Impact', 'Lucida Grande', 'Sacramento', 'calibri']
        });
        $('.summernoteExample').summernote('fontSize', 16);
        $('.summernoteExample').summernote('lineHeight', 20);
        $('div.note-editable:first-child').css('display','none');
    </script>
    <script>
        $(document).ajaxStart(function() {
            $('#loader').addClass('loader');
            $('#loader-div').css('z-index', '10');
        });

        $(document).ajaxComplete(function() {
            $('#loader').removeClass('loader');
            $('#loader-div').css('z-index', '-1');
        });

        $('#updateAboutUs').click(function(e){
            e.preventDefault();
            $.ajax({
                url : '<?= SITE_URL ?>Admin/Admin/updateAboutUs',
                type : 'post',
                data : {
                    'content' : $('#aboutUs').val()
                },
                success : function(data){
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                }
            })
        })
        $('#updatePrivacyPolicy').click(function(e){
            e.preventDefault();
            $.ajax({
                url : '<?= SITE_URL ?>Admin/Admin/updatePrivacyPolicy',
                type : 'post',
                data : {
                    'content' : $('#privacyPolicy').val()
                },
                success : function(data){
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                }
            })
        })
        $('#updateTermsConditions').click(function(e){
            e.preventDefault();
            $.ajax({
                url : '<?= SITE_URL ?>Admin/Admin/updateTermsConditions',
                type : 'post',
                data : {
                    'content' : $('#termsConditions').val()
                },
                success : function(data){
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                }
            })
        })
    </script>