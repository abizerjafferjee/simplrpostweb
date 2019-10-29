<link href="<?= base_url('assets/css/plugins/slick.css') ?>" rel="stylesheet" />
<link href="<?= base_url('assets/css/plugins/slick-theme.css') ?>" rel="stylesheet" />
<style>
    .stars-outer {
        display: inline-block;
        position: relative;
        font-family: FontAwesome;
    }

    .stars-outer::before {
        content: "\f005 \f005 \f005 \f005 \f005";
    }

    .stars-inner {
        position: absolute;
        top: 0;
        left: 0;
        white-space: nowrap;
        overflow: hidden;
        width: 0;
    }

    .stars-inner::before {
        content: "\f005 \f005 \f005 \f005 \f005";
        color: #f8ce0b;
    }

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
                    <h3 class="mb-0">
                        Feedback
                    </h3>
                </div>
            </div>

            <div class="table-responsive card shadow" id="feedbackListing">
                <table class="table align-items-center table-flush" id="feedbackList">
                    <thead class="thead-light">
                        <tr style="max-width:100%">
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">User Image</th>
                            <th scope="col">Name</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Description</th>
                            <th scope="col">Reporter Detail</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="card-footer py-4">
                <nav aria-label="...">
                    <ul class="pagination justify-content-end mb-0" id="pagination">


                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script type='text/javascript'>
        
        $(document).ready(function() {
            $(document).ajaxStart(function() {
                $('#loader').addClass('loader');
                $('#loader-div').css('z-index', '10');
            });

            $(document).ajaxComplete(function() {
                $('#loader').removeClass('loader');
                $('#loader-div').css('z-index', '-1');
            });

            // Detect pagination click
            $('#pagination').on('click', 'a', function(e) {
                e.preventDefault();
                $pageNo = $(this).attr('data-ci-pagination-page');
                loadPagination($pageNo);
            });

            loadPagination(0);
            // Load pagination
            function loadPagination(pagno) {
                $.ajax({
                    url: '<?=SITE_URL?>Admin/Admin/loadFeedback/' + pagno,
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if(data.result == ''){
                            $('#feedbackList tbody').empty();
                            $('#feedbackList tbody').append("<tr><td colspan=6 class='text-center text-danger h2'>No data Found</td></tr>");
                        } else {
                            if(pagno != 0){
                                $('html, body').animate(
                                {
                                    scrollTop: $('#feedbackListing').offset().top,
                                },
                                200,
                                'linear'
                                )
                            }
                            $('#pagination').html(data.pagination);
                            createTable(data.result, data.row);
                        }
                    }
                });
            }

            // Create table list
            function createTable(result, sno) {

                serialNumber = Number(sno);
                $('#feedbackList tbody').empty();
                for (index in result) {
                    $userName = result[index].userName;
                    $userId = result[index].userId;
                    // $date = new Date(result[index].createDate);
                    // $newDate = $date.getDate();
                    // $newMonth = $date.getMonth() + 1;
                    // if($date.getDate() < 10){
                    //     $newDate = '0'+$date.getDate();
                    // }
                    // if($date.getMonth() < 9){
                    //     $newMonth = '0'+ ($date.getMonth() + 1);
                    // }
                    // $fullDate = $newDate + '-' + $newMonth + '-' + $date.getFullYear();
                    $fullDate = result[index].createDate;
                    $description = result[index].content;
                    $rating = result[index].rating;
                    $profilePicURL = result[index].profilePicURL;
                    serialNumber += 1;

                    // for($i = 0; $ > $rating; $i++){
                    $ratingStars = $rating * "<i class='fas fa-star'></i>";
                    // }

                    var tr = "<tr>";
                    tr += "<td>" + serialNumber + "</td>";
                    tr += "<td>" + $fullDate + "</td>";
                    tr += "<td><div class='media align-items-center'><a class='avatar rounded-circle mr-3'><img style='object-fit:cover' alt='Image placeholder' src='<?= BASE_URL ?>uploads/" + $profilePicURL + "' onerror='this.onerror=null;this.src=\"<?=BASE_URL?>assets/img/user_default.png\"' width='100%' height='100%'></a></div></td>";
                    tr += "<td>" + $userName + "</td>";
                    tr += "<td>";
                    for ($i = 0; $i < $rating; $i++) {
                        tr += "<i class='fas fa-star'></i>";
                    }
                    for ($j = 0; $j < (5 - $rating); $j++) {
                        tr += "<i class='fas fa-star text-light'></i>";
                    }
                    tr += "</td>";
                    tr += "<td class='white-space-unset'><span style='max-width:100%'>" + $description + "</span></td>";
                    tr += "<td><a href='<?= SITE_URL ?>user-detail/"+ encodeURIComponent(window.btoa($userId)) +"' class='btn btn-icon btn-2 btn-primary' title='Detail'><span><i class='fas fa-eye'></i></span></a>";
                    tr += "</tr>";
                    $('#feedbackList tbody').append(tr);

                }
            }
        });
    </script>