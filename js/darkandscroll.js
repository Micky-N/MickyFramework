var mybutton = document.getElementById("myBtn");
window.onscroll = function() {
    scrollFunction()
};

function scrollFunction() {
    if (document.body.scrollTop > 1000 || document.documentElement.scrollTop > 1000) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

function topFunction() {
    window.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
    document.documentElement.scrollTo({
        top: 0,
        behavior: 'smooth'
    })
}

let isDarkMode = false


function darkMode(){
    isDarkMode = localStorage.getItem('isDarkMode') !== 'false';
    if(isDarkMode){
        $('html').addClass('dark')
    }else{
        $('html').removeClass('dark')
    }
}

function toggleDarkMode(){
    isDarkMode = !isDarkMode
    localStorage.setItem('isDarkMode', isDarkMode);
    if(isDarkMode){
        $('html').addClass('dark')
    }else{
        $('html').removeClass('dark')
    }
}

darkMode()

$('#mode').on('click', () => {
    toggleDarkMode()
})
