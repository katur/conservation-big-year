$(document).ready(function(){
})

$(window).load(function(){
	loadMasonry();
})

loadMasonry = function(){
	var masonry_instance = $(".photo-checklist-family").masonry({
		itemSelector: '.photo-and-caption',
		gutter: 20
	});
}
