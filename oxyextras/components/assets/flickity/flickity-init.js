jQuery(document).ready(oxygen_init_repeater_carousel);
    function oxygen_init_repeater_carousel($) {

        Flickity.createMethods.push('_createPrevNextCells');

        Flickity.prototype._createPrevNextCells = function() {
          this.on( 'select', this.setPrevNextCells );
        };

        Flickity.prototype.setPrevNextCells = function() {
          // remove classes
          changeSlideClasses( this.previousSlide, 'remove', 'is-previous' );
          changeSlideClasses( this.nextSlide, 'remove', 'is-next' );
          // set slides
          var previousI = fizzyUIUtils.modulo( this.selectedIndex - 1, this.slides.length );
          var nextI = fizzyUIUtils.modulo( this.selectedIndex + 1, this.slides.length );
          this.previousSlide = this.slides[ previousI ];
          this.nextSlide = this.slides[ nextI ];
          // add classes
          changeSlideClasses( this.previousSlide, 'add', 'is-previous' );
          changeSlideClasses( this.nextSlide, 'add', 'is-next' );
        };

        function changeSlideClasses( slide, method, className ) {
          if ( !slide ) {
            return;
          }
          slide.getCellElements().forEach( function( cellElem ) {
            cellElem.classList[ method ]( className );
          });
        } 
        

        $('.oxy-carousel-builder').each(function(){

            var $carousel = $(this),
                $inner = $(this).find('.oxy-inner-content'),
                $carouselslider = '#' + $carousel.attr('id') + ' ' + $inner.data('carousel'),
                $carouselcell = $inner.data('cell'), //todo
                $prev = $inner.data('prev'),
                $next = $inner.data('next'),
                $contain = $inner.data('contain'),
                $free_scroll = $inner.data('freescroll'),
                $draggable = $inner.data('draggable'),
                $wrap_around = $inner.data('wraparound'),
                $group_cells = $inner.data('groupcells'),
                $autoplay = $inner.data('autoplay'),
                $initial_index = $inner.data('initial') - 1,
                $accessibility = $inner.data('accessibility'),
                $cell_align = $inner.data('cellalign'),
                $right_to_left = $inner.data('righttoleft'),
                $page_dots = $inner.data('pagedots'),
                $percent = $inner.data('percent'),
                $asnavfor = $($inner.data('asnavfor') + ' ' + $($inner.data('asnavfor')).find('.oxy-inner-content').data('carousel'))[0],
                $dragthreshold = $inner.data('dragthreshold'),
                $selectedattraction = $inner.data('selectedattraction'),
                $friction = $inner.data('friction'),
                $freescrollfriction = $inner.data('freescrollfriction'),
                $bgspeed = $inner.data('bgspeed'),
                $adaptheight = $inner.data('adaptheight');
           

         let $flickityCarousel = $($carouselslider).flickity({
                    groupCells: $group_cells,
                    contain: $contain,
                    freeScroll: $free_scroll,
                    draggable: $draggable,
                    wrapAround: $wrap_around,
                    cellSelector: $carouselcell,
                    autoPlay: $autoplay,
                    initialIndex: $initial_index,
                    accessibility: $accessibility,
                    cellAlign: $cell_align,
                    rightToLeft: $right_to_left,
                    pageDots: $page_dots,
                    percentPosition: false,
                    //fullscreen: true, // not yet
                    asNavFor: $asnavfor,
                    adaptiveHeight: $adaptheight,
                    dragThreshold: $dragthreshold,
                    selectedAttraction: $selectedattraction,
                    friction: $friction,
                    freeScrollFriction: $freescrollfriction,
                    imagesLoaded: true,
                    prevNextButtons: false,
                    imagesLoaded: true,
                    watchCSS: true
            } );

               $($next).on( 'click', function(e) {
                  e.preventDefault();
                  $($carouselslider).flickity('next');
                });

                $($prev).on( 'click', function(e) {
                  e.preventDefault();
                  $($carouselslider).flickity('previous');
                });

            
               // Cells are clickable to select
                if (true === $inner.data('clickselect')) {

                    $flickityCarousel.on( 'staticClick.flickity', function( event, pointer, cellElement, cellIndex ) {
                      if ( typeof cellIndex == 'number' ) {
                        $flickityCarousel.flickity( 'selectCell', cellIndex );
                      }
                    });
                
                }
            
                // Parallax Elems    
               if (true === $inner.data('parallaxbg')) {
            
                   var $parallaxCells = $flickityCarousel.find($carouselcell);
                   
                    var docStyle = document.documentElement.style;
                    var transformProp = typeof docStyle.transform == 'string' ?
                      'transform' : 'WebkitTransform';
                   
                    var flkty = $flickityCarousel.data('flickity');
                   
                   
                    function parallaxbg() {     
                          flkty.slides.forEach( function( slide, i ) { 
                               
                            var $parallaxCell = $parallaxCells[i];
                            var $parallaxElem = $($parallaxCell).find('[data-speed]');
                            
                            var x = ( slide.target + flkty.x ); // Cell transform
                            
                              $parallaxElem.each(function(){ 
                                  
                                  var $parallaxSpeed = $(this).attr('data-speed');
                                  var $parallaxElemDom = $(this)[0];
                                  var trans = x * (-1/$parallaxSpeed); // Cell transform * paralax speed
                                  
                                  $parallaxElemDom.style[ transformProp ] = 'translateX(' + trans + 'px)';
                                  
                              });      
                              
                          });
                      }
                   
                   parallaxbg();
                   
                    $flickityCarousel.on( 'scroll.flickity', function( event, progress ) {
                        
                        parallaxbg();
                      
                    });
                   
                   
               }      
             
            

            $carousel.find('.oxy-carousel-next').parent('.oxy-carousel-navigation').addClass('oxy-carousel-navigation_next');
            $carousel.find('.oxy-carousel-previous').parent('.oxy-carousel-navigation').addClass('oxy-carousel-navigation_prev');

        });



    };