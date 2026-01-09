<?php
if (!defined('ABSPATH'))
    exit;

function hras_render_admin_page()
{
    ?>
    <div class="wrap">
        <h1>Headless REST API Security</h1>
        <form action="options.php" method="post">
            <?php
            settings_fields('hras_settings_group');
            do_settings_sections('hras-settings');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}