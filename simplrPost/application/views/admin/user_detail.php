<link href="<?= base_url('assets/css/plugins/slick.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/plugins/slick-theme.css') ?>" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="http://test.code-apex.com/KZN_Education/assets/css/view-bigimg.css">
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
<!-- Header -->
<div class="header bg-gradient-primary pb-8 pt-5 pt-md-8">
    <div class="container-fluid">
        <div class="header-body">
            <!-- back btn -->
            <div class="row">
                <div class="col-xl-10 back-btn-posotion">
                    <a href="javascript:history.go(-1);" class="back-btn" title="Back">
                        <img src="<?= base_url('assets/img/left-arrow.png') ?>" alt="Left-Arrow" class="back-icon">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<!--delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to delete this user?</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary deleteModalButton" id="<?=$userData[0]->userId ?>" onclick="deleteUser(this.id)">Delete</button>
        </div>
    </div>
    </div>
</div>
<!--block Modal -->
<div class="modal fade" id="blockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to block this user?</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary deleteModalButton" id="<?=$userData[0]->userId ?>" onclick="blockUserFromUserDetail(this.id)">Block</button>
        </div>
    </div>
    </div>
</div>
<!--unblock Modal -->
<div class="modal fade" id="unblockModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
        <div class="modal-header">

        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        </div>
        <div class="modal-body">
        <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to unblock this user?</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary deleteModalButton" id="<?=$userData[0]->userId ?>" onclick="unblockUser(this.id)">Unblock</button>
        </div>
    </div>
    </div>
</div>
<!-- Page content -->
<div class="container-fluid mt--7 wrap" id="mainBody">
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="card card-profile shadow">
                <div class="row justify-content-center align-item-center">
                    <div class="col-lg-3">
                        <div class="card-profile-image text-center">
                            <a>
                                <img data-enlargable style='cursor: zoom-in;object-fit:cover;' src="<?= BASE_URL . 'uploads/' . $userData[0]->profilePicURL ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>assets/img/user_default.png'" class="rounded-circle" width="270px" height="180px">
                            </a>
                            <h3 class=" mt-3">
                                <?= $userData[0]->name ?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body pt-0 pt-md-4">
                            <div class="text-center">
                                <!-- <div class="h5 font-weight-300">
                                    <i class="ni ni-pin-3 mr-2"></i>Bucharest, Romania
                                </div> -->
                                <div class="h3 mt-1">
                                    <i class="ni ni-single-02 mr-2"></i> <?= $userData[0]->userName ?>
                                </div>
                                <div class="h5 mt-1">
                                    <i class="far fa-envelope mr-2"></i> <?= $userData[0]->emailId ?>
                                </div>
                                <div class="h5 mt-1">
                                    <i class="ni ni-mobile-button mr-2"></i> <?= $userData[0]->contactNumber ?>
                                </div>
                            </div>
                            <div class="row justify-content-center pt-5">
                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deleteModal">
                                    Delete This User
                                </button>
                                <?php
                                if($userData[0]->status == 1){
                                    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#blockModal">
                                        Block This User
                                    </button>';
                                } else{
                                    echo '<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#unblockModal">
                                        Unblock This User
                                    </button>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-green border-0">
                    <div class="row">
                        <div class="col-12">
                            <h2 class="mb-0 text-white">Private Address</h2>
                        </div>
                    </div>
                </div>


                <?php
                if(count($privateAddressDetails) == 0){
                    echo "<label class='h3 green  mb-0 mt-2'>No private address available</label>";
                } else {
                for($i = 0; $i < count($privateAddressDetails); $i++){?>
                <div class="card-body">
                    <h6 class="heading-small mb-4">Address <?= $i + 1 ?></h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-center">
                                    <img data-enlargable style='cursor: zoom-in; border-radius:50%;' class="address-img" src="<?= BASE_URL . 'uploads/' . $privateAddressDetails[$i]->imageURL ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>assets/img/building_placeholder.png'">
                                    <label class="h3 green  mb-0 mt-2"><?php if(!empty($privateAddressDetails[$i]->shortName)){echo $privateAddressDetails[$i]->shortName; } else {echo 'Not Available';} ?></label>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Address</label>
                                    <p class="text-muted h4 font-weight-300"><?php if(!empty($privateAddressDetails[$i]->address)){echo $privateAddressDetails[$i]->address; }else {echo 'Not Available';} ?></p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6">
                                        <label class="form-control-label" for="input-first-name">Plus Code</label>
                                        <p class="text-muted h4 font-weight-300"><?= $privateAddressDetails[$i]->plusCode ?></p>
                                    </div>
                                    <div class="form-group qr-code col-lg-6 col-md-6 text-right">
                                        <img src="<?= BASE_URL . 'uploads/' . $privateAddressDetails[$i]->qrCodeURL ?>" onerror="this.onerror=null;this.src='http:/\/www.v3b.com/wp-content/uploads/2011/11/QR_Code.jpg'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= $privateAddressDetails[$i]->latitude . ',' . $privateAddressDetails[$i]->longitude ?>" target="_blank">View on map</a>
                                </div>
                            </div>

                        </div>
                    </div>
                    <hr class="my-4" />
                    <!-- Address -->
                    <h6 class="heading-small text-muted mb-4">Contact information</h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-last-name">Contact Number</label>
                                    <?php $numbers = preg_split("/\,/", $privateAddressDetails[$i]->contactNumbers); ?>
                                    <?php if(count($numbers) == 0){ echo 'Not Available';}else {foreach ($numbers as $number) : ?>
                                    <p class="text-muted h4 font-weight-300"><?= $number ?></p>
                                    <?php endforeach; } ?>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-last-name">Email</label>
                                    <p class="text-muted h4 font-weight-300"><?php if(!empty($privateAddressDetails[$i]->emailId)){echo $privateAddressDetails[$i]->emailId; }else {echo 'Not Available';} ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 divider">
                    </div>
                </div>
                <?php }} ?>


            </div>

        </div>

        <div class="col-xl-6">
            <div class="card bg-secondary shadow">
                <div class="card-header bg-green border-0">
                    <div class="row align-items-center">
                        <div class="col-12">
                            <h2 class="mb-0 text-white">Public Address</h2>
                        </div>
                    </div>
                </div>

                <?php
                if(count($publicAddressDetails) == 0){
                    echo "<label class='h3 green  mb-0 mt-2'>No public address available</label>";
                } else {
                for($i = 0; $i < count($publicAddressDetails); $i++){?>
                <div class="card-body">
                    <h6 class="heading-small mb-4">Address <?= $i + 1 ?></h6>
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-center">
                                    <img data-enlargable style='cursor: zoom-in; border-radius:50%;' class="address-img" src="<?= BASE_URL . 'uploads/' .$publicAddressDetails[$i]->logoURL ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>assets/img/building_placeholder.png'" style="border-radius:50%">
                                    <label class="h3 green  mb-0 mt-2"><?php if(!empty($publicAddressDetails[$i]->shortName)){echo $publicAddressDetails[$i]->shortName; }else {echo 'Not Available';} ?></label>
                                    <p class="text-muted h4 small"><?= $publicAddressDetails[$i]->categoryName ?></p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Address</label>
                                    <p class="text-muted h4 font-weight-300"><?php if(!empty($publicAddressDetails[$i]->address)){echo $publicAddressDetails[$i]->address; }else {echo 'Not Available';} ?></p>
                                </div>
                            </div>
                            <hr class="my-4" />
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <a href="javascript:void(0)" id="<?= $publicAddressDetails[$i]->addressId ?>" onclick="showModalData(this.id)">View More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php }} ?>

            </div>
        </div>
    </div>

    <!-- Full Height Modal Right -->
    <div class="modal fade right" id="fullHeightModalRight" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-full-height modal-right" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title w-100" id="myModalLabel">Public Address</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-0" id="modalBody">
                    <!-- <div class="card-header border-0">
                        <div class="row align-items-center">
                            <div class="col-12">
                                <h2 class="mb-0">Public Address</h2>
                            </div>
                        </div>
                    </div> -->
                    <div class="card-body wrap">
                        <!-- <h6 class="heading-small mb-4 address-number">Address 1</h6> -->
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group text-center">
                                        <span id="logoImage"></span>
                                        <label class="h3 green  mb-0 mt-2"><span id="publicAddressName"></span></label>
                                        <p class="text-muted h4 small"><span id="publicAddressCategory"></span></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Address</label>
                                        <p class="text-muted h4 font-weight-300"><span id="publicAddress"></span></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-email">Landmark</label>
                                        <p class="text-muted h4 font-weight-300"><span id="landmark"></span></p>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="row">
                                        <div class="form-group col-lg-6 col-md-6">
                                            <label class="form-control-label" for="input-first-name">Plus Code</label>
                                            <p class="text-muted h4 font-weight-300">
                                                <span id="publicAddressPlusCode">

                                                </span>
                                            </p>
                                        </div>
                                        <div class="form-group qr-code col-lg-6 col-md-6 text-right" id="qrImage">

                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <a href="" id="viewOnMapButton" target="_blank">View on map</a>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <hr class="my-4" />
                        <!-- Address -->
                        <h6 class="heading-small text-muted mb-4">Contact information</h6>
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-last-name">Contact Number</label>
                                        <span id="publicAddressContactNumber">

                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="form-group">
                                        <label class="form-control-label" for="input-last-name">Email</label>
                                        <p class="text-muted h4 font-weight-300"><span id="publicAddressEmailId">

                                            </span></p>
                                    </div>
                                </div>
                                <div class="col-lg-12 social-icon-modal" id="socialIcons">
                                    <!-- fb, twitter, insta, lnkedin, websiteURL -->
                                </div>
                            </div>
                        </div>

                        <hr class="my-4" />
                        <!-- Services -->
                        <h6 class="heading-small text-muted mb-4">Services</h6>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <div class="row" id="publicAddressServices">

                                </div>
                            </div>
                        </div>
                        <h6 class="heading-small text-muted mb-4">Service Description</h6>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <div class="row" id="publicAddressServiceDescription">

                                </div>
                            </div>
                        </div>
                        <hr class="my-4" />
                        <!-- Description -->
                        <h6 class="heading-small text-muted mb-4">Description</h6>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <div class="row" id="publicAddressDescription">

                                </div>
                            </div>
                        </div>

                        <hr class="my-4" />
                        <!-- Availability -->
                        <h6 class="heading-small text-muted mb-4">Availability Hours</h6>
                        <div class="pl-lg-4">
                            <div class="form-group">
                                <div id="publicAddressWeekDays">
                                    <div class="time-table">
                                        <span>Sunday</span>
                                        <span>Closed</span>
                                    </div>
                                </div>


                                <!-- delivery -->
                                <!-- for available -->
                                <div class="time-table mt-3" id="publicAddressDeliveryStatus">

                                </div>
                            </div>
                        </div>

                        <hr class="my-4" />
                        <!-- Availability -->
                        <h6 class="heading-small text-muted mb-4">Images</h6>
                        <div class="pl-lg-4">
                            <div class="image-slider text-center" id='imageSlider'>

                            </div>
                            <div class="form-group">
                                <div class="row" id="publicAddressImages">

                                </div>
                            </div>
                        </div>

                        <hr class="my-4" />
                        <!-- Availability -->
                        <div class="pl-lg-4">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <a href="" id="viewAddressReports">View All Reports</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- Full Height Modal Right -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url('assets/js/plugins/slick.min.js') ?>"></script>
    <script src="<?= base_url() ?>assets/js/modalData.js"></script>


    <script>
        $(document).ajaxStart(function() {
            $('#loader').addClass('loader');
            $('#mainBody').css('z-index', '-1');
            $('#loader-div').css('z-index', '10');
        });

        $(document).ajaxComplete(function() {
            $('#loader').removeClass('loader');
            $('#mainBody').css('z-index', '1');
            $('#loader-div').css('z-index', '-1');
        });

        $('.image-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000
        });
        $('#fullHeightModalRight').on('shown.bs.modal', function() {
            $("#fullHeightModalRight").scrollTop(0);
            // $('.modal').animate({scrollTop:$('#modalBody').position().top}, 'slow')
            $('.image-slider')[0].slick.refresh();
            $('.dynamicImages').addClass('img-enlargable').click(function() {
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
        });
        $('#fullHeightModalRight').on('hidden.bs.modal', function () {
            $('div.item').remove();
            $('.image-slider').slick('unslick');
            $('.image-slider').empty();
        });
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
        function deleteUser(id) {
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/deleteUser',
                data: {
                    'userId': id
                },
                success: function(data) {
                    window.location.href = "<?=SITE_URL?>Admin/Admin/userListingView";
                }
            });
        }
        function blockUserFromUserDetail(id){
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/blockUserFromUserDetail',
                data: {
                    'userId': id
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        }
        function unblockUser(id){
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/unblockUser',
                data: {
                    'userId': id
                },
                success: function(data) {
                    window.location.reload();
                }
            });
        }

        
    </script>
