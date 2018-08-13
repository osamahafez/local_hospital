$(function () {

	//Confirmation Message on Delete buttton

	$('.confirm').click(function () {
		return confirm('Are You Sure ?');
	});
	

	// Hide placeholder when field is selected

	$('[placeholder]').focus(function () {

	$(this).attr('data-text', $(this).attr('placeholder'));

	$(this).attr('placeholder', '');

	}).blur(function () {

		$(this).attr('placeholder', $(this).attr('data-text'));
	});

});