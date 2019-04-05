/**
 * @file
 */

(function ($) {

Drupal.behaviors.simple = {
  attach: function (context) {

    function windowSize() {
        var window_width = $(window).width();
        var window_height = $(window).height();
         if (document.getElementsByClassName('.view-news-feeds')) {
            $(".view-news-feeds .views-row").each(function () {
                var listmaxHeight = titlemaxHeight = 0;
                $(this).find(".views-col").each(function () {
                    var news_title_height = $(this).find('.news_listing .news_description_box .news_title');
                    var description_height = $(this).find('.news_listing .news_description_box .description_texts');
                    if (listmaxHeight < news_title_height.outerHeight()) {
                        listmaxHeight = news_title_height.outerHeight();
                    }
                    if (titlemaxHeight < description_height.outerHeight()) {
                        titlemaxHeight = description_height.outerHeight();
                    }
                });
                $(this).find('.news_listing .news_description_box .news_title').height(listmaxHeight);
                $(this).find('.news_listing .news_description_box .description_texts').height(titlemaxHeight);
            });
        }

    }

    windowSize();
    $(window).resize(function () {
        windowSize();
    });

  }
};
})(jQuery);
