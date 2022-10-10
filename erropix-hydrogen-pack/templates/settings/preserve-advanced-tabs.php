<section class="section" id="section-preserveadvancedtabs" data-dependon="#settings_preserveadvancedtabs_enabled">
    <div class="section-header">
        <h3 class="section-title">Preserve Advanced Tabs</h3>
    </div>

    <div class="section-body">
        <?php
        $name = "settings[preserveAdvancedTabs][requireAltKey]";
        $value = $settings->preserveAdvancedTabs->requireAltKey;
        ?>
        <div class="field">
            <div class="field-label">
                <label for="<?= $ui->nameToId($name) ?>">Require holding Alt/Option key</label>
            </div>
            <div class="field-control">
                <?= $ui->checkbox($name, $value) ?>
            </div>
        </div>
    </div>
</section>