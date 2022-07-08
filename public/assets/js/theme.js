jQuery(function($){
	$(document).ready(function(){
		$('.tour-carousel').slick({
			lazyLoad: 'ondemand',
			slidesToShow: $(window).width() > 768 ? 2 : 1,
			slidesToScroll: 1,
			autoplay: true,
			autoplaySpeed: 3000,
			infinite: true,
			speed: 300,
			cssEase: 'linear',
			dots: true,
			centerMode: true,
		});


		if ($(window).scrollTop() >= 1) {
			$('.page-header').addClass('header-sticky');
		} else {
			$('.page-header').removeClass('header-sticky');
		}
	});

	$(window).scroll(function(){
		if ($(window).scrollTop() >= 1) {
			$('.page-header').addClass('header-sticky');
		} else {
			$('.page-header').removeClass('header-sticky');
		}
	});

	$('.nav-open').off('click').on('click', function(){
		if($('body').hasClass('nav-opened'))
			$('body').removeClass('nav-opened');
		else
			$('body').addClass('nav-opened');
	});

	$('.submenu-opener').off('click').on('click', function(){
		if(!$(this).hasClass('opened')) {
			$(this).next('.menu-popup').slideDown();
			$(this).addClass('opened');
		} else {
			$(this).next('.menu-popup').slideUp();
			$(this).removeClass('opened');
		}
	});

	$('.with-sidebar .sidebar .archive .archive__list .item .title').off('click').on('click', function(){
		if(!$(this).hasClass('expanded')) {
			$(this).addClass('expanded');
		} else {
			$(this).removeClass('expanded');
		}
	});

	$('.ym-selector').on('change', function() {
		console.log( this.value );
		$('.y-value').val(this.value.substr(-8, 4));
		$('.m-value').val(this.value.substr(-3, 2));
	});

	// 20220613
	$('.reserveItem').each(function(){
		$(this).find(".reserveAdd").click(function(){
			$(this).before($(this).prev().clone());
			return false;
		}); 
	});

	$(document).on('click','.reserveDelete',function(){
		$(this).parents('.reserveList').remove();
	    return false;
	});

	$('.dateList .dateItems').each(function(){
		var $this = $(this),
				$prev = $this.find('.prevM').not('.disabled'),
				$next = $this.find('.nextM').not('.disabled');
		$prev.click(function(){
			$this.removeClass('is_active');
			$this.prev().addClass('is_active')
		});
		$next.click(function(){
			$this.removeClass('is_active');
			$this.next().addClass('is_active')
		});
	});
	
	$('.dtFor').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  arrows: false,
	  fade: true,
	  asNavFor: '.dtNav'
	});
	$('.dtNav').slick({
	  slidesToShow: 1,
	  slidesToScroll: 1,
	  asNavFor: '.dtFor',
	  dots: false,
	  arrows: true,
	  // centerMode: true,
      variableWidth: true,
	  focusOnSelect: true
	});



});

	
$(window).on('load',function(){
    var scrolltop = $('header').height();
  $('.scroll a,a.scroll').click(function(){
    var target = $(this).attr('href'),
        targetY = $(target).offset().top - scrolltop;
    $('html,body').animate({scrollTop: targetY}, 'slow','swing');
    return false;
  });
});