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
                case 'Create a post':
                    window.location.href='create-a-post.php';
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
const searchBox = document.querySelector('#search-box');
const searchSuggestions = document.querySelector('.search-suggestions');

searchInput.addEventListener('input', () => {
    searchbar.classList.add('start-input');
    document.querySelectorAll('#search-box > section')[0].classList.add('active');
    const searchBar = searchInput.value.trim();

    if (searchBar.length > 0) {
        const searchSections = document.querySelectorAll('#search-box > section');
        searchSections[0].innerHTML = searchInput.value.trim();
        for (let i = 1; i < searchSections.length; i++) {
            searchSections[i].classList.remove('active'); 
        }

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'search-suggestions.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        xhr.onload = () => {
            if (xhr.status === 200) {
                const suggestions = JSON.parse(xhr.responseText);
                let suggestionsHTML = '';
                
                if (suggestions.length > 0) {
                    suggestionsHTML = suggestions.map(suggestion => `<section>${suggestion}</section>`).join('');
                } else {
                    suggestionsHTML = '';
                }
                searchSuggestions.innerHTML = suggestionsHTML;
            }
        };
        xhr.send('searchTerm=' + encodeURIComponent(searchBar));

    } else {
        searchbar.classList.remove('start-input');
        searchSuggestions.innerHTML = '';
    }
});

searchInput.addEventListener('blur', () => {
    searchbar.classList.remove('start-input');
    document.querySelectorAll('#search-box > section').forEach(option => {
        option.classList.remove('active');
    })
})

// Search options
searchInput.addEventListener('keydown', e => {
    
    if (searchInput.value.trim().length > 0) {
        if (!searchbar.classList.contains('start-input') && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
            searchbar.classList.add('start-input');
            document.querySelectorAll('#search-box > section')[0].innerHTML = searchInput.value;
        }
        
        if (e.key === 'ArrowDown' && searchSuggestions.innerHTML != '') {
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
        
        } else if (e.key === 'ArrowUp' && searchSuggestions.innerHTML != '') {
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

