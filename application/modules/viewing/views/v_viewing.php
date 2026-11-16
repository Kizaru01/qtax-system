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

    <!-- ===== MAIN LAYOUT: 50/50 split ===== -->
    <div class="ns-layout">

        <!-- ========== LEFT PANEL ========== -->
        <div class="ns-left">

            <!-- Now Serving header -->
            <div class="ns-header">
                <div class="ns-header-icon">
                    <img src="<?php echo base_url('build/css/3heads.jpg'); ?>" alt="">
                </div>
                <div class="ns-header-divider"></div>
                <div class="ns-header-text">
                    <span class="ns-header-title">Now Serving</span>
                    <span class="ns-header-subtitle">Current transactions at each window</span>
                </div>
                <!-- Red decorative stripe -->
                <div class="ns-header-stripe"></div>
            </div>

            <!-- Cards container -->
            <div class="ns-cards-wrap">
                <div id="qtax-serving-grid" class="ns-grid"></div>
            </div>

            <!-- Hidden DataTable (keeps AJAX/Comet logic intact) -->
            <div style="display:none;">
                <table id="dt_serving" class="display" style="width:100%;"></table>
            </div>

        </div><!-- /ns-left -->

        <!-- ========== RIGHT PANEL ========== -->
        <div class="ns-right">

            <!-- City branding bar -->
            <div class="ns-brand">
                <div class="ns-brand-logo" title="City of San Fernando, Pampanga">
                    <img src="<?php echo base_url('build/css/logo_sanfernando.jpg'); ?>" alt="">
                </div>
                <div class="ns-brand-divider"></div>
                <div class="ns-brand-text">
                    <span class="ns-brand-city">CITY OF<br><strong>SAN FERNANDO</strong></span>
                    <span class="ns-brand-province">PAMPANGA</span>
                </div>
            </div>

            <!-- Video player -->
            <div class="ns-video-wrap">
                <video id="videoplayer" allow="autoplay" onended="onvideoended();">
                    <source src="movie.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>

        </div><!-- /ns-right -->

    </div><!-- /ns-layout -->

    <!-- ========== FOOTER ========== -->
    <footer class="ns-footer">
        <!-- Welcome message (left) -->
        <div class="ns-footer-welcome">
            <svg class="ns-footer-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M18 8c0-3.31-2.69-6-6-6S6 4.69 6 8c0 4.5 6 11 6 11s6-6.5 6-11z" stroke="white" stroke-width="1.8" fill="none"/>
                <circle cx="12" cy="8" r="2.5" stroke="white" stroke-width="1.8"/>
                <path d="M3 21c0-2 2-4 5-5m8 5c0-2-2-4-5-5" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
            </svg>
            <span>Welcome to the City of San Fernando</span>
        </div>
        <!-- Date and time (right) -->
        <div class="ns-footer-datetime">
            <div class="ns-footer-date">
                <svg class="ns-footer-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <rect x="3" y="4" width="18" height="17" rx="2" stroke="white" stroke-width="1.8"/>
                    <line x1="3" y1="9" x2="21" y2="9" stroke="white" stroke-width="1.8"/>
                    <line x1="8" y1="2" x2="8" y2="6" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <line x1="16" y1="2" x2="16" y2="6" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span id="txt-date"></span>
            </div>
            <div class="ns-footer-sep">|</div>
            <div class="ns-footer-time">
                <svg class="ns-footer-icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle cx="12" cy="12" r="9" stroke="white" stroke-width="1.8"/>
                    <line x1="12" y1="7" x2="12" y2="12" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                    <line x1="12" y1="12" x2="16" y2="15" stroke="white" stroke-width="1.8" stroke-linecap="round"/>
                </svg>
                <span id="txt-time1">12:00 PM</span>
            </div>
        </div>
    </footer>

    <!-- Audio bell -->
    <audio id="bell" allow="autoplay">
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
