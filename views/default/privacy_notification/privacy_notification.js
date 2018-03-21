define(function (require) {
    var elgg = require('elgg');
    var $ = require('jquery');
	
    $(function(){
//        var terms_were_scrolled = false;

        $('#privacy_terms').scroll(function () {
            //if ($(this).scrollTop() == $(this)[0].scrollHeight - $(this).height()) {
            if ($(this).scrollTop() + $(this).innerHeight() +2 >= $(this)[0].scrollHeight / 1.5) {
//                terms_were_scrolled = true;
                $('#privacy_terms_btn').attr("disabled", false);
            }
        }); 
    });
});
