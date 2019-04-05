/**
 * @file
 * Global jQuery, Drupal, drupalSettings, window
 * jslint white:true, multivar, this, browser:true.
 */

(function ($, Drupal, drupalSettings, window) {
  "use strict";
  /* SCRIPT START */

  $(window).on("load", function() {
   $(".se-pre-con").addClass("loaded");
  });

  Drupal.behaviors.encade_global = {
    attach: function (context, settings) {
    
    $(function() {
	  var cururl = window.location.pathname;
      var curpage = cururl.substr(cururl.lastIndexOf('/') + 1);
	
	  $( ".currency-detail-chart-header .nav a" ).each(function() {
	    var ThisUrl = $(this).attr('href');
	    var ThisUrlEnd = ThisUrl.split('/').filter(Boolean).pop();
	    if(ThisUrlEnd == curpage)
	    $(this).addClass('active');
	  });
	
    });
  
    $('[data-toggle="tooltip"]').tooltip();
    }
   };

   /* SCRIPT END */
} (jQuery, Drupal, drupalSettings, window));
