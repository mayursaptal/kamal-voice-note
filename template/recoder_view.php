<label class="control-label title-plan">
    Would you like to record a voice note explaining the job ?
</label>
<div class="kvn_recoder">
    <div id="controls">
        <button id="recordButton">Record</button>
        <button id="pauseButton" disabled>Pause</button>
        <button id="stopButton" disabled>Stop</button>
    </div>

    <div id="formats">Format: start recording to see sample rate</div>
    <p><strong>Recordings:</strong></p>

    <input data-value="<?php echo ($post_id);  ?>" value="<?php echo ($post_id);  ?>" type="hidden" class="kvn_post_id" name="kvn_post_id">

    <ul id="recordingsList">

        <?php
        if ($attachments) {
            foreach ($attachments as  $attachment) { ?>
                <li>
                    <audio controls="" src="<?php echo $attachment->guid ?>"></audio>
                    <a href="<?php echo $attachment->guid ?>" download="<?php echo $attachment->post_title ?>.wav">Save to disk</a>
                    <a class="delete" href="javascript:void(0);">Delete</a>
                    <input data-value="<?php echo ($attachment->ID);  ?>" class="attachement" type="hidden" name="attachement[]" value="<?php echo $attachment->ID ?>">

                </li>
        <?php }
        } ?>

    </ul>
    <!-- inserting these scripts at the end to be able to use all the elements in the DOM -->
    <!-- <script src="https://cdn.rawgit.com/mattdiamond/Recorderjs/08e7abd9/dist/recorder.js"></script> -->
    <script type="text/javascript">
        var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
    </script>
    <script src="<?php echo KVN_PLUGIN_PTH . 'assets/js/app.js' ?>"></script>
</div>
<style>
    .kvn_recoder ul {
        list-style-type: none;
    }

    .kvn_recoder audio {
        vertical-align: bottom;
    }

    .kvn_recoder button,
    .kvn_recoder a {
        color: #fff !important;
        border: 2px solid #c21c95;
        background: #c21c95;
        text-transform: uppercase;
        border-radius: 25px;
        padding: 15px 35px;
        font-family: 'Raleway', Open Sans, sans-serif !important;
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        transition: 0.1s ease-out;
        line-height: 1em;
    }

    .kvn_recoder button:disabled {
        color: #c21c95 !important;
        border: 2px solid #c21c95;
        background: #fff;
        text-transform: uppercase;
        border-radius: 25px;
        padding: 15px 35px;
        font-family: 'Raleway', Open Sans, sans-serif !important;
        display: inline-block;
        font-size: 14px;
        font-weight: 600;
        transition: 0.1s ease-out;
        line-height: 1em;
    }
</style>