(function($){
    "use strict";
    $(".inputtags").tagsinput('items');
    $(document).ready(function() {
        $('#example1').DataTable();
    });
    $('.icp_demo').iconpicker();

    $('#datepicker').datepicker({
        dateFormat: 'yyyy-mm-dd',
        language: {
            today: 'Today',
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        }
    });
    $('#timepicker').datepicker({
        language: 'en',
        timepicker: true,
        onlyTimepicker: true,
        timeFormat: 'hh:ii',
        dateFormat: null
    });

    tinymce.init({
        selector: '.editor',
        height : '300'
    });

    $(".video-button").magnificPopup({
        type: "iframe",
        iframe: {
            markup: '<div class="mfp-iframe-scaler">' +
                    '<div class="mfp-close"></div>' +
                    '<iframe class="mfp-iframe" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>' +
                    '</div>',
            patterns: {
                youtube: {
                    index: 'youtube.com',
                    id: 'v=',
                    src: 'https://www.youtube.com/embed/%id%?autoplay=1'
                },
                vimeo: {
                    index: 'vimeo.com',
                    id: '/',
                    src: 'https://player.vimeo.com/video/%id%?autoplay=1'
                }
            },
            srcAction: 'iframe_src',
        },
        mainClass: 'mfp-large-iframe', // Add a custom class for larger popup
        closeOnBgClick: true, // Close popup when clicking outside
        closeOnContentClick: false, // Do not close when clicking inside the content
        gallery: {
            enabled: true,
        },
        callbacks: {
            elementParse: function(item) {
                // Check if the link is an MP4 file
                if (item.el[0].href.match(/\.mp4$/)) {
                    item.type = 'inline'; // Change type to inline for MP4 files
                    item.src = '<div class="video-popup"><video controls autoplay><source src="' + item.el[0].href + '" type="video/mp4"></video></div>';
                }
            },
            open: function() {
                // Autoplay MP4 videos when the popup opens
                var video = this.content.find('video')[0];
                if (video) {
                    video.play();
                }
            },
            close: function() {
                // Pause MP4 videos when the popup closes
                var video = this.content.find('video')[0];
                if (video) {
                    video.pause();
                }
            }
        }
    });

    $(".magnific").magnificPopup({
        type: "image",
        gallery: {
            enabled: true,
        },
    });

    $('form').attr('autocomplete', 'off');
    $('input').attr('autocomplete', 'off');

})(jQuery);