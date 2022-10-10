<div class="oxypowerpack-action-modal-backdrop" style="background-color: rgba(0,0,0, 0.1); position: fixed; top:0; left: 0; width: 100%; height: 100%; z-index: 10000; display:flex; justify-content:center; align-items:center;" ng-if="oxyPowerPack.aboutModal==true">
    <div class="card border-primary shadow-lg" style="width: 550px; height: 350px;">
        <div class="card-header rounded-0 p-1">
            About
            <button type="button" class="close" aria-label="Close" ng-click="oxyPowerPack.aboutModal=false">
                <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="card-body helper-area" style="max-height:300px;">
            <a href="https://oxypowerpack.com" target="_blank"><img src="<?=plugin_dir_url(__FILE__)?>../img/logo.png" style="width: 50px; float:left; margin-right:10px;"></a>
            <p><strong>OxyPowerPack <?=OXYPOWERPACK_VERSION?> <?=OXYPOWERPACK_PREVIEW_LABEL?></strong><br>Copyright © <?php echo date("Y"); ?> Emmanuel Laborin<br></p>
            <p ng-if="oxyPowerPack.BEData.license_status!='valid'"><span class="text-warning">NOT ACTIVATED</span> Please activate OxyPowerPack using your license key in order to get automatic updates and access to the design sets.</p>
            <p>This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.</p>
            <p>For feature request and bug reports please send an email to <a href="mailto:support@oxypowerpack.com">support@oxypowerpack.com</a>.</p>
            <p>OxyPowerPack is not affiliated, associated, authorized, endorsed by, or in any way officially connected with Soflyy or Oxygen Builder.</p>
        </div>
    </div>
</div>
