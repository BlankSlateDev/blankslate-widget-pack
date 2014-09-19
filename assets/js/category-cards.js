(function(){
	jQuery('.showmore').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();

		jQuery(this).toggleClass('expanded');
		if (jQuery(this).hasClass('expanded')){
			jQuery(this).find('span').text('Hide');
			jQuery('.more-categories').slideDown();
		} else {
			jQuery(this).find('span').text('Show More');
			jQuery('.more-categories').slideUp();
		}
	});

	jQuery('.mobile-categories-trigger').on('touchstart, click', function(e){
		e.preventDefault();
		e.stopPropagation();

		jQuery(this).toggleClass('expanded');
		if (jQuery(this).hasClass('expanded')){
			jQuery('.mobile-categories-menu ul').slideDown();
		} else {
			jQuery('.mobile-categories-menu ul').slideUp();
		}
	});
}());