<!-- <link href="https://urcommunitycares.com/assets/web_files/vendor/plugin_css/datepicker.css" rel="stylesheet"> -->
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
                <h3 class="mb-0">Report Listing</h3>
              </div>
            </div>
            <div class="table-responsive" id="reportsListing">
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
                    url: '<?=SITE_URL?>Admin/Admin/loadReport/' + pagno,
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if(data.result == ''){
                            $('#reportList tbody').empty();
                            $('#reportList tbody').append("<tr><td colspan=8 class='text-center text-danger h2'>No data Found</td></tr>");
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
            function createTable(result, sno) {

                serialNumber = Number(sno);
                $('#reportList tbody').empty();
                for (index in result) {
                    $reportId = result[index].reportId;
                    $reporterName = result[index].reporterName;
                    console.log($reporterName);
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
                    // if($date.getMonth() < 9){
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
                    tr += "<td><a href='" + "<?=SITE_URL?>report-detail/"+ encodeURIComponent(window.btoa($reportId)) +"' class='btn btn-icon btn-2 btn-primary' title='Detail'><span><i class='fas fa-eye'></i></span></a></td>";
                    tr += "</tr>";
                    $('#reportList tbody').append(tr);

                }
            }
        });
    </script>