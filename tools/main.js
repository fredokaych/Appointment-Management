var ready = (callback) => {
    if (document.readyState != "loading") callback();
    else document.addEventListener("DOMContentLoaded", callback);
}
ready(() => {
    document.querySelector(".header").style.height = window.innerHeight-56 + "px";
	//document.querySelector(".headerr").style.height = window.innerHeight-56 + "px";

});


$(document).ready(function () {
    $('#demo-modal').on('show.bs.modal', function (e) {
        var id = $(e.relatedTarget).data('id');

        document.getElementById('mylbl').innerHTML = id.charAt(0).toUpperCase() + id.slice(1) + ' Sign In';
        document.getElementById('hiddencontainer').value = id;
        if (id != 'patient') {
            document.getElementById('mycreate').innerHTML = '';
        }
        //document.getElementById('uname').focus();
        if (id == 'patient') {
            document.getElementById('mycreate').innerHTML = "Don't have an account? <a href='register.php'>Sign up now</a>";
        } else {
            document.getElementById('mycreate').innerHTML = "Don't have an account? <a href='#' data-toggle='modal' data-target='#contact-modal'>Contact Admin</a>";
        }

    })

});
