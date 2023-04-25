const logoutBtn = document.querySelector('.tab-option[name="logout"]');

logoutBtn.addEventListener('click', () => {
    let xhr = new XMLHttpRequest();
    
    xhr.open('GET', 'logout.php');
    xhr.send();

    window.location.href = 'login.php';
})