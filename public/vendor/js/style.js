(function ($) {



}(jQuery));

function successToast() {
    $('#successToast').show();
    setTimeout(function () {
        $('#successToast').hide();
    }, 5000);
}