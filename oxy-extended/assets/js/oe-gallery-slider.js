! (function ($) {
    OEGallerySlider = function ( settings ) {
        this.id = settings.id,
		this.compClass = ".oe-gallery-slider-" + settings.id,
		this.elements = "",
		this.slidesPerView = settings.slidesPerView,
		this.slidesPerColumn = settings.slidesPerColumn,
		this.slidesToScroll = settings.slidesToScroll,
		this.settings = settings,
		this.swipers = {};
		
		if ( this._isSlideshow() ) {
			this.slidesPerView = settings.slideshow_slidesPerView;
		}

		if ( typeof Swiper === 'undefined' ) {
			$(window).on('load', $.proxy(function() {
				if ( typeof Swiper === 'undefined' ) {
					return;
				} else {
					this._init();
				}
			}, this) );
		} else {
			this._init();
		}
    };

	OEGallerySlider.prototype = {
        id: "",
        compClass: "",
        elements: "",
        slidesPerView: {},
        slidesToScroll: {},
        settings: {},
        swipers: {},

        _init: function () {
            this.elements = {
				mainSwiper: this.compClass
			};
			this.elements.swiperSlide = $(this.elements.mainSwiper).find('.swiper-slide');

			if (1 >= this._getSlidesCount()) {
				return;
			}

			var swiperOpts = this._getSwiperOptions();
			this.swipers.main = new Swiper(this.elements.mainSwiper, swiperOpts.main);
			if ( this._isSlideshow() ) {
				$(this.compClass).closest(".oe-gallery-slider-wrapper").addClass("oe-thumbs-slider-" + this.id);
				this.swipers.main.controller.control = this.swipers.thumbs = new Swiper(".oe-thumbs-slider-" + this.id + " .oe-thumbnails-swiper", swiperOpts.thumbs);
				this.swipers.thumbs.controller.control = this.swipers.main;
			}
			var self = this;
			$(this.compClass).on("mouseenter", function (e) {
				self.settings.pause_on_hover && self.swipers.main.autoplay.stop();
			}), $(this.compClass).on("mouseleave", function (e) {
				self.settings.pause_on_hover && self.swipers.main.autoplay.start();
			})
        },

		_getEffect: function () {
			return this.settings.effect
		},

		_getSlidesCount: function () {
			return this.elements.swiperSlide.length
		},

		_getInitialSlide: function () {
			return this.settings.initialSlide
		},

		_getSpaceBetween: function () {
			var value = this.settings.spaceBetween.desktop;
			value = parseInt(value);
			return isNaN(value) ? 10 : value;
		},

		_getSpaceBetweenTablet: function () {
			var value = this.settings.spaceBetween.tablet;
			value = parseInt(value);
			return isNaN(value) ? this._getSpaceBetween() : value;
		},

		_getSpaceBetweenLandscape: function () {
			var value = this.settings.spaceBetween.landscape;
			value = parseInt(value);
			return isNaN(value) ? this._getSpaceBetweenTablet() : value;
		},

		_getSpaceBetweenPortrait: function () {
			var value = this.settings.spaceBetween.portrait;
			value = parseInt(value);
			return isNaN(value) ? this._getSpaceBetweenLandscape() : value;
		},

		_getSlidesPerView: function () {
			if (this._isSlideshow()) {
				return 1;
			}
			var value = this.slidesPerView.desktop;
			return Math.min(this._getSlidesCount(), +value)
		},

        _getSlidesPerViewTablet: function () {
			if (this._isSlideshow()) {
				return 1;
			}

			var slidesPerView = this.slidesPerView.tablet;

			if (slidesPerView === '' || slidesPerView === 0) {
				slidesPerView = this.slidesPerView.desktop
			}

			if (!slidesPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 3);
			}

			return Math.min(this._getSlidesCount(), +slidesPerView);
		},

		_getSlidesPerViewLandscape: function () {
			if (this._isSlideshow()) {
				return 1;
			}
			var slidesPerView = this.slidesPerView.landscape;

			if (slidesPerView === '' || slidesPerView === 0) {
				slidesPerView = this._getSlidesPerViewTablet();
			}

			if (!slidesPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 3);
			}

			return Math.min(this._getSlidesCount(), +slidesPerView);
		},

		_getSlidesPerViewPortrait: function () {
			if (this._isSlideshow()) {
				return 1;
			}
			var slidesPerView = this.slidesPerView.portrait;

			if (slidesPerView === '' || slidesPerView === 0) {
				slidesPerView = this._getSlidesPerViewLandscape();
			}

			if (!slidesPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 3);
			}

			return Math.min(this._getSlidesCount(), +slidesPerView);
		},

        _getSlidesPerColumn: function () {
            return this._isSlideshow() ? 1 : this.slidesPerColumn.desktop
        },

		_getSlidesPerColumnTablet: function () {
			if (this._isSlideshow()) {
				return 1;
			}
			var slidesPerView = this.slidesPerColumn.tablet;

			if (slidesPerView === '' || slidesPerView === 0) {
				slidesPerView = this.slidesPerColumn.desktop;
			}

			if (!slidesPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 1);
			}

			return slidesPerView;
		},

		_getSlidesPerColumnLandscape: function () {
			if (this._isSlideshow()) {
				return 1;
			}
			var slidesPerView = this.slidesPerColumn.landscape;

			if (slidesPerView === '' || slidesPerView === 0) {
				slidesPerView = this._getSlidesPerColumnTablet();
			}

			if (!slidesPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 1);
			}

			return slidesPerView;
		},

		_getSlidesPerColumnPortrait: function () {
			if (this._isSlideshow()) {
				return 1;
			}
			var slidesPerView = this.slidesPerColumn.portrait;

			if (slidesPerView === '' || slidesPerView === 0) {
				slidesPerView = this._getSlidesPerColumnLandscape();
			}

			if (!slidesPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 1);
			}

			return slidesPerView;
		},

		_getThumbsSlidesPerView: function () {
			var thumbsPerView = this.slidesPerView.desktop;
			return Math.min(this._getSlidesCount(), +thumbsPerView);
		},

		_getThumbsSlidesPerViewTablet: function () {
			var thumbsPerView = this.slidesPerView.tablet;

			if (thumbsPerView === '' || thumbsPerView === 0) {
				thumbsPerView = this.slidesPerView.desktop;
			}

			if (!thumbsPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 3);
			}

			return Math.min(this._getSlidesCount(), +thumbsPerView);
		},

		_getThumbsSlidesPerViewLandscape: function () {
			var thumbsPerView = this.slidesPerView.landscape;

			if (thumbsPerView === '' || thumbsPerView === 0) {
				thumbsPerView = this._getThumbsSlidesPerViewTablet();
			}

			if (!thumbsPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 3);
			}

			return Math.min(this._getSlidesCount(), +thumbsPerView);
		},

		_getThumbsSlidesPerViewPortrait: function () {
			var thumbsPerView = this.slidesPerView.portrait;

			if (thumbsPerView === '' || thumbsPerView === 0) {
				thumbsPerView = this._getThumbsSlidesPerViewLandscape();
			}

			if (!thumbsPerView && 'coverflow' === this.settings.type) {
				return Math.min(this._getSlidesCount(), 3);
			}

			return Math.min(this._getSlidesCount(), +thumbsPerView);
		},

        _getSlidesToScroll: function (device) {
			if (!this._isSlideshow() && 'slide' === this._getEffect()) {
				var slides = this.slidesToScroll[device];

				return Math.min(this._getSlidesCount(), +slides || 1);
			}

			return 1;
		},

        _getSlidesToScrollDesktop: function () {
            return this._getSlidesToScroll("desktop")
        },

        _getSlidesToScrollTablet: function () {
            return this._getSlidesToScroll("tablet")
        },

        _getSlidesToScrollLandscape: function () {
            return this._getSlidesToScroll("landscape")
        },

        _getSlidesToScrollPortrait: function () {
            return this._getSlidesToScroll("portrait")
        },

        _getSwiperOptions: function () {
            var desktopBreakpoint = this.settings.breakpoint.desktop,
				tabletBreakpoint = this.settings.breakpoint.tablet,
				landscapeBreakpoint = this.settings.breakpoint.landscape,
				portraitBreakpoint = this.settings.breakpoint.portrait,
				compClass = this.compClass;

			var options = {
                navigation: {
                    prevEl: $(compClass).closest(".oe-gallery-slider").find(".oe-swiper-button-prev")[0],
                    nextEl: $(compClass).closest(".oe-gallery-slider").find(".oe-swiper-button-next")[0]
                },
                pagination: {
                    el: compClass + " .swiper-pagination",
                    type: this.settings.pagination,
                    dynamicBullets: this.settings.dynamicBullets,
                    clickable: !0
                },
                grabCursor: !1,
                effect: this._getEffect(),
                initialSlide: this._getInitialSlide(),
                slidesPerView: this._getSlidesPerView(),
                slidesPerColumn: this._getSlidesPerColumn(),
                slidesPerColumnFill: "row",
                slidesPerGroup: this._getSlidesToScrollDesktop(),
                spaceBetween: this._getSpaceBetween(),
                centeredSlides: this.settings.centered,
                loop: this.settings.loop,
                loopedSlides: this._getSlidesCount(),
                speed: this.settings.speed,
                autoHeight: this.settings.autoHeight,
                breakpoints: {}
            };

            if ( this.settings.isBuilderActive ) {
				options.simulateTouch = false;
				options.shortSwipes = false;
				options.longSwipes = false;
			}

			if (!this.settings.isBuilderActive && this.settings.autoplay_speed !== false) {
				options.autoplay = {
					delay: this.settings.autoplay_speed,
					disableOnInteraction: this.settings.pause_on_interaction
				};
			}

			if ( "cube" !== this._getEffect() && "fade" !== this._getEffect() ) {
				options.breakpoints[desktopBreakpoint] = {
					slidesPerView: this._getSlidesPerView(),
					slidesPerColumn: this._getSlidesPerColumn(),
					slidesPerGroup: this._getSlidesToScrollDesktop(),
					spaceBetween: this._getSpaceBetween()
				};
				options.breakpoints[tabletBreakpoint] = {
					slidesPerView: this._getSlidesPerViewTablet(),
					slidesPerColumn: this._getSlidesPerColumnTablet(),
					slidesPerGroup: this._getSlidesToScrollTablet(),
					spaceBetween: this._getSpaceBetweenTablet()
				};
				options.breakpoints[landscapeBreakpoint] = {
					slidesPerView: this._getSlidesPerViewLandscape(),
					slidesPerColumn: this._getSlidesPerColumnLandscape(),
					slidesPerGroup: this._getSlidesToScrollLandscape(),
					spaceBetween: this._getSpaceBetweenLandscape()
				};
				options.breakpoints[portraitBreakpoint] = {
					slidesPerView: this._getSlidesPerViewPortrait(),
					slidesPerColumn: this._getSlidesPerColumnPortrait(),
					slidesPerGroup: this._getSlidesToScrollPortrait(),
					spaceBetween: this._getSpaceBetweenPortrait()
				}
			}

			if ( "cube" == this._getEffect() ) {
                options.cubeEffect = {
					shadow: true,
					slideShadows: true,
					shadowOffset: 20,
					shadowScale: .94
				}
            }

            var thumbOptions = {
				setWrapperSize: true,
				slidesPerView: this._getThumbsSlidesPerView(),
				initialSlide: this._getInitialSlide(),
				slideToClickedSlide: true,
				spaceBetween: this._getSpaceBetween(),
				centeredSlides: this.settings.centered,
				loop: this.settings.loop,
				loopedSlides: this._getSlidesCount(),
				speed: this.settings.speed,
				watchSlidesVisibility: true,
				watchSlidesProgress: true,
				onSlideChangeEnd: function (e) {
					e.loopFix()
				},
				breakpoints: {}
			};
			thumbOptions.breakpoints[tabletBreakpoint] = {
				slidesPerView: this._getThumbsSlidesPerViewTablet(),
				spaceBetween: this._getSpaceBetweenTablet()
			};
			thumbOptions.breakpoints[landscapeBreakpoint] = {
				slidesPerView: this._getThumbsSlidesPerViewLandscape(),
				spaceBetween: this._getSpaceBetweenLandscape()
			};
			thumbOptions.breakpoints[portraitBreakpoint] = {
				slidesPerView: this._getThumbsSlidesPerViewPortrait(),
				spaceBetween: this._getSpaceBetweenPortrait()
			};
			
			if ( "coverflow" === this.settings.type ) {
				options.effect = "coverflow";
			}

			if ( this._isSlideshow() ) {
				options.slidesPerView = 1;
				delete options.breakpoints;
			}

			return {
				main: options,
				thumbs: thumbOptions
			}
        },

        _isSlideshow: function () {
            return "slideshow" === this.settings.type
        },
        _onElementChange: function (e) {
            0 === e.indexOf("width") && this.swipers.main.onResize(), 0 === e.indexOf("spaceBetween") && this._updateSpaceBetween(this.swipers.main, e)
        },
        _updateSpaceBetween: function (e, t) {
            var s = this._getSpaceBetween(),
                i = t.match("space_between_(.*)");
            if (i) {
                var n = {
                    tablet: this.settings.breakpoint.tablet,
                    landscape: this.settings.breakpoint.landscape,
                    portrait: this.settings.breakpoint.portrait
                };
                e.params.breakpoints[n[i[1]]].spaceBetween = s
            } else e.originalParams.spaceBetween = s;
            e.params.spaceBetween = s, e.onResize()
        }
    }
})(jQuery);