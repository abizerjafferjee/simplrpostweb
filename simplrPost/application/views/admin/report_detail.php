<link href="<?= base_url('assets/css/plugins/slick.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/plugins/slick-theme.css') ?>" rel="stylesheet" />
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
                <h2 class="modal-title pb-4 text-center font-weight-bold" style="color:#1bac71" id="exampleModalLabel">
                <!-- block message -->
                </h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal" onclick="window.location = '<?=SITE_URL?>/Admin/Admin/reportListingView'">Close</button>
            </div>
        </div>
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
                        <img src="<?=base_url('assets/img/left-arrow.png')?>" alt="Left-Arrow" class="back-icon">
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Page content -->
<div class="container-fluid mt--7 wrap">
    <div class="row">
        <div class="col-xl-12 mb-5">
            <div class="card card-profile shadow">

                <div class="row justify-content-center align-item-center">
                    <div class="col-lg-3">
                        <div class="card-profile-image text-center">
                            <a href="#">
                            <img data-enlargable style='cursor: zoom-in;object-fit:cover' src="<?=BASE_URL . 'uploads/' . $reportData[0]->profilePicURL?>" onerror="this.onerror=null;this.src='<?=BASE_URL?>assets/img/user_default.png'" class="rounded-circle" width="270px" height="180px">
                            </a>
                            <h3 class=" mt-3">
                                <?=$reportData[0]->name?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body pt-0 pt-md-4 justify-content-center">
                            <div class="text-center">
                                <div class="h5">
                                    <div class="h3 mt-1">
                                        <i class="ni ni-single-02 mr-2"></i> <?=$reportData[0]->userName?>
                                    </div>
                                    <div class="h5 mt-1">
                                        <i class="far fa-envelope mr-2"></i> <?=$reportData[0]->emailId?>
                                    </div>
                                    <div class="h5 mt-1">
                                        <i class="ni ni-mobile-button mr-2"></i> <?=$reportData[0]->contactNumber?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-4 pb-0">
                    <div class="col-lg-6 col-md-12">
                        <label class="px-2 py-2"><b>Reporter Name</b></label>
                        <p class="px-2"><?=$reportData[0]->reporterName?></p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <label class="px-2 py-2"><b>Reporter Email Id</b></label>
                        <p class="px-2"><?=$reportData[0]->reporterEmailId?></p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <label class="px-2 py-2"><b>Reporter Contact Number</b></label>
                        <p class="px-2"><?=$reportData[0]->reporterContactNumber?></p>
                    </div>
                    <div class="col-lg-6 col-md-12">
                        <label class="px-2 py-2"><b>Report Issue</b></label>
                        <p class="px-2"><?=$reportData[0]->issue?></p>
                    </div>
                    <div class="col-lg-12 col-md-12">
                        <label class="px-2 py-2"><b>Report Description</b></label>
                        <p class="px-2"><?=$reportData[0]->description?></p>
                        <a class="px-2" href="javascript:void(0)" id="<?=$reportData[0]->businessId?>" onclick="showModalData(this.id)">View business page</a>
                    </div>

                    <div class="col-lg-12 col-md-12 col-sm-12 mt-4">
                        <label class="px-2 py-2"><b>Take Action</b></label>
                    </div>
                    <div class="form-group col-lg-4 col-md-4 col-sm-9">
                        <div class="input-group input-group-alternative border">
                            <select class="form-control" id="reportAction">
                                <option value="-1">Action</option>
                                <option value="1">Block Business temporary</option>
                                <option value="2">Block Business permanent</option>
                                <option value="3">Block User Account</option>
                            </select>
                        </div>
                        <span id='actionValidation' class="validate_error">Please select an action<span>
                    </div>
                    <div class="form-group col-lg-2 col-md-2 col-sm-3" style="display:none" id="selectDays">
                        <div class="input-group input-group-alternative border">
                            <select class="form-control" id="blockDays">
                                <option value="7">7 days</option>
                                <option value="15">15 days</option>
                                <option value="30">30 days</option>
                                <option value="60">60 days</option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" value="<?=$reportData[0]->businessId?>" id="businessId">
                    <div class="form-group col-lg-2 col-md-2 col-sm-12">
                        <button class="btn btn-primary" id="takeAction" data-id='<?=$reportData[0]->reportId?>'>Submit</button>
                    </div>
                </div>
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
    <script src="<?= base_url() ?>assets/js/modalData.js"></script>
    <script src="<?= base_url('assets/js/plugins/slick.min.js') ?>"></script>


    <script>
        $(document).ajaxStart(function() {
            $('#loader').addClass('loader');
            $('#loader-div').css('z-index', '10');
        });

        $(document).ajaxComplete(function() {
            $('#loader').removeClass('loader');
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
        $('#reportAction').on('change', function(){
            if($(this).val() == 1){
                $('#selectDays').css('display', 'block');
                $('#actionValidation').css("display","none");
            } else {
                $('#selectDays').css('display', 'none');
            }
        })
        $('#takeAction').click(function(e){
            e.preventDefault();
            $addressId = 
            $addressId = $('#businessId').val();
            $reportId = $(this).attr('data-id');
            $action = $('#reportAction').val();
            if($action == -1){
                $('#actionValidation').css("display","block");
            } else if($action == 1){
                $days = $('#blockDays').val();
                reportActionFunction($reportId, $addressId, $days);
            } else if($action == 2){
                reportActionFunction($reportId, $addressId, '-1');
            } else {
                deleteUser($reportId, $addressId);
            }
        })
        function reportActionFunction(reportId, addressId, duration){
            $.ajax({
                url : '<?= SITE_URL ?>Admin/Admin/blockBusiness',
                type: 'post',
                data : {
                    'reportId' : reportId,
                    'addressId' : addressId,
                    'duration' : duration
                },
                success: function(data){
                    $('#exampleModalLabel').html('Address has been blocked successfully');
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false});
                }
            })
        }
        function deleteUser(reportId, addressId){
            $.ajax({
                url : '<?= SITE_URL ?>Admin/Admin/blockUser',
                type: 'post',
                data : {
                    'reportId' : reportId,
                    'addressId':addressId
                },
                success: function(data){
                    $('#exampleModalLabel').html('User has been blocked successfully');
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false});
                }
            })
        }
    </script>
