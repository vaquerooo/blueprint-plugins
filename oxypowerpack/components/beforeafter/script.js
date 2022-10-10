oxyBeforeAfterInit = function($element, before_image, after_image, before_lbl, after_lbl){
    let loadedImages = 0;
    let imageLoadFn = function(){
        loadedImages++;
        if( loadedImages == 2){
            $element.twentytwenty({before_label: before_lbl, after_label: after_lbl});
        }
    };
    let before = new Image();
    before.src = before_image;
    $element.append(before)
    before.onload = imageLoadFn;

    let after = new Image();
    after.src = after_image;
    $element.append(after)
    after.onload = imageLoadFn;
};
