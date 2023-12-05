jQuery(function ($) {
    $.productListGallery = function (el) {
        let $widget = $(el),
            methods,
            elementorSettings,
            elementorAnimation,
            elementsWithAnimation,
            settings,
            product_gallery,
            overlayTemplateExist = false,
            $gallery = $widget.find(".dt-product-gallery"),
            $overlay,
            visibility;
        // Store a reference to the object
        $.data(el, "productListGallery", $widget);
        // Private methods
        methods = {
            init: function () {
                $widget.layzrInitialisation();

                $overlay = $widget.find(".the7-overlay-content");
                $triggerZoom = $widget.find(".woocommerce-product-gallery__trigger");

                if ($overlay.length) {
                    overlayTemplateExist = true;
                    elementorAnimation = new The7ElementorAnimation();
                    elementsWithAnimation = elementorAnimation.findAnimationsInNode($overlay);
                    if ($widget.find("a.post-thumbnail-rollover").length && !elementorFrontend.isEditMode()) {
                        $overlay.css("cursor", "pointer");
                        $overlay.on("click", function (e) {
                            let $this = $(this),
                                $thisParent = $this.parent();
                            let $imgWrap = $thisParent.find("a.post-thumbnail-rollover");
                            if ((e.target.tagName.toLowerCase() !== "a" && !$(e.target).parents("a").length) && e.target.tagName.toLowerCase() !== "button") {
                                //for lighbox
                                $imgWrap.trigger("click");
                            }
                        })
                    }
                }

                elementorSettings = new The7ElementorSettings($widget);
                $widget.refresh();

                if (overlayTemplateExist) {
                    visibility = The7ElementorSettings.getResponsiveControlValue(settings, "hover_visibility");
                    switch (visibility) {
                        case "hover-hide":
                            elementorAnimation.animateElements(elementsWithAnimation);
                            break;
                    }
                }
                //Zoom click
                if ($triggerZoom.length) {
                    $widget.on("click", ".zoom-flash", function (e) {
                        e.preventDefault();
                        $triggerZoom.trigger("click");
                    });
                }
                $widget.on("click", ".woocommerce-product-gallery__trigger", function (e) {
                    if (typeof product_gallery !== "undefined") {
                        product_gallery.openPhotoswipe(e);
                    }
                });

                //allow to initialize single-product.js
                if (typeof wc_single_product_params === "undefined") {
                    var wc_single_product_params = {};
                }

                if (typeof dtGlobals != "undefined") {
                    dtGlobals.addOnloadEvent(function () {
                        if (typeof $.fn.wc_product_gallery === "function") {
                            var wc_product_params = {

                                "zoom_enabled": false,
                                "photoswipe_enabled": true,
                                "photoswipe_options": {
                                    "shareEl": false,
                                    "closeOnScroll": false,
                                    "history": false,
                                    "hideAnimationDuration": 0,
                                    "showAnimationDuration": 0
                                }
                            };
                            product_gallery = $gallery.wc_product_gallery(wc_product_params).data("product_gallery");
                        }
                    });
                }

                // Support image transitions.
                $widget.one("mouseenter", function () {
                    $widget.find(".post-thumbnail-rollover img").addClass("run-img-transitions");
                });
            },
            handleResize: function () {
                if (overlayTemplateExist) {
                    visibility = The7ElementorSettings.getResponsiveControlValue(settings, "hover_visibility");
                    switch (visibility) {
                        case "always":
                            elementorAnimation.animateElements(elementsWithAnimation);
                            break;
                        case "disabled":
                            elementorAnimation.resetElements(elementsWithAnimation);
                            break;
                    }
                }
            },
            bindEvents: function () {
                elementorFrontend.elements.$window.on("the7-resize-width-debounce", methods.handleResize);
                if (overlayTemplateExist) {
                    $widget.on({mouseenter: methods.mouseenter, mouseleave: methods.mouseleave});
                }
            },
            unBindEvents: function () {
                elementorFrontend.elements.$window.off("the7-resize-width-debounce", methods.handleResize);
                if (overlayTemplateExist) {
                    $widget.off({mouseenter: methods.mouseenter, mouseleave: methods.mouseleave});
                }
            },
            mouseenter: function () {
                switch (visibility) {
                    case "hover":
                        methods.addAnimation();
                        break;
                    case "hover-hide":
                        methods.resetAnimation();
                        break;
                }
            },
            mouseleave: function () {
                switch (visibility) {
                    case "hover":
                        methods.resetAnimation();
                        break;
                    case "hover-hide":
                        methods.addAnimation();
                        break;
                }
            },
            onOverlayTransitionsEnd: function (event) {
                if (event.originalEvent.propertyName === "opacity") {
                    elementorAnimation.resetElements(elementsWithAnimation);
                }
            },
            resetAnimation: function () {
                $overlay.on("transitionend", methods.onOverlayTransitionsEnd);
            },
            addAnimation: function () {
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
            $widget.removeData("productListGallery");
        };
        methods.init();
    };
    $.fn.productListGallery = function () {
        return this.each(function () {
            var widgetData = $(this).data("productListGallery");
            if (widgetData !== undefined) {
                widgetData.delete();
            }
            new $.productListGallery(this);
        });
    };
});
(function ($) {
    // Make sure you run this code under Elementor.
    $(window).on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction("frontend/element_ready/the7-woocommerce-product-images-list.default", function ($widget, $) {
            $(document).ready(function () {
                if (elementorFrontend.isEditMode()) {
                    The7ElementorAnimation.patchElementsAnimation($widget);
                }
                $widget.productListGallery();
            })
        });
    });
})(jQuery);
