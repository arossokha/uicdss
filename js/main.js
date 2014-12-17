$(document).ready(function() {
    $('.faqQuestion').live('click', function() {
        $(this).next().slideToggle();
    });
});