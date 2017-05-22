(function() {
	var view_width = document.getElementsByTagName('html')[0].getBoundingClientRect().width;
	var _html = document.getElementsByTagName('html')[0];
	_html.style.fontSize = view_width / 16 + 'px';
})(jQuery);