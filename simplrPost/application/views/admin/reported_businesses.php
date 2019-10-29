<link href="<?=base_url('assets/css/plugins/datepicker.css')?>" rel="stylesheet">
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
<!-- Page content -->
<div class="container-fluid mt--7 wrap">
    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">
                        Reported Businesses Listing
                    </h3>
                </div>
            </div>

            <div class="table-responsive card shadow" id="reportedBusinessesListing">
                <table class="table align-items-center table-flush" id="reportedBusinessesList">
                <thead class="thead-light">
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Image</th>
                    <th scope="col">Name</th>
                    <th scope="col">Category</th>
                    <th scope="col">Block Date</th>
                    <th scope="col">Status</th>
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
      <script src="<?=base_url()?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
      <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
      <script type="text/javascript" src="<?=base_url('assets/js/plugins/moment.js')?>"></script>

      <script type="text/javascript">
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
        // } else {
        //   loadPagination($currentActivePage);
        // }
        function loadPagination(pagno) {
          $.ajax({
            url: '<?=SITE_URL?>Admin/Admin/loadReportedBusinesses/' + pagno,
            type: 'get',
            dataType: 'json',
            success: function(data) {
              if(data.result == ''){
                $('#reportedBusinessesList tbody').empty();
                $('#reportedBusinessesList tbody').append("<tr><td colspan=7 class='text-center text-danger h2'>No data Found</td></tr>");
              } else {
                if(pagno != 0){
                  $('html, body').animate(
                  {
                    scrollTop: $('#reportedBusinessesListing').offset().top,
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
          $('#reportedBusinessesList tbody').empty();
          for (index in result) {
            $reportId = result[index].reportId;
            $addressId = result[index].businessId;
            $logoURL = result[index].logoURL;
            $shortName = result[index].shortName;
            $blockDate = result[index].blockDate;
            $categoryName = result[index].categoryName;
            if(result[index].status == -5){
              $status = 'Blocked'
            } else {
              $status = 'Unblocked'
            }
            serialNumber += 1;

            var tr = "<tr>";
            tr += "<td>" + serialNumber + "</td>";
            tr += "<td scope='row'><div class='media align-items-center'><a class='avatar rounded-circle mr-3'><img style='object-fit:cover' alt='Image placeholder' src='<?=BASE_URL?>uploads/"+ $logoURL + "' onerror='this.onerror=null;this.src=\"<?=BASE_URL?>assets/img/building_placeholder.png\"' height='100%' width='100'></a></div></td>";
            tr += "<td>" + $shortName + "</td>";
            tr += "<td>" + $categoryName + "</td>";
            tr += "<td>" + $blockDate + "</td>";
            tr += "<td>" + $status + "</td>";
            tr += "<td><a href='<?=SITE_URL?>reported-business-detail/" + encodeURIComponent(window.btoa($addressId)) + "' class='btn btn-icon btn-2 btn-primary' title='Detail'><span><i class='fas fa-eye'></i></span></a></td>";
            tr += "</tr>";
            $('#reportedBusinessesList tbody').append(tr);

          }
        }
      </script>