/* Smooth Scrolling
/*    Make links to anchors within page scroll smoothly
/*    See http://css-tricks.com/snippets/jquery/smooth-scrolling
*/
(function($) {
  /* ADDED: make targets focusable */
  $('target[id]').attr('tabindex', '-1');

  $('a[href*=#]:not([href=#])').click(function() {
    var $linkElem = $(this);
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000, function() {
          /* ADDED: focus the target */
          target.focus();
          /* end ADDED */
          /* ADDED: update the URL */
          window.location.hash = $linkElem.attr('href').substring(1);
         // window.location.hash = $(this).attr('href').substring(1, $(this).attr('href').length);
          /* end ADDED */
        });
        return false;
      }
    }
  });
})(jQuery);
