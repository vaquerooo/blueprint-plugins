jQuery(document).ready(function(){

    var observer = new IntersectionObserver(function (entries, self) {

        entries.forEach(function (entry) {
            // process just the images that are intersecting.
            if (entry.isIntersecting) {
                var imgUrl = entry.target.getAttribute('lazyload-src');
                entry.target.setAttribute('src', imgUrl );
                entry.target.removeAttribute( 'lazyload-src' );
                if(jQuery(window).scrollTop() > 100) {
                    jQuery(entry.target).hide();
                    jQuery(entry.target).fadeIn('fast');
                }

                self.unobserve(entry.target);

                if(entry.target.hasAttribute('lazyload-srcset')){
                    var imgSrcset = entry.target.getAttribute('lazyload-srcset');
                    entry.target.setAttribute('srcset', imgSrcset );
                    entry.target.removeAttribute( 'lazyload-srcset' );
                }
            }
        });
    }, {
        rootMargin: '0px 0px 0px 0px',
        threshold: 0
    });

    var imgs = document.querySelectorAll('[lazyload-src]');
    imgs.forEach(function (img) {
        observer.observe(img);

    });

});
