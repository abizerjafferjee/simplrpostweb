<link href="https://urcommunitycares.com/assets/web_files/vendor/plugin_css/datepicker.css" rel="stylesheet">
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

<div class="container-fluid mt--7">
    <!-- Table -->
    <div class="row">
        <div class="col">
            <div class="card shadow">
                <div class="card-header border-0">
                    <h3 class="mb-0">Manage FAQ</h3>

                    <div class="float-right">
                        <button class="btn btn-icon btn-3 btn-primary" type="button" data-toggle="modal" data-target="#add_modal" data-backdrop="static" data-keyboard="false">
                            <span class="btn-inner--icon"><i class="ni ni-fat-add"></i></span>
                            <span class="btn-inner--text">Add New FAQ</span>
                        </button>
                    </div>
                </div>
            </div>
            <div class="table-responsive" id="faqListing">
                <table class="table align-items-center table-flush" id="categoriesList">
                    <thead class="thead-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Question</th>
                            <th scope="col">View Details</th>
                            <th scope="col">Edit</th>
                            <th scope="col">Delete</th>
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
    <!-- </div> -->
    <!-- Dark table -->


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
                    <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Are you sure you want to delete this FAQ ?</h3>
                    <!-- <div class="custom-control custom-checkbox mb-3">
                    <input class="custom-control-input" id="customCheck1" type="checkbox">
                    <label class="custom-control-label" for="customCheck1">This category is selected by so many users. If you delete it, it will put impact on system. Please cross check before proceeding. </label>
            </div> -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary deleteModalButton" onclick="deleteFAQ(this.id)" id="">Delete</button>
                </div>
            </div>
        </div>
    </div>



    <!--edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="edit_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title pb-4 text-center" id="exampleModalLabel">Edit FAQ</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label class="pl-2">Question</label>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-alternative" id="question" placeholder="Question" style="resize:none"></textarea>
                    </div>
                    <span class="validate_error" id="questionValidationError"></span>
                    <label class="pl-2">Answer</label>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-alternative" id="answer" placeholder="Answer" style="resize:none" rows="5"></textarea>
                    </div>
                    <span class="validate_error" id="answerValidationError"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary editModalButton" onclick="updateFAQ(this.id)">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <!--add Modal -->
    <div class="modal fade" id="add_modal" tabindex="-1" role="dialog" aria-labelledby="add_modal" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title pb-4 text-center" id="exampleModalLabel">Add New FAQ</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-alternative" id="addQuestion" placeholder="Question" style="resize:none"></textarea>
                    </div>
                    <span class="validate_error" id="questionValidationError"></span>
                    <div class="form-group">
                        <textarea type="text" class="form-control form-control-alternative" id="addAnswer" placeholder="Answer" style="resize:none" rows="5"></textarea>
                    </div>
                    <span class="validate_error" id="answerValidationError"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary modalCloseButton" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="addNewCategory" onclick="addNewFAQ()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/jquery/dist/jquery.min.js"></script>
    <script src="<?= base_url() ?>assets/js/plugins/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://urcommunitycares.com/assets/web_files/vendor/jquery/datepicker.min.js"></script>
    <script type="text/javascript" src="https://urcommunitycares.com/assets/web_files/vendor/jquery/moment.js"></script>

    <script type="text/javascript">
        $('.datepicker').datepicker({
            autoHide: true,
            format: 'yyyy-mm-dd'
        });



        $('.datepicker').on('pick.datepicker', function(e) {
            var dt = moment(e.date);

            dob = dt.format('YYYY-MM-DD');

            $(this).val(dt.format('MMM DD, YYYY'));

        });
    </script>
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
                    url: '<?=SITE_URL?>Admin/Admin/loadFAQ/' + pagno,
                    type: 'get',
                    dataType: 'json',
                    success: function(data) {
                        if(data.result == ''){
                            $('#categoriesList tbody').empty();
                            $('#categoriesList tbody').append("<tr><td colspan=6 class='text-center text-danger h2'><img src='<?= BASE_URL?>assets/img/no_data.png'></td></tr>");
                        } else {
                            if(pagno != 0){
                                $('html, body').animate(
                                {
                                    scrollTop: $('#faqListing').offset().top,
                                },
                                200,
                                'linear'
                                )
                            }
                            $('#pagination').html("<li class='page-item'><a id='currentPageLink' style='display:none'>currentPageLink</a></li>" + data.pagination);
                            createTable(data.result, data.row);
                        }
                    }
                });
            }

            // Create table list
            function createTable(result, sno) {
                serialNumber = Number(sno);
                $('#categoriesList tbody').empty();

                result.forEach(function(result, index) {
                    $questionId = result.questionId;
                    $question = result.question;
                    serialNumber += 1;

                    var tr = "<tr>";
                    tr += "<td>" + serialNumber + "</td>";
                    tr += "<td class='white-space-unset'><span style='max-width:100%'>" + $question + "</span></td>";
                    tr += "<td><a href='<?= BASE_URL ?>FAQ-detail/"+ encodeURIComponent(window.btoa($questionId)) +"' class='btn btn-icon btn-2 btn-primary' title='Detail'><span><i class='fas fa-eye'></i></span></a>";
                    tr += "<td><button class='btn btn-icon btn-2 btn-primary' type='button' id='"+ $questionId +"' onclick='setEditModalValues(this.id)'><span><i class='fas fa-edit'></i></span></button></td>";
                    tr += "<td><button class='btn btn-icon btn-2 btn-danger' type='button' id='"+ $questionId +"' onclick='setDeleteModalQuestionId(this.id)'><span><i class='fas fa-trash'></i></span></button></td>";
                    tr += "</tr>";
                    $('#categoriesList tbody').append(tr);
                })
            }
        });

        function createCurrentPageLink() {
            $activePage = $('#activePage').text();
            $('#currentPageLink').attr('href', '<?=SITE_URL?>Admin/Admin/loadFAQ/' + $activePage);
            $('#currentPageLink').attr('data-ci-pagination-page', $activePage);
        }

        function setEditModalValues(id) {
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/getQuestionValues',
                data: {
                    'questionId': id
                },
                success: function(data) {
                    data = JSON.parse(data);
                    $('.editModalButton').attr('id', id);
                    $('#question').val(data.question);
                    $('#answer').val(data.answer);
                    $('#editModal').modal({backdrop: 'static', keyboard: false});
                }
            });
        }

        function updateFAQ(id) {
            createCurrentPageLink();
            $question = $('#question').val();
            $answer = $('#answer').val();
            if ($question.trim().length == 0) {
                $('#questionValidationError').html('<p class="text-danger" style="z-index:11">Please fill this field<p>');
            } else if($answer.trim().length == 0){
                $('#answerValidationError').html('<p class="text-danger" style="z-index:11">Please fill this field<p>');
            } else {
                $.ajax({
                    type: "post",
                    url: '<?=SITE_URL?>Admin/Admin/editFAQ',
                    data: {
                        'questionId': id,
                        'question': $question,
                        'answer': $answer
                    },
                    success: function(data) {
                        $('#currentPageLink').trigger('click');
                        $('.modalCloseButton').trigger('click');
                        $('#exampleModalLabel').html('FAQ updated successfully');
                        $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                    }
                });
            }
        }

        function addNewFAQ() {
            createCurrentPageLink();
            $question = $('#addQuestion').val();
            $answer = $('#addAnswer').val();
            if ($question.trim().length == 0) {
                $('#questionValidationError').html('<p class="text-danger" style="z-index:11">Please fill this field<p>');
            } else if($answer.trim().length == 0){
                $('#answerValidationError').html('<p class="text-danger" style="z-index:11">Please fill this field<p>');
            } else {
                $.ajax({
                    type: "post",
                    url: '<?=SITE_URL?>Admin/Admin/addNewFAQ',
                    data: {
                        'question': $question,
                        'answer' : $answer
                    },
                    success: function(data) {
                        $('#currentPageLink').trigger('click');
                        $('.modalCloseButton').trigger('click');
                        $('#exampleModalLabel').html('FAQ added successfully');
                        $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                    }
                });
            }
        }

        function setDeleteModalQuestionId(id) {
            createCurrentPageLink();
            $('.deleteModalButton').attr('id', id);
            $('#deleteModal').modal({backdrop: 'static', keyboard: false});
        }

        function deleteFAQ(id) {
            $.ajax({
                type: "post",
                url: '<?=SITE_URL?>Admin/Admin/deleteFAQ',
                data: {
                    'questionId': id
                },
                success: function(data) {
                    $('#currentPageLink').trigger('click');
                    $('.modalCloseButton').trigger('click');
                    $('#exampleModalLabel').html('FAQ Deleted successfully');
                    $('#modalCenter').modal({backdrop: 'static', keyboard: false})
                }
            });
        }
    </script>