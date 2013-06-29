$(document).ready(function(){
	loadMasonry();
})

$(window).load(function(){
})

loadMasonry = function(){
	var masonry_instance = $(".photo-checklist-family").masonry({
		itemSelector: '.photo-and-caption',
		gutter: 20
	});
}
