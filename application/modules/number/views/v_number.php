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
    <div class="min-vh-100 d-flex flex-column">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <h2 class="display-4" style="margin: 0 auto;"><strong>Real Property Tax Queuing</strong></h2>
        </nav>
        <div class="text-center mb-5 mt-5">
            <h2 class="display-4">Choose your transaction</h2>
            <h2>and take a ticket</h2>
        </div>

        <div class="row mx-auto w-100 gy-3 align-items-stretch" style="max-width: 1400px;">
            <div class="col-12 col-md-6 content-regular">
                <button id="btn-regular" class="bg-primary btn-modal w-100 h-100 text-white text-wrap p-4 p-lg-5" type="button">
                    <img src="<?php echo base_url('vendors/bootstrap-5/female-and-male-shapes-silhouettes.png?v='.VERSION); ?>"
                        class="card-img-top" alt="" style="max-width: 180px;">
                    <span class="d-block display-5 mt-4">Regular</span>
                </button>
            </div>
            <div class="col-12 col-md-6 content-special">
                <button id="btn-special" class="bg-danger btn-modal w-100 h-100 text-white text-wrap p-4 p-lg-5" type="button">
                    <img src="<?php echo base_url('vendors/bootstrap-5/special-icon.png?v='.VERSION); ?>"
                        class="card-img-top" alt="" style="max-width: 180px;">
                    <span class="d-block display-5 mt-4">Special</span>
                    <span class="d-block fs-5">(PWDs, Senior Citizens, Pregnant Women)</span>
                </button>
            </div>
        </div>

        <footer class="text-center mt-auto pt-4">
            <p>Developed & Maintained by City Information and Communications Technology Office </p>
        </footer>
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