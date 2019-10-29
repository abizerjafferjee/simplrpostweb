
<link href="<?= base_url('assets/css/plugins/slick.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/plugins/slick-theme.css') ?>" rel="stylesheet" />
<!-- Header -->
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
    a.disabled {
        pointer-events: none;
    }
</style>
<div id="loader-div">
    <div id="loader">

    </div>
</div>
<style>
.disabled{
    pointer-events: none;
}
</style>
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
                Address Unblocked Successfully
                </h2>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal" onclick="location.reload()">Close</button>
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
        <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to delete this business?</h3>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary deleteModalButton" id="<?= $address[0]->addressId ?>" onclick="deleteBusiness(this.id)">Delete</button>
        </div>
    </div>
    </div>
</div>
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

<!-- Page content -->
<div class="container-fluid mt--7 wrap">
    <div class="row">
        <div class="col-xl-12 mb-3">
            <div class="card card-profile shadow">
                <div class="row justify-content-center align-item-center">
                    <div class="col-lg-3">
                        <div class="card-profile-image text-center">
                            <a>
                                <img data-enlargable style='cursor: zoom-in;object-fit:cover' src="<?= base_url('uploads/' . $address[0]->profilePicURL) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>assets/img/user_default.png'" class="rounded-circle" width="270px" height="180px">
                            </a>
                            <h3 class=" mt-3">
                                <?php echo !empty($address[0]->name) ? $address[0]->name : 'Not available'?>
                            </h3>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="card-body pt-0 pt-md-4">
                            <div class="text-center">
                                <div class="h3 mt-1">
                                    <i class="ni ni-single-02 mr-2"></i> 
                                    <?php echo !empty($address[0]->userName) ? $address[0]->userName : 'Not available'?>
                                </div>
                                <div class="h5 mt-1">
                                    <i class="far fa-envelope mr-2"></i> 
                                    <?php echo !empty($address[0]->userEmailId) ? $address[0]->userEmailId : 'Not available'?>
                                </div>
                                <div class="h5 mt-1">
                                    <i class="ni ni-mobile-button mr-2"></i> 
                                    <?php echo !empty($address[0]->userContactNumber) ? $address[0]->userContactNumber : 'Not available'?>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php if($report == 1){?>
        <div class="row">
            <div class="col-12">
                <div class="card shadow">
                    <div class="card-body wrap">
                        <div class="pl-lg-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Block Date</label>
                                        <p class="text-muted h4 font-weight-300"><?= $address[0]->blockDate ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-control-label">Block Type</label>
                                        <p class="text-muted h4 font-weight-300"><?php echo ($address[0]->isUnblockable == 1) ? 'Temporary' : 'Permanent' ?></p>
                                    </div>
                                </div>
                                <div class="col-md-6" style="<?php echo ($address[0]->status == -5) ? 'display : none' : ''?>">
                                    <div class="form-group">
                                        <label class="form-control-label">Status</label>
                                        <p class="text-muted h4 font-weight-300">Unblocked Now</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <?php
                                        if($address[0]->blockDuration > 0){
                                            echo "<div class='form-group'><label class='form-control-label'>Block Duration</label><p class='text-muted h4 font-weight-300'>".$address[0]->blockDuration." Days</p><div>";
                                        }
                                    ?>
                                </div>
                                <div class="form-group mt-4" style="display : <?php echo ($address[0]->status == 1) ? 'none' : 'block'?>">
                                    <button class="btn btn-primary" id="unblockAddress" data-id="<?= $address[0]->addressId?>">Unblock Address</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    <div class="row <?php if($report == 1){echo 'mt-3';}?>">
        <div class="col">
            <div class="card shadow">
                <div class="card-body wrap">
                    <div class="pl-lg-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group text-center">
                                    <img data-enlargable style='cursor: zoom-in; border-radius:50%;' class="address-img" src="<?= base_url('uploads/' . $address[0]->logoURL) ?>" onerror="this.onerror=null;this.src='<?= BASE_URL ?>assets/img/building_placeholder.png'">
                                    <label class="h3 green  mb-0 mt-2">
                                    <?php echo !empty($address[0]->shortName) ? $address[0]->shortName : 'Not available'?></p>
                                    <p class="text-muted h4 small">
                                    <?php echo !empty($address[0]->categoryName) ? $address[0]->categoryName : 'Not available'?></p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Address</label>
                                    <p class="text-muted h4 font-weight-300">
                                    <?php echo !empty($address[0]->address) ? $address[0]->address : 'Not available'?></p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-email">Landmark</label>
                                    <p class="text-muted h4 font-weight-300">
                                    <?php echo !empty($address[0]->landmark) ? $address[0]->landmark : 'Not available'?></p>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="row">
                                    <div class="form-group col-lg-6 col-md-6">
                                        <label class="form-control-label" for="input-first-name">Plus Code</label>
                                        <p class="text-muted h4 font-weight-300"><?= $address[0]->plusCode ?></p>
                                    </div>
                                    <div class="form-group qr-code col-lg-6 col-md-6 text-right">
                                        <img src="<?= BASE_URL . 'uploads/' . $address[0]->qrCodeURL ?>" onerror="this.onerror=null;this.src='http:/\/www.v3b.com/wp-content/uploads/2011/11/QR_Code.jpg'">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <a href="https://www.google.com/maps/search/?api=1&query=<?= $address[0]->latitude . ',' . $address[0]->longitude ?>" target="_blank">View on map</a>
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

                                    <?php
                                    if ($address[0]->contactNumbers != null) {
                                        $numbers = explode(',', $address[0]->contactNumbers);
                                        foreach ($numbers as $number) {
                                            echo "<p class='text-muted h4 font-weight-300'>$number</p>";
                                        }
                                    } else {
                                        echo "Not available";
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="form-control-label" for="input-last-name">Email</label>
                                    <p class="text-muted h4 font-weight-300">
                                    <?php echo !empty($address[0]->emailId) ? $address[0]->emailId : 'Not available'?></p>
                                </div>
                            </div>
                            <div class="col-lg-12 social-icon-modal">
                                <?php
                                // $address[0]->facebookURL = $address[0]->twitterURL = $address[0]->linkedInURL = $address[0]->instagramURL = 'http://google.com';
                                    if(empty($address[0]->facebookURL)){
                                        echo '<span style="cursor:not-allowed"><a target="_blank" class="disabled"><i class="fab fa-facebook-f"></i></a></span>';
                                    }
                                    else{
                                        echo "<a href='".$address[0]->facebookURL."' target='_blank'><i class='fab fa-facebook-f'></i></a>";
                                    }
                                    if(empty($address[0]->twitterURL)){
                                        echo '<span style="cursor:not-allowed"><a target="_blank" class="disabled"><i class="fab fa-twitter"></i></a></span>';
                                    }
                                    else{
                                        echo "<a href='".$address[0]->twitterURL."' target='_blank'><i class='fab fa-twitter'></i></a>";
                                    }
                                    if(empty($address[0]->linkedInURL)){
                                        echo '<span style="cursor:not-allowed"><a target="_blank" class="disabled"><i class="fab fa-linkedin-in"></i></a></span>';
                                    }
                                    else{
                                        echo "<a href='".$address[0]->linkedInURL."' target='_blank'><i class='fab fa-linkedin-in'></i></a>";
                                    }
                                    if(empty($address[0]->instagramURL)){
                                        echo '<span style="cursor:not-allowed"><a target="_blank" class="disabled"><i class="fab fa-instagram"></i></a></span>';
                                    }
                                    else{
                                        echo "<a href='".$address[0]->instagramURL."' target='_blank'><i class='fab fa-instagram'></i></a>";
                                    }
                                    if(empty($address[0]->websiteURL)){
                                        echo '<span style="cursor:not-allowed"><a class="disabled"><img style="cursor:not-allowed" src="'.BASE_URL.'assets/img/icons/web-link.png" alt="" class="social-img"></a></span>';
                                    }
                                    else{
                                        echo "<a href='".$address[0]->websiteURL."' target='_blank'style='cursor:pointer'><img src='".BASE_URL."assets/img/icons/web-link.png' alt='' class='social-img'></a>";
                                    }
                                    // <a href="#"> <img src="assets/img/icons/web-link.png" alt="" class="social-img"></a>
                                ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" />
                    <!-- Services -->
                    <h6 class="heading-small text-muted mb-4">Services Description</h6>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <div class="row" id="publicAddressImages">
                            <p class="text-muted h4 font-weight-300"><?php echo !empty($address[0]->serviceDescription) ? $address[0]->serviceDescription : 'Not available'?></p>
                            </div>
                        </div>
                    </div>
                    <h6 class="heading-small text-muted mb-4">Services</h6>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <div class="row" id="publicAddressServices">
                                <br>
                                <?php
                                    if ($address[0]->serviceURL != null) {
                                        $services = explode(',', $address[0]->serviceURL);
                                        foreach ($services as $service) {
                                            $arr = explode('.', $service);
                                            if (end($arr) == 'pdf') {
                                                echo "<div class='col-lg-3 p-2'><a href='" . BASE_URL . "uploads/" . $service . "' target='_blank'><img class='address-img' src='" . BASE_URL . "assets/img/pdfPlaceholder.png' id='pdf'><a></div>";
                                            } else if (end($arr) == 'doc' || end($arr) == 'docx') {
                                                echo "<div class='col-lg-3 p-2'><a href='" . BASE_URL . "uploads/" . $service . "' target='_blank'><img class='address-img' src='" . BASE_URL . "assets/img/docPlaceholder.png' id='doc'><a></div>";
                                            } else {
                                                echo "<div class='col-lg-3 p-2'><img data-enlargable style='cursor: zoom-in' class='address-img' src='" . BASE_URL . "uploads/" . $service . "'></div>";
                                                // onerror="this.onerror=null;this.src='http:/\/www.v3b.com/wp-content/uploads/2011/11/QR_Code.jpg'"
                                            }
                                        }
                                    } else {
                                        echo '<p class="text-muted h4 font-weight-300">Not available</p>';
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" />
                    <!-- Description -->
                    <h6 class="heading-small text-muted mb-4">Description</h6>
                    <div class="pl-lg-4">
                        <div class="form-group">
                            <div class="row" id="publicAddressServices">
                            <p class="text-muted h4 font-weight-300"><?php echo !empty($address[0]->description) ? $address[0]->description : 'Not available'?></p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4" />
                    <!-- Availability -->
                    <h6 class="heading-small text-muted mb-4">Availability Hours</h6>
                    <div class="pl-lg-4 col-lg-5">
                        <div class="form-group">
                            <?php
                            if ($weekDays != null) {
                                foreach ($weekDays as $weekDay) {
                                    if ($weekDay->isOpen == 0) {
                                        echo "<div class='time-table'><span>$weekDay->dayName</span><span>Closed</span></div>";
                                    } else {
                                        echo "<div class='time-table'><span>$weekDay->dayName</span><span>$weekDay->openTime- $weekDay->closeTime</span></div>";
                                    }
                                }
                            } else {
                                echo "<div class='row' id='publicAddressServices'><p class='text-muted h4 font-weight-300'>Not available</p></div>";
                            }
                            ?>
                            <!-- delivery -->
                            <!-- for available -->
                            <div class="time-table mt-3">
                                <?php
                                if ($address[0]->isDeliveryAvailable == 0) {
                                    echo "<span class='text-muted'>Delivery</span><span>Not Available <i class='ni ni-fat-remove ml-2 text-danger'></i></span>";
                                } else {
                                    echo "<span class='text-muted'>Delivery</span><span>Available <i class='ni ni-check-bold ml-2 green'></i></span>";
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <hr class="my-4" />
                    <!-- Availability -->
                    <h6 class="heading-small text-muted mb-4">Images</h6>
                    <div class="pl-lg-4">
                            <?php
                            if ($address[0]->imageURL != null) {
                                $arrImageURL = explode(',', $address[0]->imageURL);
                                echo '<div class="image-slider">';
                                foreach ($arrImageURL as $image) {
                                    echo "<div class='item'><div><img data-enlargable style='cursor: zoom-in' class='address-img' src='" . BASE_URL . "uploads/" . $image . "'  class='img-fluid' onerror='this.onerror=null;this.src=\"". BASE_URL ."assets/img/building_placeholder.png\"'></div></div>";
                                }
                                echo '</div>';
                            } else {
                                echo "<div class='form-group'><div class='row' id='publicAddressServices'><p class='text-muted h4 font-weight-300'>Not available</p></div></div>";
                            }
                            ?>
                        </div>
                    </div>
                    <?php if(!empty($reportCount)){?>
                        <hr class="my-4" />
                        <div class="col-lg-12 text-center">
                            <div class="form-group">
                                <a href="<?=SITE_URL .'business-reports/'.urlencode(base64_encode($address[0]->addressId)) ?>">View All Reports</a>
                            </div>
                        </div>
                    <?php }?>
                </div>
                <div class="row justify-content-center pt-5">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#deleteModal">
                        Delete This Business
                    </button>
                </div>

            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" type="text/css" href="http://test.code-apex.com/KZN_Education/assets/css/view-bigimg.css">
    <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
    <script type="text/javascript" src="https://urcommunitycares.com/assets/web_files/vendor/jquery/moment.js"></script>
    <script type="text/javascript" src="http://test.code-apex.com/KZN_Education/assets/js/view-bigimg.js"></script>

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
        $('#unblockAddress').click(function(e){
            e.preventDefault();
            $addressId = $(this).attr('data-id');
            $.ajax({
                url : '<?= SITE_URL ?>Admin/Admin/unblockAddress',
                type : 'post',
                data : {
                    'addressId' : $addressId
                },
                success : function(data){
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                }
            })
        })
        $('.image-slider').slick({
            infinite: true,
            slidesToShow: 3,
            slidesToScroll: 1,
            autoplay: true,
            autoplaySpeed: 2000,
        });

        $(window).on('shown.bs.modal', function() {
            $('.image-slider')[0].slick.refresh()

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
        function deleteBusiness(id) {
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/deleteBusiness',
                data: {
                    'businessId': id
                },
                success: function(data) {
                    console.log(data);
                    window.location.replace('<?=SITE_URL?>Admin/Admin/businessListingView');
                }
            });
        }
    </script>
    <div id="iv-container" class="iv-container" style="display: none;">
        <div class="iv-loader" style="display: none;"></div>
        <div class="iv-image-view">
            <div class="iv-image-wrap"><img class="iv-large-image" src="" style="display: block; width: 657px; height: 657px; left: 42.5px; top: 0px; max-width: none; max-height: none;"></div>
            <div class="iv-close"></div>
        </div>
    </div>