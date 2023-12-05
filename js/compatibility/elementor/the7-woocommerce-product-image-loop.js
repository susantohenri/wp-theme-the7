(function ($) {
    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/the7-woocommerce-loop-product-image.default", function ($widget, $) {
            $(document).ready(function () {
                if (elementorFrontend.isEditMode()) {
                    The7ElementorAnimation.patchElementsAnimation($widget);
                }
                $widget.productSlider();
            })
        });


        if (!elementorFrontend.isEditMode()) {
            The7ElementorAnimation.patchElementsAnimation($('.elementor-widget-the7-woocommerce-loop-product-image .the7-overlay-content'));
        }


        if (elementorFrontend.isEditMode()) {
            elementorEditorAddOnChangeHandler("the7-woocommerce-loop-product-image", refresh);
        }

        function refresh(controlView, widgetView) {
            let refresh_controls = [
                ...The7ElementorSettings.getResponsiveSettingList('hover_visibility'),
            ];
            const controlName = controlView.model.get('name');
            if (-1 !== refresh_controls.indexOf(controlName)) {
                const $widget = $(widgetView.$el);
                const widgetData = $widget.data('productSlider');
                if (typeof widgetData !== 'undefined') {
                    widgetData.refresh();
                } else {
                    $widget.productSlider();
                }
            }
        }
    });

    $.productSlider = function (el) {
        const data = {
            selectors: {
                slider: '.elementor-slides-wrapper',
                slide: 'the7-swiper-slide',
                activeSlide: '.swiper-slide-active',
                activeDuplicate: '.swiper-slide-duplicate-active'
            },
        };
        let $widget = $(el),
            methods,
            elementorSettings,
            elementorAnimation,
            elementsWithAnimation,
            settings,
            overlayTemplateExist = false,
            $overlay,
            visibility,
            elements = {
                $swiperContainer: $widget.find(data.selectors.slider),
                animatedSlides: {},
                activeElements: []
            };
            elements.$slides = elements.$swiperContainer.find('.' + data.selectors.slide);
        const $imgSlider = $widget.find(".elementor-slides-wrapper");

        // Store a reference to the object.
        $.data(el, "productSlider", $widget);

        // Private methods.
        methods = {
            init: function () {
                $widget.layzrInitialisation();
                $overlay = $widget.find('.the7-overlay-content');
                if ($overlay.length) {
                    overlayTemplateExist = true;
                    elementorAnimation = new The7ElementorAnimation();
                    elementsWithAnimation = elementorAnimation.findAnimationsInNode($overlay);

                    if($widget.find('a.post-thumbnail-rollover').length && !elementorFrontend.isEditMode()){
                        $overlay.css('cursor', 'pointer');
                        $overlay.on('click', function(e){
                            let $this = $(this),
                                $thisParent = $this.parent();
                            let $imgWrap =  $thisParent.hasClass('product-image-carousel-wrap') ? $thisParent.find('.dt-owl-item.active a.post-thumbnail-rollover') : $thisParent.find('a.post-thumbnail-rollover');
                            if((e.target.tagName.toLowerCase() !== 'a' && !$(e.target).parents('a').length) && e.target.tagName.toLowerCase() !== 'button' ){
                                if(typeof $imgWrap.attr('data-elementor-open-lightbox') != 'undefined'){
                                    //for lighbox
                                    $imgWrap.trigger("click");
                                }else{
                                    //for regular img link
                                    window.location.href = $imgWrap.attr('href');
                                }
                            }
                        })
                    }
                }

                elementorSettings = new The7ElementorSettings($widget);
                settings = elementorSettings.getSettings();
                this.initSlider();
                $widget.refresh();
                 if (overlayTemplateExist) {
                    visibility = The7ElementorSettings.getResponsiveControlValue(settings, 'hover_visibility');

                    switch (visibility) {
                        case 'hover-hide':
                            elementorAnimation.animateElements(elementsWithAnimation);
                            break;
                    }


                }
                 // Support image transitions.
                $widget.one('mouseenter', function() {
                    $widget.find('.post-thumbnail-rollover img').addClass('run-img-transitions');
                });
                
            },
            initSlider: async function () {
                if ($imgSlider.length) {
                     //Swiper
                    const Swiper = elementorFrontend.utils.swiper;
                    swiper = await new Swiper($imgSlider, this.getSwiperOptions());
                    methods.loopLazyFix();

                }
            },
            getSwiperOptions: function () {

                swiperOptions = {
                    grabCursor: true,
                    loop: true,
                    loopPreventsSlide: true,
                    pauseOnMouseEnter: true,
                    speed: settings['transition_speed'],
                    effect: settings['transition'],
                    slideClass: 'the7-swiper-slide',
                    nested: true,
                };

                const navigation = true,
                    pagination = true;
                if (navigation) {
                    swiperOptions.navigation = {
                        prevEl: elements.$swiperContainer.siblings('.the7-swiper-button-prev')[0],
                        nextEl: elements.$swiperContainer.siblings('.the7-swiper-button-next')[0]
                    };
                }
                if (pagination) {
                    swiperOptions.pagination = {
                        el: elements.$swiperContainer.siblings('.swiper-pagination')[0],
                        type: 'bullets',
                        bulletActiveClass: 'active',
                        bulletClass: 'owl-dot',
                        clickable: true,
                        renderBullet: function (index, className) {
                            return '<button role="button" class="' + className + '" aria-label="Go to slide ' + index + 1 + '"><span></span></button>';
                        },
                    };

                }
                return swiperOptions;
            },
            getInitialSlide() {
                return 0;
            },
            loopLazyFix: function () {
                if (swiper.params.loop){
                let $swiperDuplicates = $(swiper.wrapperEl).children("." + (swiper.params.slideDuplicateClass));
                    $swiperDuplicates.find(".is-loading").removeClass("is-loading");
                    $swiperDuplicates.layzrInitialisation();
                }
            },
            handleResize: function () {
                if (overlayTemplateExist) {
                    visibility = The7ElementorSettings.getResponsiveControlValue(settings, 'hover_visibility');
                    switch (visibility) {
                        case 'always':
                            elementorAnimation.animateElements(elementsWithAnimation);
                            break;
                        case 'disabled':
                            elementorAnimation.resetElements(elementsWithAnimation);
                            break;
                    }
                }
            },
            bindEvents: function () {
                elementorFrontend.elements.$window.on('the7-resize-width-debounce', methods.handleResize);
                if (overlayTemplateExist) {
                    $widget.on({mouseenter: methods.mouseenter, mouseleave: methods.mouseleave});
                }
            },
            unBindEvents: function () {
                elementorFrontend.elements.$window.off('the7-resize-width-debounce', methods.handleResize);
                if (overlayTemplateExist) {
                    $widget.off({mouseenter: methods.mouseenter, mouseleave: methods.mouseleave});
                }
            },
            mouseenter: function () {
                switch (visibility) {
                    case 'hover':
                        methods.addAnimation();
                        break;
                    case 'hover-hide':
                        methods.resetAnimation();
                        break;
                }
            },
            mouseleave: function () {
                switch (visibility) {
                    case 'hover':
                        methods.resetAnimation();
                        break;
                    case 'hover-hide':
                        methods.addAnimation();
                        break;
                }
            },
            onOverlayTransitionsEnd: function (event){
                if (event.originalEvent.propertyName === "opacity") {
                    elementorAnimation.resetElements(elementsWithAnimation);
                }
            },
            resetAnimation: function(){
                $overlay.on("transitionend", methods.onOverlayTransitionsEnd);
            },
            addAnimation: function(){
                $overlay.off("transitionend", methods.onOverlayTransitionsEnd);
                elementorAnimation.animateElements(elementsWithAnimation);
            }
        };
        $widget.refresh = function () {
            settings = elementorSettings.getSettings();
            methods.unBindEvents();
            methods.bindEvents();
            methods.handleResize();
        };
        $widget.delete = function () {
            methods.unBindEvents();
            $widget.removeData("productSlider");
        };
        methods.init();
    };

    $.fn.productSlider = function () {
        return this.each(function () {
            if ($(this).data("productSlider") !== undefined) {
                $(this).removeData("productSlider")
            }
            new $.productSlider(this);
        });
    };
})(jQuery);
