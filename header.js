// Logout button
const logoutBtn = document.querySelector('.tab-option[name="logout"]');

logoutBtn.addEventListener('click', logOut);
logoutBtn.addEventListener('keydown', e => {
    if (e.key === 'Enter') logOut();
});

// Drop down options
const dropdowns = document.querySelectorAll('header > div');
dropdowns.forEach(div => navigateThrough(div));

// Tab options 
const tabOptions = document.querySelectorAll('header > #tab-box > .tab-option:not([first-option])');
tabOptions.forEach(option => {
    option.addEventListener('keydown', e => {
        if (e.key === 'Enter') {
            const name = option.querySelector('p');
            
            switch (name.innerText) {
                case 'Home':
                    window.location.href='home.php';
                    break;
                case 'Communities':
                    window.location.href='communities.php';
                    break;
                case 'Trending':
                    window.location.href='trending.php';
                    break;
            }
        }
    });
});

// Remove focus from options dropdown if first option is selected
document.querySelector('header > #tab-box > .tab-option[first-option]').addEventListener('keydown', e => {
    if (e.key === 'Enter') document.querySelector('header > #tab-box > .tab-option[first-option]').blur();
});

// Search box
const searchInput = document.querySelector('#searchbar > input');

searchInput.addEventListener('input', () => {
    searchbar.classList.add('start-input');
});

searchInput.addEventListener('blur', () => {
    searchbar.classList.remove('start-input');
})

// Search options
const searchBox = document.querySelector('#search-box');

searchInput.addEventListener('keydown', e => {
    if (!searchbar.classList.contains('start-input')) {
        searchbar.classList.add('start-input');
    }

    if (e.key === 'ArrowDown') {
        e.preventDefault();
        const children = searchBox.querySelectorAll('section');
        
        let currentIndex = -1;
        for (let i = 0; i < children.length; i++) {
            if (children[i].classList.contains('active')) {
                currentIndex = i;
                break;
            }
        }
        const nextIndex = currentIndex === children.length - 1 ? 0 : currentIndex + 1;
    
        children[nextIndex].classList.add('active');
        searchInput.value = children[nextIndex].innerText;
        if (currentIndex >= 0) children[currentIndex].classList.remove('active');
    
    } else if (e.key === 'ArrowUp') {
        e.preventDefault();
        const children = searchBox.querySelectorAll('section');
        
        let currentIndex = 1;
        for (let i = 0; i < children.length; i++) {
            if (children[i].classList.contains('active')) {
                currentIndex = i;
                break;
            }
        }
        const nextIndex = currentIndex === 0 ? children.length - 1 : currentIndex - 1;
    
        children[nextIndex].classList.add('active')
        searchInput.value = children[nextIndex].innerText;
        children[currentIndex].classList.remove('active');
    }
});

// -----------------------------------------------
// FUNCTIONS
// -----------------------------------------------
function navigateThrough(div) {
    div.addEventListener('keydown', e => {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            const children = div.querySelectorAll('.tab-option');
            
            const currentIndex = Array.from(children).indexOf(document.activeElement);
            const nextIndex = currentIndex === children.length - 1 ? 0 : currentIndex + 1;
            children[nextIndex].focus();
            
        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            const children = div.querySelectorAll('.tab-option');
            const currentIndex = Array.from(children).indexOf(document.activeElement);
            const prevIndex = currentIndex === 0 ? children.length - 1 : currentIndex - 1;
            children[prevIndex].focus();
        }
    });
}

function logOut() {
    let xhr = new XMLHttpRequest();
    
    xhr.open('GET', 'logout.php');
    xhr.send();

    window.location.href = 'login.php';
}

