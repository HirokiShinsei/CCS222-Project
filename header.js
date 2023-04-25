const optionBtn = document.querySelector('#dropdown');
const optionBox = document.querySelector('#dropdown-box');
const optionItem = document.querySelectorAll('.dropdown-item');

optionBtn.onclick = function(e) {
    optionBox.classList.toggle('dropdown-box-active');
}

window.addEventListener('click', e => {
    if(!optionBtn.contains(e.target)) {
        optionBox.classList.remove('dropdown-box-active');
    }

    if (document.querySelector('#user').contains(e.target)) {
        console.log("Clicked");

        let xhr = new XMLHttpRequest();

        xhr.open('GET', 'logout.php');
        xhr.send();

        window.location.href = "login.php";
    }
});