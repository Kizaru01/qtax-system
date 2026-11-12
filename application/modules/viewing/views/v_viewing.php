<!DOCTYPE html>
<html lang="en">

<head>
    <?php define("VERSION", $this->config->item('queuing_version')); ?>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo (isset($title)) ? $title . ' | Queuing' : 'Queuing System'; ?></title>
    <link href="<?php echo base_url('vendors/datatables/css/jquery.dataTables.min.css'); ?>" rel="stylesheet">
    <link href="<?php echo base_url('build/css/viewing.css?v='.VERSION); ?>" rel="stylesheet">
</head>

<body class="viewing">

    <div class="vrow">
        <div class="vcol col-50">
            <h1 class="v-title">Now Serving</h1>
            <div class="vrow">
                <div class="vcol col-100">
                    <table id="dt_serving" class="display" style="width:100%;">

                    </table>
                </div>

            </div>
        </div>
        <div class="vcol col-50">
            <div class="vrow">
                <div class="vcol col-100">
                    <div class="txt-title">CITY OF SAN FERNANDO</div>

                    <!-- <video width="320" height="240" autoplay loop> -->
                    <video id="videoplayer" width="320" height="240" allow="autoplay" onended="onvideoended();">
                        <source src="movie.mp4" type="video/mp4">
                        <!-- <source src="movie.ogg" type="video/ogg"> -->
                        Your browser does not support the video tag.
                    </video>



                </div>
            </div>
        </div>
    </div>



    <div class="txt-announcement">
        Welcome to City of San Fernando (P)
    </div>

    <div class="txt-date">
        <span id="txt-date"></span>
        <span id="txt-time1">12:00 PM</span>
    </div>

    <audio id="bell" allow="autoplay">
        <!-- <source src="horse.ogg" type="audio/ogg"> -->
        <source src="<?php echo base_url('sounds/bell.mp3'); ?>" type="audio/mpeg">
        Your browser does not support the audio element.
    </audio>


    <script>
    var base_url = "<?php echo base_url(); ?>";
    </script>
    <script type="text/javascript" src="<?php echo base_url('vendors/comet/prototype.min.js'); ?>"></script>
    <script src="<?php echo base_url('vendors/jquery/jquery-3.3.1.min.js'); ?>"></script>
    <script src="<?php echo base_url('vendors/datatables/js/jquery.dataTables.min.js'); ?>"></script>
    <script src="<?php echo base_url('build/js/viewing.js?v='.VERSION); ?>"></script>
</body>

</html>