if (jQuery("body").attr("ng-controller"))
    var bodyElement = angular.element("body"),
        $scope = bodyElement.scope();
function processOELink(e) {
    var o = jQuery(".oxygen-link-button");
    setTimeout(
        function () {
            jQuery("<textarea>").attr("id", "ct-link-dialog-txt").css("display", "none").attr("data-linkProperty", o.attr("data-linkProperty")).attr("data-linkTarget", o.attr("data-linkTarget")).attr("data-linkField", e).appendTo("body"),
                wpLink.open("ct-link-dialog-txt"),
                jQuery("#wp-link-url").val($scope.iframeScope.component.options[$scope.iframeScope.component.active.id].model[e]),
                jQuery("#wp-link-wrap").removeClass("has-text-field"),
                jQuery("#oxygen-link-data-dialog-opener").insertAfter(jQuery("#wp-link-wrap.has-text-field #wp-link-url")),
                jQuery("#oxygen-link-data-dialog").insertAfter(jQuery("#wp-link-wrap.has-text-field")),
                ($scope.showLinkDataDialog = !1),
                $scope.$apply();
        },
        0,
        !1
    );
}
!(function (e) {
    var o = setInterval(function (e) {
        var t;
        "undefined" != typeof iframeScope &&
            (((t = iframeScope).ouDynamicILUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_img_url", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicICBImage = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_image_comparison_oeic_before_image", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicICAImage = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_image_comparison_oeic_after_image", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicDHUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_dual_heading_link", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicFBUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_flip_box_back_link", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicCDUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_countdown_redirect_link", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicOEGPID=function(e) {
                e = (e = e.replace(/\"/gi,"'")).replace(/oxygen/gi,"oxyultimate");
                var o=t.component.active.id;
                t.setOptionModel("oxy-oe_acf_gallery_page_id","oedata_"+btoa(e),o)
            }),
             
            (t.oeDynamicHPImageAlt = function (e) {
                e = (e = e.replace(/\"/gi,"'")).replace(/oxygen/gi,"oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_hotspots_oe_hs_image_alt","oedata_"+btoa(e),o);
            }),
            (t.oeDynamicVideoUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_video_video_url", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicSILUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_scroll_image_image_url", "oedata_" + btoa(e), o);
            }),
            (t.oeDynamicRILUrl = function (e) {
                e = (e = e.replace(/\"/gi, "'")).replace(/oxygen/gi, "oxyextended");
                var o = t.component.active.id;
                t.setOptionModel("oxy-oe_random_image_image_url", "oedata_" + btoa(e), o);
            }),
            clearInterval(o));
    }, 100);
})(jQuery);
