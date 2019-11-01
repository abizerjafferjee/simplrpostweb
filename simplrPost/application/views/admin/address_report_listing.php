<!DOCTYPE html>
<html lang="en">
<meta http-equiv="content-type" content="text/html;charset=utf-8" />

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= $title ?></title>
  <!-- Favicon -->
  <link href="<?= base_url('assets/img/brand/favicon.png') ?>" rel="icon" type="image/png">
  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
  <!-- Icons -->
  <link href="<?= base_url('assets/js/plugins/nucleo/css/nucleo.css') ?>" rel="stylesheet" />
  <link href = "https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.css" rel = "stylesheet">
  <link href="<?= base_url('assets/js/plugins/%40fortawesome/fontawesome-free/css/all.min.css') ?>" rel="stylesheet" />
  <!-- CSS Files -->
  <link href="<?= base_url('assets/css/argon-dashboard.min9f1e.css?v=1.1.0') ?>" rel="stylesheet" />
  <link href="<?= base_url('assets/css/custom.css') ?>" rel="stylesheet" />
  <style>
    .page-item a {
      position: relative;
      display: block;
      padding: .5rem .75rem;
      margin-left: -1px;
      line-height: 1.25;
      color: #8898aa;
      background-color: #fff;
      border: 1px solid #dee2e6
    }

    .page-item a:hover {
      z-index: 2;
      color: #8898aa;
      text-decoration: none;
      background-color: #dee2e6;
      border-color: #dee2e6
    }

    .page-item a:focus {
      z-index: 2;
      outline: 0;
      box-shadow: none
    }

    .page-item:first-child a {
      margin-left: 0;
      border-top-left-radius: .375rem;
      border-bottom-left-radius: .375rem
    }

    .page-item:last-child a {
      border-top-right-radius: .375rem;
      border-bottom-right-radius: .375rem
    }

    .page-item.active a {
      z-index: 1;
      color: #fff;
      background-color: #1bac71;
      border-color: #1bac71
    }

    .page-item.disabled a {
      color: #8898aa;
      pointer-events: none;
      cursor: auto;
      background-color: #fff;
      border-color: #dee2e6
    }

    .pagination-lg a {
      padding: .75rem 1.5rem;
      font-size: 1.25rem;
      line-height: 1.5
    }

    .pagination-lg .page-item:first-child a {
      border-top-left-radius: .4375rem;
      border-bottom-left-radius: .4375rem
    }

    .pagination-lg .page-item:last-child a {
      border-top-right-radius: .4375rem;
      border-bottom-right-radius: .4375rem
    }

    .pagination-sm a {
      padding: .25rem .5rem;
      font-size: .875rem;
      line-height: 1.5
    }

    .pagination-sm .page-item:first-child a {
      border-top-left-radius: .25rem;
      border-bottom-left-radius: .25rem
    }

    .pagination-sm .page-item:last-child a {
      border-top-right-radius: .25rem;
      border-bottom-right-radius: .25rem
    }

    .page-item.active a{
      box-shadow: 0 7px 14px rgba(50, 50, 93, .1), 0 3px 6px rgba(0, 0, 0, .08)
    }

    .page-item a {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 0;
      margin: 0 3px;
      border-radius: 50% !important;
      width: 36px;
      height: 36px;
      font-size: .875rem
    }

    .pagination-lg .page-item a {
      width: 46px;
      height: 46px;
      line-height: 46px
    }

    .pagination-sm .page-item a{
      width: 30px;
      height: 30px;
      line-height: 30px
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
</head>

<body class="">

<div id="loader-div">
    <div id="loader">

    </div>
</div>
  <div class="main-content">
    <!-- Navbar -->
    <nav class="navbar navbar-top navbar-expand-md navbar-dark" id="navbar-main">
      <div class="container-fluid">
        <!-- Brand -->
        <!-- back btn  ml-sm-2 ml-lg-0-->
        <div class="row mt-4">
          <div class="col-xl-10 back-btn-posotion mt-4">
            <a href="javascript:history.go(-1);" class="back-btn mt-4" title="Back">
              <img src="<?= base_url('assets/img/left-arrow.png') ?>" alt="Left-Arrow" class="back-icon">
            </a>
          </div>
        </div>
        <!-- Form -->
        <!-- <form class="navbar-search navbar-search-dark form-inline mr-3 d-none d-md-flex ml-lg-auto">
          <div class="form-group mb-0">
            <div class="input-group input-group-alternative">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-search"></i></span>
              </div>
              <input class="form-control" placeholder="Search" type="text">
            </div>
          </div>
        </form> -->
        <!-- User -->
        <ul class="navbar-nav align-items-center ml-auto d-none d-md-flex">
          <li class="nav-item dropdown">
            <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <div class="media align-items-center">
                <span class="avatar avatar-sm rounded-circle">
                  <img alt="Image placeholder" src="<?= BASE_URL . 'uploads/' . $data->profilePicURL ?>" width="100%" height="100%">
                </span>
                <div class="media-body ml-2 d-none d-lg-block">
                  <span class="mb-0 text-sm  font-weight-bold"><?= $data->name ?></span>
                </div>
              </div>
            </a>
            <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-right">
              <div class=" dropdown-header noti-title">
                <h6 class="text-overflow m-0">Welcome!</h6>
              </div>
              <a href="<?= BASE_URL ?>admin-profile" class="dropdown-item">
                <i class="ni ni-single-02"></i>
                <span>My profile</span>
              </a>
              <a href="<?= BASE_URL ?>change-password" class="dropdown-item">
                <i class="ni ni-lock-circle-open"></i>
                <span>Change Password</span>
              </a>

              <div class="dropdown-divider"></div>
              <a href="<?= BASE_URL ?>logout" class="dropdown-item">
                <i class="ni ni-user-run"></i>
                <span>Logout</span>
              </a>
            </div>
          </li>
        </ul>
      </div>
    </nav>
    <!-- End Navbar -->
    <link href="https://urcommunitycares.com/assets/web_files/vendor/plugin_css/datepicker.css" rel="stylesheet">
<input type="hidden" id="addressId" value="<?=$addressId?>">
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


  <div class="container-fluid mt--7">
      <!-- Table -->
      <div class="row">
        <div class="col">
          <div class="card shadow">
            <div class="card-header border-0">
                <h3 class="mb-0">Business Report Listing</h3>
              </div>
            </div>
            <div class="table-responsive" id="reportListing">
              <table class="table align-items-center table-flush" id="reportList">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Report Date</th>
                    <th scope="col">Report By</th>
                    <th scope="col">Reporter EmailId</th>
                    <th scope="col">Business Name</th>
                    <th scope="col">Business Address</th>
                    <th scope="col">Issue</th>
                    <th scope="col">Detail</th>
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
            $addressId = $('#addressId').val();
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
                    url: '<?= SITE_URL?>Admin/Admin/loadAddressReports/' + pagno,
                    type: 'post',
                    data: {
                        'addressId' : $addressId
                    },
                    success: function(data) {
                      data = JSON.parse(data);
                      if(data.result == ''){
                        $('#reportList tbody').empty();
                        $('#reportList tbody').append("<tr><td colspan=8 class='text-center text-danger h2'><img src='<?= BASE_URL?>assets/img/no_data.png'></td></tr>");
                      } else {
                        if(pagno != 0){
                          $('html, body').animate(
                          {
                            scrollTop: $('#reportsListing').offset().top,
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
            function createTable(result) {

                serialNumber = 0;
                $('#reportList tbody').empty();
                for (index in result) {
                    $reportId = result[index].reportId;
                    $reporterName = result[index].reporterName;
                    $reporterEmailId = result[index].reporterEmailId;
                    $businessId = result[index].businessId;
                    $businessName = result[index].businessName;
                    $businessAddress = result[index].businessAddress;
                    $issue = result[index].issue;
                    // $date = new Date(result[index].createDate);
                    // $newDate = $date.getDate();
                    // $newMonth = $date.getMonth() + 1;
                    // if($date.getDate() < 10){
                    //     $newDate = '0'+$date.getDate();
                    // }
                    // if($date.getMonth() < 10){
                    //     $newMonth = '0'+ ($date.getMonth() + 1);
                    // }
                    // $fullDate = $newDate + '-' + $newMonth + '-' + $date.getFullYear();
                    $fullDate = result[index].createDate;
                    $description = result[index].description;
                    $rating = result[index].rating;
                    $profilePicURL = result[index].profilePicURL;
                    serialNumber += 1;

                    // for($i = 0; $ > $rating; $i++){
                    $ratingStars = $rating * "<i class='fas fa-star'></i>";
                    // }

                    var tr = "<tr>";
                    tr += "<td>" + serialNumber + "</td>";
                    tr += "<td>" + $fullDate + "</td>";
                    tr += "<td>" + $reporterName + "</td>";
                    tr += "<td>" + $reporterEmailId + "</td>";
                    tr += "<td>" + $businessName + "</td>";
                    tr += "<td>" + $businessAddress + "</td>";
                    tr += "<td class='white-space-unset'>" + $issue + "</td>";
                    tr += "<td><a href='<?= BASE_URL?>report-detail/" + encodeURIComponent(window.btoa($reportId)) + "' class='btn btn-icon btn-2 btn-primary' title='Detail'><span><i class='fas fa-eye'></i></span></a></td>";
                    tr += "</tr>";
                    $('#reportList tbody').append(tr);

                }
            }
        });
    </script>