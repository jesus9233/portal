<!doctype html>
<?php include('server.php') ?>
<?php require_once 'backend/patient/Controller.php'; ?>
<?php
    if (!isset($_SESSION['username'])) {
        $_SESSION['msg'] = "You must log in first";
        header('location: ' . getLink('login.php'));
    }
    // send to next page
    $_SESSION['medicalrecord'] = $medicalrecord;

    if (!empty($otherhospitals)) {

        $otherhospitalsA = $otherhospitals;
    }

    if (isset($_GET['logout'])) {
        session_destroy();
        unset($_SESSION['username']);
        header("location: " . getLink('login.php'));
    }
?>

<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Bootstrap core CSS -->
    <link href="<?php echo getLink("css/bootstrap.min.css") ?>" rel=" stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="<?php echo getLink("css/mdb.min.css") ?>" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <link href="<?php echo getLink("css/style.css") ?>" rel="stylesheet">
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script type="text/javascript" src="<?php echo getLink("js/jquery-3.3.1.min.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getLink("js/feedback.min.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getLink("js/jquery-ui.min.js") ?>"></script>
    <script type="text/javascript" src="<?php echo getLink("js/modernizr.js") ?>"></script>
    <link rel="stylesheet" href="<?php echo getLink("css/feedback.min.css") ?>" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
    <!-- Font Awesome JS -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/solid.js" integrity="sha384-tzzSw1/Vo+0N5UhStP3bvwWPq+uvzCMfrN1fEFe+xBmv1C/AtVX5K0uZtmcHitFZ" crossorigin="anonymous">
    </script>
    <script defer src="https://use.fontawesome.com/releases/v5.0.13/js/fontawesome.js" integrity="sha384-6OIrr52G08NpOFSZdxxz1xdNSndlD4vdcf/q2myIUVO0VsqaGHJsB0RaBE01VTOY" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
    <script>
        var siteurl = "<?php echo getLink(''); ?>";
    </script>
    <script type="text/javascript" src="<?php echo getLink("backend/patient/main.js") ?>"></script>
    <title>3Points</title>
    <style>
        .has-error {
            border: 1px solid red;
        }

        .tt-menu {
            width: 100% !important;
        }

        .tt-suggestion {
            color: white;
            background: gray;
            cursor: pointer;
            border: 1px solid white;
        }

        .select2-container {
            width: 100% !important;
            padding: 0;
        }

        span.select2-container {
            z-index: 10050;
        }

        .select2-container--open {
            z-index: 9999999
        }

        .selection {
            width: 100% !important;
        }

        .select2-results__option {
            background: #5897fb !important;
        }

        .snfcolor {
            color: #fff;
        }

        span.error {
            display: block;
            visibility: hidden;
            color: red;
            font-size: 90%;
        }

        .text-end {
            text-align: end;
            padding-right: 5px;
        }

        .text-start {
            padding-left: 0px;
        }

        .twitter-typeahead {
            position: relative;
            display: inline-block;
            width: 100%;
        }

        #ulcart {
            padding-left: 8px !important;
        }

        .remove_button {
            position: relative;
            bottom: 26px;
            float: right;
            right: 249px;
        }

        footer.page-footer {
            bottom: 0;
            color: #fff;
            position: static;
            width: 100%;
        }
        .main-model {
            padding-top: 5rem !important;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<!--Main Navigation-->
<header>
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-light white scrolling-navbar">
        <div class="container">
            <!-- Brand -->
            <a class="navbar-brand waves-effect" href="https://www.3pointssoftware.com" target="_blank">
                <strong class="blue-text"><img src="<?php echo getLink("img/logo.png") ?>" width="70px"></strong>
            </a>
            <!-- Collapse -->
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Links -->
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <!-- Left -->
                <?php if ($_SESSION['role_id'] == 1) { ?>
                    <ul class="navbar-nav mr-auto">
                        <button class="btn btn-success activity-log" id="activity_logs">Activity Log</button>
                    </ul>
                <?php } ?>

                <!-- notification message -->
                <?php if (isset($_SESSION['success'])) : ?>
                    <!-- <div class=" error success">
                        <h3>
                            <?php
                                echo $_SESSION['success'];
                                unset($_SESSION['success']);
                                ?>
                        </h3>
                    </div> -->
                <?php endif ?>

                <div class="col-3">
                    <select class="form-control getHospitalId" id="materialRegisterFormHospital_top">
                        <?php
                        foreach ($otherhospitalsA as $Val) {
                            if (!empty($Val)) {
                                $_SESSION['val'] = $Val;
                                if ($Val == $hospital) {
                                    $selected = 'selected="selected"';
                                } else {
                                    $selected = '';
                                }

                                ?>
                                <option <?php echo $selected ?> value="<?php echo $Val['id'] ?>"><?php echo $Val['name']; ?></option>
                        <?php     }
                        } ?>
                    </select>
                </div>

                <?php if ($_SESSION['role_id'] == 1) { ?>
                    <div class="col-3 text-end">
                        <button class="btn btn-success" data-toggle="modal" data-target="#mysnfsModal">
                            Add New SNF
                        </button>
                    </div>
                    <div class="col-3 text-start">
                        <button class="btn btn-success" id="usersnf" data-toggle="modal" data-target="#userSnfModel">
                            Assign User to SNF
                        </button>
                    </div>
                <?php } ?>
                <!-- Right -->
                <ul class="navbar-nav nav-flex-icons">
                    <li class="nav-item">
                        <?php if (isset($_SESSION['username'])) : ?>
                            <a href="<?php echo getLink("patientorexisting.php?logout=1") ?>" class="nav-link waves-effect"><i class="fas fa-sign-out-alt"></i> </a>
                        <?php endif ?>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Navbar -->
</header>
<!--Main layout-->
<main>
    <div class="container-fluid mt-5 pt-5 main-model" id="p-1">
        <div class="row">
            <div class="offset-md-2 col-lg-7 col-md-7">
                <?php flashMsg(); ?>
                <?php if (isset($_SESSION['val'])) { ?>
                    <div class="card">
                        <div class="card-header">
                            <div class="text-center mt-0">
                                <button class="btn btn-success align-items-center new-patient"><a id="new-patient">New Patient</a></button>
                                <button class="btn btn-success align-items-center existing-patient"><a id="existing-patient">View Existing Patients</a></button>
                            </div>
                        </div>
                    </div>
                <?php  } ?>
            </div>
        </div>
    </div>
    <div class="new-patient-form offset-md-2 col-lg-7 col-md-7" id="p-2">
        <div class="card">
            <div class="card-body px-lg-5 pt-0">
                <form class="text-center" method="post" style="color: #757575;">
                    <?php include('errors.php'); ?>
                    <?php $medicalrecord = ""; ?>
                    <div class="form-row">
                        <div class="col">
                            <!-- First name -->
                            <div class="md-form">
                                <input class="form-control" id="materialRegisterFormFirstName" name="firstname" placeholder="First Name" type="text" value="<?php echo $firstname; ?>">
                            </div>
                        </div>
                        <div class="col">
                            <!-- Last name -->
                            <div class="md-form">
                                <input class="form-control" id="materialRegisterFormLastName" name="lastname" placeholder="Last Name" type="text" value="<?php echo $lastname; ?>">
                            </div>
                        </div>
                    </div>
                    <div class="md-form mt-0">
                        <input class="form-control" id="materialRegisterFormRecord" name="medicalrecord" placeholder="Patient Medical Record" type="text" value="<?php echo $medicalrecord; ?>">
                    </div>
                    <!-- Hospital -->
                    <div class="md-form mt-0">
                        <input class="form-control snfsName" readonly id="materialRegisterFormHospital" name="hospital" placeholder="SNF (Skilled Nursing Facilities)" type="text" value="">


                    </div>
                    <input type="hidden" name="snf_id" id="hospitalId" value="">
                    <button class="btn btn-outline-info btn-rounded btn-block my-4 btn-blue waves-effect z-depth-0" id="register-btn" name="reg_patient" type="submit">Create Patient</button>
                </form><!-- Form -->
            </div>
        </div>
    </div>
    <div class="existing-patient-content offset-md-2 col-lg-7 col-md-7" id="p-4">
        <div class="card">
            <div class="card-body px-lg-5 pt-0">
                <table id="existingPatients" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Medical Record</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Medical Record</th>
                            <th>Actions</th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 main-model" id="p-3" style="display: none; padding: 0px 15px ;">
        <div class="row">
            <div class="col-sm-5">
                <div class="card">
                    <div class="card-header">
                        <h2>User</h2>
                    </div>
                    <div class="card-body px-lg-5 pt-4">
                        <table id="user_table" class="display" style="width:100%; cursor: pointer;">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-sm-7">
                <div class="card">
                    <div class="card-header">
                        <h2><label id="user_name" style="margin-bottom: 0px"></label> Activity</h2>
                    </div>
                    <div class="card-body px-lg-5 pt-4">
                        <table id="activity_table" class="display" style="width:100%">
                            <thead>
                                <tr>
                                    <th width="70%">Log</th>
                                    <th width="30%">Date</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<footer id="footer" class="page-footer unique-color-dark mt-4">
    <!--Footer Links-->
    <div class="container text-center py-4 text-md-left mt-5">
        <div class="row mt-3">
            <div class="col-md-4 col-lg-4 col-xl-4">
                <h6 class="text-uppercase font-weight-bold">
                    <strong>Support</strong>
                </h6>
                <hr class="info-color mb-4 mt-0 d-inline-block mx-auto" style="width: 60px;">
                <p>
                    <i class="fas fa-envelope mr-3"></i> support@3pointssoftware.com
                </p>
            </div>
            <!--/.Fourth column-->
        </div>
    </div>
    <!--/.Footer Links-->
    <!-- Copyright-->
    <div class="footer-copyright py-3 text-center">
        © 2019 Copyright:
        <a href="https://threepointssoftware.com">
            <strong> 3Points Software</strong>
        </a>
    </div>
    <div class="modal modal-print fade" id="patientAnswers" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-notify modal-success" role="document">
            <!--Content-->
            <div class="modal-content">
                <!--Header-->
                <div class="modal-header">
                    <p class="heading lead ">Patient Name: <span class="pt-name"></span></p>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true" class="white-text">&times;</span>
                    </button>
                </div>
                <!--Body-->
                <div class="modal-body" data-keyboard="false" data-backdrop="static">
                    <p class="heading lead text-dark">NTA Score: <span class="scoreView"></span></p>
                    <div class="model-inner"></div>
                </div>

            </div>
            <!--/.Content-->
        </div>
    </div>
    <!--/.Copyright -->
    <?php echo PatientController::getEditModal(); ?>
</footer>
<!-- add nre snfs modal -->
<div id="mysnfsModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <span class="snfcolor">Add SNF (Skilled Nursing Facilities)</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">×</span>
                </button>
            </div>
            <!--Body-->
            <div class="modal-body" data-keyboard="false" data-backdrop="static">
                <div class="form-group">
                    <input type="text" name="snfs" id="snfs" class="form-control" placeholder="Add SNF (Skilled Nursing Facilities)">
                </div>
                <br>
                <button type="button" class="btn btn-primary submitSNFS waves-effect waves-light">Submit</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger cancel waves-effect waves-light">Cancel</button>
            </div>
        </div>
    </div>
</div>
<!-- add nre snfs modal -->
<!-- aaaign user to snfs modal -->
<div id="userSnfModel" class="modal fade" role="dialog">
    <div class="modal-dialog modal-notify modal-success" role="document">
        <!--Content-->
        <div class="modal-content">
            <!--Header-->
            <div class="modal-header">
                <span class="snfcolor">Assign User To SNF (Skilled Nursing Facilities)</span>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" class="white-text">×</span>
                </button>
            </div>
            <!--Body-->
            <div class="modal-body" data-keyboard="false" data-backdrop="static">
                <label>Select User</label>
                <div class="form-group" id="users">
                </div>
                <label class="setlabelSNF">Select SNF</label>
                <input class="typeahead form-control" type="text" id="suggestss" ria-label="Search SNF" placeholder="Search for Code" value="" name="suggestss">
                <input type="hidden" name="snfId" class="snfID[]" value="[]">
                <br>
                <ul id="ulcart" style="list-style-type:disc;">
                    <input name="hospital" id="hospital" type="hidden" value="">
                </ul>
            </div>
            <br>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary submitsnfsusers waves-effect waves-light">Submit</button>
                <button type="button" data-dismiss="modal" class="btn btn-danger cancel waves-effect waves-light">Cancel</button>
            </div>
        </div>
    </div>
</div>
</div>
<!-- add nre snfs modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script type="text/javascript" src="<?php echo getLink("js/popper.min.js") ?>"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo getLink("js/bootstrap.min.js") ?>"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo getLink("js/mdb.js") ?>"></script>
<script type="text/javascript" src="<?php echo getLink("js/typeahead.js") ?>"></script>
<script type="text/javascript" src="<?php echo getLink("js/bloodhound.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo getLink("js/printThis.js") ?>"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<script>
    var catId = [];
    //== user id select to sho list ==//
    $(document).on('change', '#userName', function() {
        var userId = $('#userName').val();
        $.ajax({
            url: siteurl + '/getautocomplete.php?getSnfAgainstUserID',
            type: 'GET',
            data: {
                user_id: userId
            },
            success: function(data) {
                var dataa = JSON.parse(data);
                if (dataa.success === false) {
                    toastr.error(dataa.message);
                } else {
                    var snfs = dataa.getUserSnf;
                    $('#ulcart').append('');
                    $('#ulcart').html('');
                    snfs.map(function(cat_id) {
                        if(cat_id[0]==undefined) return [];
                        $('.hidden-cat').val(cat_id[0].id);
                        catId.push(cat_id[0].id, cat_id[0].name);
                        $('#ulcart').append('<li class="list-unstyled item ui-state-default cartid_' +
                            cat_id[0].id + '" data-cart-id = "' + cat_id[0].id + '"><h5 data-toggle="tooltip" class="mt-1 mb-1 cart-rank font-weight-bold highlight-red "> ' +
                            cat_id[0].name + '</h5><a href="javascript:void(0);" data-id="' + cat_id[0].id +
                            '" class="remove_button"> <i class="fa fa-times" aria-hidden="true"></i></a></li>'
                        );
                        return cat_id[0];
                    });
                }
            }
        });
    });
    //== user id select to sho list ==//
    // remove list items
    $('#ulcart').on('click', '.remove_button', function(e) {
        e.preventDefault();
        var h = $(this).data('id');
        catId = jQuery.grep(catId, function(value) {
            return value != h;
        });

        $(this).parent('li').remove();
        x--; //Decrement field
    }); // === END wrapper Remove === //
</script>
<script>
    var source2 = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: siteurl + '/getautocomplete.php?snfs=%QUERY',
            wildcard: '%QUERY'
        }
    });

    // Initialize the Bloodhound suggestion engine
    source2.initialize();
    $('#suggestss').typeahead(null, {
        name: 'source1',
        display: 'name',
        highlight: true,
        source: source2.ttAdapter(),
        templates: {
            suggestion: function(data) {
                // lists all suggestions
                var details = "<div>" + data + "</div>";
                console.log(data);
                return details
            }
        }
    });

    var maxField = 50; //Input fields increment limitation
    var catId = [];
    var x = 1; //Initial field counter is 1
    var wrapper = $('.field_wrapper'); //Input field wrapper
    $('#suggestss').on('typeahead:selected', function(e, datum) {
        // grab the hidden input value
        var datumConvert = JSON.stringify(datum);
        // if autocomplete contains description as well
        if (datumConvert.includes(":")) {
            // remove any characters after :
            var stripDatum2 = datumConvert.substring(0, datumConvert.lastIndexOf(":"));
            //remove all characters
            var stripDatum3 = stripDatum2.replace(/[\[\]""]+/g, '');
            $('.hidden-cat').val(stripDatum3)
            $('.hidden-id').val(stripDatum3)
        }
        // if no description
        else {
            // remove special characters
            var stripDatum = datumConvert.replace(/[{()}]/g, '');
            // remove special characters
            var stripDatum3 = stripDatum.replace(/[\[\]""]+/g, '');
            $('.hidden-cat').val(stripDatum3)
            $('.hidden-id').val(stripDatum3)
        }
        //var stripDatum4 = stripDatum3.substring(0, stripDatum3.lastIndexOf(":") );
        var hiddenCat = $('.hidden-cat').val()
        var hiddenID = $('.hidden-id').val()
        //get user id
        var userId = $('#userName').val();
        // ajax call it and return the category ID
        $.ajax({
            url: siteurl + '/getautocomplete.php?snfId',
            type: 'GET',
            data: {
                itemID: hiddenCat,
                user_id: userId
            },
            success: function(data) {
                alert()
                var dataa = JSON.parse(data);
                if (dataa.success === false) {
                    toastr.error(dataa.message);
                } else {
                    console.log(dataa);
                    console.log(dataa.checkSnfAssign.length);
                    if (dataa.checkSnfAssign.length === '' || dataa.checkSnfAssign.length === 0) {
                        var snfs = dataa.snfId;
                        snfs.map(function(cat_id) {
                            $('.hidden-cat').val(cat_id.cat_id)
                            console.log('cat_id');
                            console.log(cat_id.id);
                            console.log('cat_id');
                            catId.push(cat_id.id, cat_id.name);
                            $('#ulcart').append('<li class="list-unstyled item ui-state-default cartid_' +
                                cat_id.id + '" data-cart-id = "' + cat_id.id + '"><h5 data-toggle="tooltip" class="mt-1 mb-1 cart-rank font-weight-bold highlight-red "> ' +
                                cat_id.name + '</h5><a href="javascript:void(0);" data-id="' + cat_id.id +
                                '" class="remove_button"> <i class="fa fa-times" aria-hidden="true"></i></a></li>'
                            );
                            return cat_id;
                        });
                    } else {}
                }
            }
        })
        $.ajax({
            url: siteurl + '/getautocomplete.php',
            type: 'POST',
            data: {
                itemID2: hiddenID
            }
        })
    }) // === END Suggest === //
    // remove list items
    $('#ulcart').on('click', '.remove_button', function(e) {
        e.preventDefault();
        var h = $(this).data('id');
        catId = jQuery.grep(catId, function(value) {
            return value != h;
        });

        $(this).parent('li').remove();
        x--; //Decrement field
    }); // === END wrapper Remove === //
</script>

<script>
    $(document).on('change', '#materialRegisterFormHospital_top', function() {
        var h = $(this).val();
        var snfsname = $("#materialRegisterFormHospital_top option:selected").text();
        $.ajax({
            url: siteurl + 'data.php',
            type: 'GET',
            data: {
                action: 'changehospital',
                hospitalname: h
            },
            success: function(data) {
                var data = JSON.parse(data);
                $('.snfsName').val();
                console.log(data);
                var snfsName = data[0].name;
                var snfsID = data[0].id;
                $('.snfsName').val(snfsName);
                $('#hospitalId').val(snfsID);
                let url = siteurl + snfsName + '/patientorexisting.php';
                history.pushState({}, null, url);
              }
        });
    });

    $(document).on('click', '.submitsnfsusers', function() {
        var code_list = ',';
        $('#ulcart li').each(function(i, obj) {
            var code_id = $(obj).attr('data-cart-id');
            code_list += code_id;
        });
        if (code_list != "") {
            code_list = code_list.slice(1);
        }
        let userId = $('#userName').val();
        if (userId === 0) {
            toastr.error('User must be selected');
        } else if (code_list.length < 1) {
            toastr.error('At least one snf must be selected');
        } else {
            $.ajax({
                url: siteurl + 'data.php',
                type: 'POST',
                data: {
                    action: 'insertDataUserSnf',
                    user_id: userId,
                    snfList: code_list
                },
                success: function(data) {
                    var response = JSON.parse(data);
                    if (response.success === true) {
                        toastr.success('Record Inserted Successfully');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    }
                }
            });
        }
        //At least one snf must be selected
    });

    $(document).on('click', '.submitSNFS', function() {
        var s = $('#snfs').val();
        $.ajax({
            url: siteurl + 'data.php',
            type: 'POST',
            data: {
                action: 'savesnfs',
                snfsname: s
            },
            success: function(data) {
                toastr.success('SNF (Skilled Nursing Facilities) added successfully');
                $('#snfs').val();
                setTimeout(function() {
                    window.location.reload();
                }, 2000);
            }
        });
    });

    $(document).on('click', '#usersnf', function() {
        $.ajax({
            url: siteurl + 'data.php',
            type: 'POST',
            data: {
                action: 'getUsersAndSnfs'
            },
            success: function(data) {
                let info = JSON.parse(data);
                $('#users').html(info['usersHtml']);
                $('#snfsforuser').html(info['snfsHtml']);
            }
        })
    });

    var getpatient = '';
    var getactivity = '';

    $('.existing-patient-content').hide();
    $('.new-patient').on('click', function() {
        $('.new-patient-form').show();
        $('.existing-patient-content').hide();
    });
    $('.existing-patient').on('click', function() {
        $('.new-patient-form').hide();
        $('.existing-patient-content').show();
    });

    $(document).ready(function() {

        <?php 
            if (isset($_SESSION['hospital'])) {
                $snf_id = $_SESSION['hospital'];
            } else {
                $snf_id = '';
            }
        ?>
        let snf_id = "<?php if ($snf_id != '') echo $snf_id;
                        else {
                            echo "0";
                        } ?>";
        if (snf_id != 0) {
            $("#materialRegisterFormHospital_top option").each(function() {
                if ($(this).text() == snf_id) {
                    $(this).attr('selected', 'selected');
                }
            });
            //$('#materialRegisterFormHospital_top').trigger('change');
            $('.snfsName').val(snf_id);
        }

        $(document).on('change', '.getHospitalId', function() {
            getpatient();
        });
        getpatient = function() {
            var snp_id = $('.getHospitalId').val();
            $('#existingPatients').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "pageLength": 10,
                "iDisplayLength": 10,
                "lengthChange": false,
                "info": false,
                "ordering": false,
                "bDestroy": true,
                "ajax": {
                    "url": siteurl + 'data.php',
                    "type": "POST",
                    "data": {
                        action: "getPatients",
                        snfsid: snp_id
                    }
                },
                "columns": [{
                        "data": "firstname"
                    },
                    {
                        "data": "lastname"
                    },
                    {
                        "data": "medicalrecord"
                    },
                    {
                        "data": "medicalrecord"
                    }
                ],
                columnDefs: [{
                    targets: 2,
                    render: function(data, type, row, meta) {
                        // console.log(data);
                        // console.log(type);
                        if (type === 'display') {
                            data = '<a class="viewPatient" data-medicalrecord="' +
                                encodeURIComponent(data) + '" data-patientname="' + row
                                .firstname + ' ' + row.lastname +
                                '" href="javascript:void(0)">View</a> | <a class="editPatient" data-id="' +
                                row.id + '" data-medicalrecord="' + encodeURIComponent(
                                    data) + '" data-patientname="' + row.firstname +
                                ' ' +
                                row
                                .lastname +
                                '" href="javascript:void(0)">Edit</a>| <a class="deletePatient" data-id="' +
                                row.id + '" data-medicalrecord="' + encodeURIComponent(
                                    data) + '" data-patientname="' + row.firstname +
                                ' ' +
                                row
                                .lastname + '" href="javascript:void(0)">Delete</a>';
                        }
                        return data;
                    }
                }],
            });
        }
        getpatient();

        // Activity log
        $(document).on('click', '.activity-log', function() {
            var x = document.getElementById("activity_logs").textContent;
            if (x === "Activity Log") {
                document.querySelector('#activity_logs').innerHTML = 'CREATE PATIENT';
                $("#p-1").hide();
                $("#p-2").hide();
                $("#p-3").show();
                $("#p-4").hide();
                getuser();
            } else {
                if ($.fn.DataTable.isDataTable("#user_table")) {
                    $('#user_table').DataTable().clear().destroy();
                }
                document.querySelector('#activity_logs').innerHTML = 'Activity Log';
                $("#p-3").hide();
                $("#p-1").show();
                $("#p-2").show();
            }
        });

        var selectUser = '';

        getuser = function() {
            $('#user_table').DataTable({
                "processing": true,
                "serverSide": true,
                "pageLength": 5,
                "iDisplayLength": 5,
                "lengthChange": true,
                "info": true,
                "ordering": false,
                "bDestroy": true,
                "ajax": {
                    "url": siteurl + 'data.php',
                    "type": "POST",
                    "data": {
                        action: "getUser",
                    }
                },
                "columns": [
                    {
                        "data": "name"
                    },
                    {
                        "data": "email"
                    }
                ]
            });
        }

        $(function(){
            $('#user_table').on('click', 'tr', function(){
                if ( $(this).hasClass('selected') ) {
                    $(this).removeClass('selected');
                }
                else {
                    $('#user_table').DataTable().$('tr.selected').removeClass('selected');
                    $(this).addClass('selected');
                }
                var name_str = $(this).children("td:nth-child(1)").text();
                $('#user_name').text(name_str+"'s");
                selectUser = $(this).children("td:nth-child(2)").text();
                if ($.fn.DataTable.isDataTable("#activity_table")) {
                    $('#activity_table').DataTable().clear().destroy();
                }
                getactivity();
            });
        });

        getactivity = function() {
            $('#activity_table').DataTable({
                "processing": true,
                "serverSide": true,
                "searching": false,
                "pageLength": 10,
                "iDisplayLength": 10,
                "lengthChange": false,
                "info": false,
                "ordering": false,
                "bDestroy": true,
                "ajax": {
                    "url": siteurl + 'data.php',
                    "type": "POST",
                    "data": {
                        action: "getActivity",
                        selectuser: selectUser
                    }
                },
                "columns": [
                    {
                        "data": "action"
                    },
                    {
                        "data": "reg_date"
                    }
                ],
                "select": {
                    "style": 'os',
                    "className": 'focusedRow',
                    "selector": 'td'
                }
            });
        }
        getactivity();
    });

    $(document).on('click', '.viewPatient', function() {
        var popupElem = $('#patientAnswers').find('.model-inner');
        popupElem.html('');
        var medicalRecord = $(this).data('medicalrecord');
        var patientname = $(this).data('patientname');
        $('.pt-name').html(patientname);
        $.ajax({
            url: siteurl + 'data.php',
            type: 'POST',
            data: {
                action: 'getAnswers',
                medicalrecord: medicalRecord,
                patientname: patientname
            },
            success: function(data) {
                var data = JSON.parse(data);
                var answers = data.answers;
                var codes = data.icd_codes;
                var html = '<ul id="sortable" class="ui-sortable olcart">';
                var score2 = 0;
                $.each(codes, function(index, value) {
                    if (value.icd_tertiary_ranking == '') {
                        value.icd_tertiary_ranking = 0;
                    }
                    score2 += parseInt(value.icd_tertiary_ranking);
                    var icd_tertiary_ranking = '(' + value.icd_tertiary_ranking + ')';
                    html += `<li class="item ui-state-default ui-sortable-handle" data-order="13"><h5 class="mt-1 mb-1 cart-rank font-weight-bold"> ${value.icd_code}${icd_tertiary_ranking} <div class="cartdiag" style="">: ${value.icd_desc} , </div></h5>
                      </li>`;
                });
                html += '</ul>';
                var checked = '';
                score = 0;
                $.each(answers, function(index, value) {
                    if (value.answer == 'Yes') {
                        checked = 'checked="checked"';
                    } else {
                        checked = '';
                    }
                    score += parseInt(value.points);
                    html += '<div class="custom-control custom-checkbox"><input ' +
                        checked +
                        ' disabled type="checkbox" class="form-check-input" id="q' + value
                        .id +
                        '" name="ques[' + value.id +
                        ']" increment="1" value="yes">  <label class="form-check-label" for="q' +
                        value.id + '">' + value.title + '</label></div>';
                });
                var finalscore = 0;
                finalscore += parseInt(score) + parseInt(score2);
                $('.scoreView').html(finalscore);
                popupElem.html(html);
                $('#patientAnswers').modal('show');
            }
        });
    });

    $(document).on('click', '.removeC', function() {
        var code = $(this).attr('value');
        var firstname = $('#firstname').val();
        var lastname = $('#lastname').val();
        $.ajax({
            url: siteurl + 'data.php',
            type: "POST",
            data: {
              action: "removeCode",
              removeCode: code,
              firstName: firstname,
              lastName: lastname
            }
        })
    })


</script>

<!-- Optional JavaScript -->
<!-- Bootstrap tooltips -->
<script type="text/javascript" src="<?php echo getLink("js/popper.min.js") ?>"></script>
<!-- Bootstrap core JavaScript -->
<script type="text/javascript" src="<?php echo getLink("js/bootstrap.min.js") ?>"></script>
<!-- MDB core JavaScript -->
<script type="text/javascript" src="<?php echo getLink("js/mdb.js") ?>"></script>
<script type="text/javascript" src="<?php echo getLink("js/typeahead.js") ?>"></script>
<script type="text/javascript" src="<?php echo getLink("js/bloodhound.min.js") ?>"></script>
<script type="text/javascript" src="<?php echo getLink("js/printThis.js") ?>"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js">
</script>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>

</body>

</html>
