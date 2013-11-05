(function ($) {
    $.extend({
        dialog:function (args) {
            var contentId = $.trim(args.argvElement) + "-content";
            var subjectId = $.trim(args.argvElement) + "-subject";
            var inTitleId = $.trim(args.argvElement) + "-subject-title";
            var inCloseId = $.trim(args.argvElement) + "-subject-close";
            var messageId = $.trim(args.argvElement) + "-message";
            var buttonsId = $.trim(args.argvElement) + "-buttons";
            var button1Id = $.trim(args.argvElement) + "-button1";
            var button2Id = $.trim(args.argvElement) + "-button2";
            var button2Id = $.trim(args.argvElement) + "-button2";
            
            args.argvSubject = !args.argvSubject ? "" : args.argvSubject;

            var topWindowDocument = window.top.document;

            if ($("#" + contentId, topWindowDocument))
                $("#" + contentId, topWindowDocument).remove();
            
            var dialogHTML = jQuery('<div id="' + contentId + '" class="dialog-content">' +
                '<div id="' + subjectId + '" class="dialog-subject"><span id="' + inTitleId +
                '" class="title">' + args.argvSubject + '</span>' + '<span id="inCloseId" ' +
                'class="close"><a href="javascript::;" onclick="' + 'javascript:$(this).pa' +
                'rent().parent().parent().remove(); return false;">×</a></span></div>' +
                '<div id="' + messageId + '" class="dialog-message">' + args.argvMessage +
                '</div><div id="' + buttonsId + '" class="dialog-buttons"><a id="' + button1Id +
                '" class="dialog-button1">确&nbsp;定</a><a id="' + button2Id +
                '" class="dialog-button2">关&nbsp;闭</a></div></div>');

            $(topWindowDocument.body).append(dialogHTML);

            $("#" + contentId, topWindowDocument).css({
                "height":args.argvHeight + 120,
                "width":args.argvWidth,
                "marginTop":function () {
                    return $(window.top).scrollTop() +
                        ($(window.top).height() - $("#" + contentId, topWindowDocument).outerHeight()) / 2;
                },
                "marginLeft":function () {
                    return ($(window.top).width() - $("#" + contentId, topWindowDocument).outerWidth()) / 2;
                }
            });

            $("#" + messageId, topWindowDocument).css({
                "height":args.argvHeight
            });

            $("#" + button1Id, topWindowDocument).bind("click", function () {
                args.argvCallback();
                $(this).parent().parent().remove();
            });

            $("#" + button2Id, topWindowDocument).bind("click", function () {
                $(this).parent().parent().remove();
            });

            $(topWindowDocument).bind("scroll", function () {
                $("#" + contentId, topWindowDocument).css({
                    "marginTop":function () {
                        return $(window.top).scrollTop() +
                            ($(window.top).height() - $("#" + contentId, topWindowDocument).outerHeight()) / 2;
                    }
                });
            });
        }
    });
})(jQuery);