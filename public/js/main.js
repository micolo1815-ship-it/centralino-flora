$('.oleez-header .dropdown').hover(function() {
  $(this).find('.dropdown-menu').first().stop(true, true).delay(250).slideDown();
}, function() {
  $(this).find('.dropdown-menu').first().stop(true, true).delay(100).slideUp();
});

$('[data-toggle="offCanvasMenu"]').click(function() {
  $('#offCanvasMenu').addClass('open');
});

$('[data-dismiss="offCanvasMenu"]').click(function() {
  $(this).parent('#offCanvasMenu').removeClass('open');
});

$('[data-toggle="searchModal"]').click(function() {
  $('#searchModal').addClass('open');
});

$('[data-dismiss="searchModal"]').click(function() {
  $(this).parent('#searchModal').removeClass('open');
});



$(document).ready(function () {
  let mainNavbar = $('.main-navbar');
  let scrollNavbar = $('.scroll-navbar');

  $(window).on('scroll', function () {
    let mainBottom = mainNavbar.offset().top + mainNavbar.outerHeight();
    let scrollTop = $(window).scrollTop();

    if (scrollTop > mainBottom) {
      scrollNavbar.removeClass('d-none').addClass('d-flex');
    } else {
      scrollNavbar.removeClass('d-flex').addClass('d-none');
    }
  });
});

Fancybox.bind('[data-fancybox="widget-gallery"]', {
});


