const optionBtn = document.querySelector('#dropdown');
const optionBox = document.querySelector('.dropdown-box');
const optionItem = document.querySelectorAll('.dropdown-item');
const userBtn = document.querySelector('.user');
const userBox = document.querySelector('.user-dropdown');

optionBtn.onclick = function(e) {
    optionBox.classList.toggle('dropdown-box-active');
}

userBtn.addEventListener('click', e => {
    userBox.classList.toggle('user-dropdown-active');
});

window.addEventListener('click', e => {
    if(!optionBtn.contains(e.target)) {
        optionBox.classList.remove('dropdown-box-active');
    }
    if (!userBtn.contains(e.target)) {
        userBox.classList.remove('user-dropdown-active');
    }

    if (logout.contains(e.target)) {
        let xhr = new XMLHttpRequest();

        xhr.open('GET', 'logout.php');
        xhr.send();

        window.location.href = "login.php";
    }
});