
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <form method="post" action="options.php">
                    <?php
                    settings_fields( 'oxypowerpack' );
                    ?>
                    <h2 class="text-secondary">Features</h2>
                    <div class="row" style="display:none;" helper-title="Drawer Enabled" helper-text="You shouldn't be able to see this setting. Please don't turn it off, it can break other stuff.">
                        <div class="col-sm-7">
                            Drawer Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_drawer_enabled">
                                <input type="checkbox" id="oxypowerpack_drawer_enabled" name="oxypowerpack_drawer_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_drawer_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Interactivity Engine" helper-text="Enable or disable the interactivity engine. If disabled, nothing will be executed in frontend and the 'interactivity' tab will be removed from the OxyPowerPack drawer inside Oxygen Builder. Any previously defined interactions will persist but won't be executed until it is enabled again.">
                        <div class="col-sm-7">
                            Interactivity Engine Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_events_enabled">
                                <input type="checkbox" id="oxypowerpack_events_enabled" name="oxypowerpack_events_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_events_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Custom Attributes" helper-text="Enable or disable custom attributes. If disabled, nothing will be output in frontend and the related UI will be removed from Oxygen Builder. Any previously defined attributes will persist but won't be output in frontend until it is enabled again.">
                        <div class="col-sm-7">
                            Custom Attributes Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_attributes_enabled">
                                <input type="checkbox" id="oxypowerpack_attributes_enabled" name="oxypowerpack_attributes_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_attributes_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Popovers" helper-text="Enable or disable Popovers. If disabled, nothing will be output in frontend and the related UI will be removed from Oxygen Builder. Any previously defined popovers will persist but won't be shown in frontend until it is enabled again.">
                        <div class="col-sm-7">
                            Popovers Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_tooltips_enabled">
                                <input type="checkbox" id="oxypowerpack_tooltips_enabled" name="oxypowerpack_tooltips_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_tooltips_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Parallax Effect" helper-text="Enable or disable the parallax effect. If disabled, no effect will be applied in frontend and the related UI will be removed from Oxygen Builder. Any previously defined parallax effects will persist but won't be shown in frontend until it is enabled again.">
                        <div class="col-sm-7">
                            Parallax Effect Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_parallax_enabled">
                                <input type="checkbox" id="oxypowerpack_parallax_enabled" name="oxypowerpack_parallax_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_parallax_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Parallax Efect on Mobile Devices" helper-text="Enables or disables the parallax effect on mobile devices. If parallax effect is disabled, this setting won't have any effect.">
                        <div class="col-sm-7">
                            Parallax Effect Enabled on Mobile Devices
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_parallax_enabled_mobile">
                                <input type="checkbox" id="oxypowerpack_parallax_enabled_mobile" name="oxypowerpack_parallax_enabled_mobile" class="" value="true" <?php checked(get_option('oxypowerpack_parallax_enabled_mobile'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Text Rotator" helper-text="Enable or disable the text rotator feature. If disabled, no effect will be applied in frontend and the related UI will be removed from Oxygen Builder. Any previously defined rotators will persist but won't be shown in frontend until it is enabled again.">
                        <div class="col-sm-7">
                            Text Rotator Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_textrotator_enabled">
                                <input type="checkbox" id="oxypowerpack_textrotator_enabled" name="oxypowerpack_textrotator_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_textrotator_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Lazy-Load Images" helper-text="Enable or disable the lazy-loading images feature. If disabled, no effect will be applied in frontend and the related UI will be removed from Oxygen Builder. Any previously defined lazy-loadable images will persist but won't be shown in frontend until it is enabled again (images will be output as regular non-lazy loadable images).">
                        <div class="col-sm-7">
                            Lazy-Load Images Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_lazyload">
                                <input type="checkbox" id="oxypowerpack_lazyload" name="oxypowerpack_lazyload" class="" value="true" <?php checked(get_option('oxypowerpack_lazyload'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="Re-login Window" helper-text="Enables or disables the Re-auth window that allows to login again without leaving Oxygen Builder. If Oxygen Builder adds this same functionality in the future, it is a good idea to turn off this setting to avoid redundancy or collisions.">
                        <div class="col-sm-7">
                            Re-login Window Enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_login_enabled">
                                <input type="checkbox" id="oxypowerpack_login_enabled" name="oxypowerpack_login_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_login_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="External SASS Workflow" helper-text="Enables or disables the external SASS workflow that allows to edit CSS/SCSS locally in a computer and upload the styles using a Gulp script. Turn this setting off on production sites, or at least change the secret code regularly.">
                        <div class="col-sm-7">
                            External SASS Workflow enabled
                        </div>
                        <div class="col-sm-5 px-0">
                            <label class="switch" for="oxypowerpack_sass_enabled">
                                <input type="checkbox" id="oxypowerpack_sass_enabled" name="oxypowerpack_sass_enabled" class="" value="true" <?php checked(get_option('oxypowerpack_sass_enabled'), 'true'); ?>>
                                <div class="slider round"></div>
                            </label>
                        </div>
                    </div>
                    <div class="row" helper-title="SASS Workflow Secret Code" helper-text="Secret code needed by the external Gulp script to be able to upload the styles. The initial value is random, but it is a good idea to change it regularly, ie. when a developer is not working anymore on the site, preventing him to change the styles.">
                        <div class="col-sm-7">
                            SASS Workflow Secret Code
                        </div>
                        <div class="col-sm-5 px-0">
                            <input type="text" id="oxypowerpack_sass_secret" class="form-control form-control-sm" name="oxypowerpack_sass_secret" class="" value="<?php echo get_option('oxypowerpack_sass_secret') ?>">
                        </div>
                    </div>
                    <div class="row" helper-title="MapBox Access Token" helper-text="The MapBox Access Token is a key code needed to render the beautiful MapBox premium base maps. You can sign up for free MapBox account and grab your key at <a href='https://www.mapbox.com/' style='color:blue;' target='_blank'>mapbox.com</a>.">
                        <div class="col-sm-7">
                            MapBox Access Token
                        </div>
                        <div class="col-sm-5 px-0">
                            <input type="text" id="oxypowerpack_mapbox_key" class="form-control form-control-sm" name="oxypowerpack_mapbox_key" class="" value="<?php echo get_option('oxypowerpack_mapbox_key') ?>">
                        </div>
                    </div>

                    <?php submit_button('Save Settings', 'btn btn-primary btn-sm'); ?>
                </form>
            </div>
            <div class="col-sm-6">
                <h3 class="text-secondary" id="helper-title"></h3>
                <p id="helper-text"></p>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="row">
            <div class="col-sm-6">
                <h2 class="text-secondary">Design Set</h2>
                <p>OxyPowerPack comes with a design set with several multi-purpose premade content blocks and full pages, including coming soon and maintenance pages. To be able to use them, make sure you have the "Enable 3rd Party Design Sets" option enabled in Oxygen > Settings > Library.</p>
            </div>
            <div class="col-sm-6">
                <h2 class="text-secondary">External SASS Workflow</h2>
                <p>Download the SASS workflow Gulp script from the GirHub repository (<a href="https://github.com/OxyPowerPack/oxy-sass" style="color: black;" target="_blank">here</a>). There, you'll find instructions on how to set it up.</p>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    jQuery('[helper-title]').on('mouseover', function(){
        jQuery("#helper-title").html(jQuery(this).attr('helper-title'));
        jQuery("#helper-text").html(jQuery(this).attr('helper-text'));
    });
</script>