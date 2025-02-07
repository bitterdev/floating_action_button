(function ($) {
    $.fn.isInViewport = function() {
        let elementTop = $(this).offset().top;
        let elementBottom = elementTop + $(this).outerHeight();
        let viewportTop = $(window).scrollTop();
        let viewportBottom = viewportTop + $(window).height();

        return elementBottom > viewportTop && elementTop < viewportBottom;
    };

    $.fn.floatingActionButton = function (options) {

        $(window).on("scroll resize", function () {
            let windowHeight = $(window).height();
            let documentHeight = $(document).height()
            let fadeInThreshold = 50;
            let fadeOutThreshold = documentHeight - windowHeight * 2;
            let scrollTop = $(window).scrollTop();

            $(".ccm-page .floating-action-button").each(function () {
                let $floatingActionButton = $(this);

                if ($floatingActionButton.hasClass("fade-in")) {
                    if (scrollTop > fadeInThreshold) {
                        $floatingActionButton.addClass("visible");
                    } else {
                        $floatingActionButton.removeClass("visible");
                        return;
                    }
                }

                if ($floatingActionButton.hasClass("fade-out")) {
                    let $footer = $(".ccm-page footer");

                    if ($footer.length) {
                        if ($footer.isInViewport()) {
                            $floatingActionButton.removeClass("visible");
                        } else {
                            $floatingActionButton.addClass("visible");
                        }
                    } else {
                        if (scrollTop > fadeOutThreshold) {
                            $floatingActionButton.removeClass("visible");
                        } else {
                            $floatingActionButton.addClass("visible");
                        }
                    }
                }
            });
        });

        return this.each(function () {
            let $actionButton = $("<a/>")
                .addClass("floating-action-button")
                .addClass("align-" + options.align)
                .attr("href", options.targetUrl)
                .css({
                    width: options.imageSize,
                    height: options.imageSize
                }).append(
                    $("<img/>")
                        .attr("src", options.imageUrl)
                        .attr("width", options.imageSize)
                        .attr("height", options.imageSize)
                )

            if (options.fadeIn) {
                $actionButton.addClass("fade-in");
            } else {
                $actionButton.addClass("visible");
            }

            if (options.fadeOut) {
                $actionButton.addClass("fade-out");
            }

            $(this).append($actionButton);

            $(window).trigger("scroll");
        });
    }
})(jQuery);