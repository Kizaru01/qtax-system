<!DOCTYPE html>
<html lang="en">

<head>
    <?php define("VERSION", $this->config->item('queuing_version')); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo (isset($title)) ? $title . ' | Queuing' : 'Queuing System'; ?></title>
    <link href="<?php echo base_url('vendors/bootstrap-5/css/bootstrap.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('build/css/app.min.css?v='.VERSION); ?>" rel="stylesheet">
    <link href="<?php echo base_url('build/css/number.css?v='.VERSION); ?>" rel="stylesheet">
    <link href="<?php echo base_url('build/css/print.css?v='.VERSION); ?>" rel="stylesheet">
</head>

<body class="page-number1">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <h2 class="display-4" style="margin: 0 auto;"><strong>Real Property Tax Queuing</strong></h2>
    </nav>
    <div class="text-center mb-5 mt-5">
        <h2 class="display-4">Choose your transaction</h2>
        <h2>and take a ticket</h2>
    </div>

    <div class="row" style="width: 1400px;margin: 0 auto;">
        <div class="col-50 d-grid gap-4">
            <!-- <h1 class="title text-center"><strong>Assessment</strong></h1> -->
            <div class="row">
                <div class="col-sm content-regular">
                    <div class="bg-primary btn-modal" data-bs-toggle="modal" data-bs-target="#modal-1-4app"
                        aria-label="Close" style="cursor: pointer;padding: 45px;">
                        <img src="<?php echo base_url('build/images/search.png?v='.VERSION); ?>" class="card-img-top"
                            alt="" width="200" style="max-width: 180px;">
                        <p style="font-size: 50px;margin-top: 30px;">Assessment</p>
                    </div>
                </div>
            </div>
            <!-- <button class="btn btn-primary btn-number m-auto" type="button" data-bs-toggle="modal"
                data-bs-target="#modal-1-4app-blpd">1 - 4 Application</button>
            <button class="btn btn-primary btn-number m-auto" type="button" data-bs-toggle="modal"
                data-bs-target="#modal-5-10app-blpd">5 - 10 Application</button>
            <button class="btn btn-primary btn-number m-auto" type="button" data-bs-toggle="modal"
                data-bs-target="#modal-releasing">Releasing Only</button> -->
        </div>
        <div class="col-50 d-grid gap-4">
            <!-- <h1 class="title text-center"><strong>Payment Only</strong></h1> -->
            <div class="row">
                <div class="col-sm content-regular">
                    <div class="bg-primary btn-modal" data-bs-toggle="modal" data-bs-target="#modal-5-10app"
                        aria-label="Close" style="cursor: pointer;padding: 45px;">
                        <img src="<?php echo base_url('build/images/bill.png?v='.VERSION); ?>" class="card-img-top"
                            alt="" width="200" style="max-width: 180px;">
                        <p style="font-size: 50px;margin-top: 30px;">Payment</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div>
        <p style="text-align: center;position: absolute;bottom: 0;left: 0;right: 0;">Developed & Maintained by City
            Information and Communications Technology Office </p>
    </div>



    <div class="modal" id="modal-taxbill" data-bs-keyboard="false" tabindex="-1" aria-labelledby="modal-taxbill"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="">Tax BIll / PWDs, Senior, Pregnant</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="min-width: 35px;"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm content-regular">
                            <div id="btn-regular-tax" class="bg-primary btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p>Tax BIll</p>
                            </div>
                        </div>
                        <div class="col-sm content-special">
                            <div id="btn-special-tax" class="bg-danger btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p><span>(PWDs, Senior, Pregnant)</span><br>Special lane
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-1-4app" data-bs-keyboard="false" tabindex="-2" aria-labelledby="modal-1-4app"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="">Assessment</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="min-width: 35px;"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm content-regular">
                            <div id="btn-regular" class="bg-primary btn-modal" data-bs-dismiss="modal"
                                aria-label="Close" style="cursor: pointer">
                                <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p>Regular lane</p>
                            </div>
                        </div>
                        <div class="col-sm content-special">
                            <div id="btn-special" class="bg-danger btn-modal" data-bs-dismiss="modal" aria-label="Close"
                                style="cursor: pointer">
                                <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p><span>(PWDs, Senior, Pregnant)</span><br>Special lane
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-5-10app" data-bs-keyboard="false" tabindex="-3" aria-labelledby="modal-1-4app"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">Payment Only</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="min-width: 35px;"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm content-regular">
                            <div id="btn-regular1" class="bg-primary btn-modal" data-bs-dismiss="modal"
                                aria-label="Close" style="cursor: pointer">
                                <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p>Regular lane</p>
                            </div>
                        </div>
                        <div class="col-sm content-special">
                            <div id="btn-special1" class="bg-danger btn-modal" data-bs-dismiss="modal"
                                aria-label="Close" style="cursor: pointer">
                                <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p><span>(PWDs, Senior, Pregnant)</span><br>Special lane
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-1-4app-blpd" data-bs-keyboard="false" tabindex="-2" aria-labelledby="modal-1-4app"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 750px  !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="">Assessment - 1 to 4 Application</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="min-width: 35px;"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm content-regular">
                            <div id="btn-regular" class="bg-primary btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p>Regular lane</p>
                            </div>
                        </div>
                        <div class="col-sm content-special">
                            <div id="btn-special" class="bg-danger btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p><span>(PWDs, Senior, Pregnant)</span><br>Special lane
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" id="modal-5-10app-blpd" data-bs-keyboard="false" tabindex="-3" aria-labelledby="modal-1-4app"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 750px  !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="">Assessment - 5 to 10 Application</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="min-width: 35px;"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm content-regular">
                            <div id="btn-regular1" class="bg-primary btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p>Regular lane</p>
                            </div>
                        </div>
                        <div class="col-sm content-special">
                            <div id="btn-special1" class="bg-danger btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p><span>(PWDs, Senior, Pregnant)</span><br>Special lane
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-releasing" data-bs-keyboard="false" tabindex="-3" aria-labelledby="modal-1-4app"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md" style="max-width: 750px  !important">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="">Releasing</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"
                        style="min-width: 35px;"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm content-regular">
                            <div id="btn-regular-releasing" class="bg-primary btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p>Regular lane</p>
                            </div>
                        </div>
                        <div class="col-sm content-special">
                            <div id="btn-special-releasing" class="bg-danger btn-modal" data-bs-dismiss="modal"
                                aria-label="Close">
                                <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                                    class="card-img-top" alt="">
                                <p><span>(PWDs, Senior, Pregnant)</span><br>Special lane
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="printable1">
        <div id="print">
            <div id="tdescription">Regular</div>
            <div id="tnumber" class="number">
                1001
            </div>
            <!-- <div id="ttype" class="type">
            Regular
          </div> -->
            <div class="description">
                Please wait until your number is flashed on the screen.<br>Thank you.
            </div>

            <div class="content-block">
                <div id="tdate" class="left-content">
                    9/06/2017
                </div>
                <div id="ttime" class="right-content">
                    12:39 PM
                </div>
            </div>
        </div>
    </div>


    <script>
    let base_url = "<?php echo base_url(); ?>",
        _system = "<?php echo $title;?>";
    </script>
    <script src="<?php echo base_url('vendors/jquery/jquery-3.3.1.min.js'); ?>" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('vendors/bootstrap-5/js/bootstrap.bundle.min.js'); ?>" crossorigin="anonymous">
    </script>
    <script src="<?php echo base_url('vendors/sweetalert/sweetalert.min.js'); ?>" crossorigin="anonymous"></script>
    <script src="<?php echo base_url('build/js/custom.min.js?v='.VERSION); ?>"></script>
    <script src="<?php echo base_url('build/js/number.js?v=1'.VERSION); ?>"></script>
</body>

</html>