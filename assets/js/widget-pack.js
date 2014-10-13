(function(j){
	j('.bs-widget-pack .flexslider').flexslider({
		animation: "slide",
		animationLoop: true,
		itemWidth: 125,
		itemMargin: 0,
		controlNav: false,
		maxItems: 6,
		prevText: "",
		nextText: ""
	});

	// j('.show-categories').on('click', function(){
	// 	if ( j('.category-nav').is(':visible') ){
	// 		j('.show-categories').find('span').text('Show Categories');
	// 		j('.show-categories').find('i').attr('class', 'icon-plus');
	// 		j('.category-nav').slideUp();
	// 	} else {
	// 		j('.show-categories').find('span').text('Hide Categories');
	// 		j('.show-categories').find('i').attr('class', 'icon-minus');
	// 		j('.category-nav').slideDown(); 
	// 	}
	// });

	/*
	*		Category Cards
	**/
	j('.showmore').on('click', function(e){
		e.preventDefault();
		e.stopPropagation();

		j(this).toggleClass('expanded');
		if (j(this).hasClass('expanded')){
			j(this).find('span').text('Hide');
			j('.more-categories').slideDown();
		} else {
			j(this).find('span').text('Show More');
			j('.more-categories').slideUp();
		}
	});

	j('.mobile-categories-trigger').on('touchstart', function(e){
		e.preventDefault();
		e.stopPropagation();

		j(this).toggleClass('expanded');
		if (j(this).hasClass('expanded')){
			j('.mobile-categories-menu ul').slideDown();
		} else {
			j('.mobile-categories-menu ul').slideUp();
		}
	});
}(jQuery));

