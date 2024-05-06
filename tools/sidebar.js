document.addEventListener("DOMContentLoaded", function () {
    const showNavbar = (toggleId, navId, bodyId, headerId) => {
        const toggle = document.getElementById(toggleId),
            nav = document.getElementById(navId),
            bodypd = document.getElementById(bodyId),
            headerpd = document.getElementById(headerId);
        // Validate that all variables exist
        if (toggle && nav && bodypd && headerpd) {
            toggle.addEventListener('click', () => {
                // show navbar
                nav.classList.toggle('show');
                // change icon
                toggle.classList.toggle('bx-x');
                // add padding to body
                bodypd.classList.toggle('body-pd');
                // add padding to header
                headerpd.classList.toggle('body-pd');
            });
        }
    };
    showNavbar('header-toggle', 'nav-bar', 'body-pd', 'header');
    /*===== LINK ACTIVE =====*/
    const linkColor = document.querySelectorAll('.nav_link');

    function colorLink() {
        if (linkColor) {
            linkColor.forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        }
		
		
    }
    linkColor.forEach(l => l.addEventListener('click', colorLink));
    // Your code to run since DOM is loaded and ready
	
});


window.onload = function () {
    if(innerWidth>767){
			document.getElementById('header-toggle').click();
		}
}
var ready = (callback) => {
    if (document.readyState != "loading") callback();
    else document.addEventListener("DOMContentLoaded", callback);
}
ready(() => {
    document.querySelector(".bg").style.height = window.innerHeight - 0  + "px";
});



function myFunction(){
  document.getElementById("myDropdown").classList.toggle("show");
}
//myFunction();
// Close the dropdown menu if the user clicks outside of it
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    var dropdowns = document.getElementsByClassName("dropdown-content");
    var i;
    for (i = 0; i < dropdowns.length; i++) {
      var openDropdown = dropdowns[i];
      if (openDropdown.classList.contains('show')) {
        openDropdown.classList.remove('show');
      }
    }
  }
}

function togglepassvisible() {
    var x = document.getElementsByClassName("togglepass");
    for (var i = 0; i < x.length; i++) {
        if (x[i].type === "password") {
            x[i].type = "text";
        } else {
            x[i].type = "password";
        }
    }

}

//var timestamp = '<?=time();?';
//function updateTime(){
//	//const md = new Date("25/03/2020");
//	$('#time').html(Date(timestamp));
//	timestamp++;
//}
//$(function(){
//	setInterval(updateTime, 1000);
//});