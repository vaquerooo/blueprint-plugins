(function($) {
    var t = setInterval(function () {
        var $scope = iframeScope;
       
        if (typeof iframeScope !== "undefined") {
            (($scope.insertShortcodeToCounterStart = function (text) {
                text=text.replace(/\"/ig, "'");
                var id = $scope.component.active.id;
                $scope.setOptionModel('oxy-counter_start', text, id); // Counter Start
            }),
            ($scope.insertShortcodeToCounterEnd = function (text) {
                text=text.replace(/\"/ig, "'");
                var id = $scope.component.active.id;
                $scope.setOptionModel('oxy-counter_end', text, id);  // Counter End
            }),
            ($scope.insertShortcodeToFormID = function (text) {
                text=text.replace(/\"/ig, "'");
                var id = $scope.component.active.id;
                $scope.setOptionModel('oxy-fluent-form_acf_form_field', text, id);  // Fluent Form ID
            }), 
           
            clearInterval(t));
        }
    }, 2);
    
    $('body').on('.oxy-burger-trigger', function (e) {
        console.log('clicked burger');
        $(this).children('.hamburger').toggleClass('is-active');
    });
    
})(jQuery);








