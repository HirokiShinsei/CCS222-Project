// Redirect to home page when the title is clicked
if (window.innerWidth > 480) {
    document.querySelector('header > h2').onclick = function() {
        window.location.href = 'home.php';
    }
}


// Search box

// main search bar
const searchbar = document.querySelector('#searchbar')

// search bar (input)
const searchInput = searchbar.querySelector('input');

// search box (appears on user type)
const searchBox = searchbar.querySelector('#search-box');

// Search suggestions div
const searchSuggestions = searchBox.querySelector('#search-suggestions');

// First search box option (user typed)
const user_option = searchBox.querySelector('#user-typed');

// Every time user types on search bar
searchInput.addEventListener('input', () => {

    // remove all active class tags
    searchBox.querySelectorAll('section').forEach((option, index) => {
        if (index > 0) option.classList.remove('active');
    });
    user_option.classList.add('active');

    if (!searchbar.classList.contains('start-input')) {
        // show the search box
        searchbar.classList.add('start-input');
        user_option.classList.add('active');
    }

    const searchValue = searchInput.value.trim();

    if (searchValue.length > 0) {
        user_option.innerHTML = searchValue;

        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'search-suggestions.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        xhr.onload = () => {
            if (xhr.status === 200) {
                const suggestions = JSON.parse(xhr.responseText);
                let suggestionsHTML = '';
                
                if (suggestions.length > 0) {
                    suggestionsHTML = suggestions.map(suggestion => `<section ontouchstart="window.location.href='search-results.php?search=${suggestion}'" onclick="window.location.href='search-results.php?search=${suggestion}'">${suggestion}</section>`).join('');
                    document.querySelector('#search-suggest-label').innerHTML = 'POSTS RELATED TO YOUR SEARCH';
                    document.querySelector('#search-suggest-label').style.marginBlock = "1em";
                } else {
                    document.querySelector('#search-suggest-label').innerHTML = '';
                    document.querySelector('#search-suggest-label').style.marginBlock = "0";

                    suggestionsHTML = '';
                }
                searchSuggestions.innerHTML = suggestionsHTML;
            }
        };
        xhr.send('searchTerm=' + encodeURIComponent(searchValue));
        
    } else {
        searchbar.classList.remove('start-input');
        searchSuggestions.innerHTML = '';
    }
});

// When down button is pressed and there is content on the searchbar
searchInput.addEventListener('keydown', e => {

    // if there is content on the searchbar, allow arrow navigation
    if (searchInput.value.length > 0 && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
        // show the searchbar
        if (!searchbar.classList.contains('start-input')) {
            searchbar.classList.add('start-input');
            user_option.classList.add('active');

        } else {
            e.preventDefault();
            const searchSections = searchBox.querySelectorAll("section");
            let sectionIndex = Array.from(searchSections).findIndex(section => section.classList.contains('active'));

            // If down key is pressed
            if (e.key === 'ArrowDown') {
                sectionIndex = sectionIndex === searchSections.length - 1 ? 0 : sectionIndex + 1;
            } 
            // If up key is pressed
            else if (e.key === 'ArrowUp') {
                sectionIndex = sectionIndex === 0 ? searchSections.length - 1 : sectionIndex - 1;
            }

            searchSections.forEach((section, index) => {

                if (index === sectionIndex)
                    section.classList.add('active');
                else 
                    section.classList.remove('active');
            });

            // Modify searchbar text value to the active section's text
            searchInput.value = searchSections[sectionIndex].innerHTML.trim();

        }
    }
});

// Add left click to open profile, right click to log out
const user_btn = document.querySelector('#user-btn');

if (user_btn != null) {
    if (window.innerWidth > 480) {
        user_btn.addEventListener('click', openLogOutMenu);
    } else {
        user_btn.addEventListener('touchstart', expandSidebar);
    }
}

function expandSidebar(e) {
    document.querySelector('#backdrop').classList.add('active');
    document.querySelector('#mobile-sidebar').classList.add('active');
    
    function closeSidebar(e) {
        if (!document.querySelector('#mobile-sidebar').contains(e.target)) {
            document.querySelector('#mobile-sidebar').classList.remove('active');
            document.querySelector('#backdrop').classList.remove('active');
            user_btn.addEventListener('touchstart', expandSidebar);
        }
    }
    e.stopPropagation();

    document.addEventListener('touchstart', closeSidebar);
    user_btn.removeEventListener('touchstart', expandSidebar);
    document.querySelector('#log-out-option').addEventListener('touchstart', logOut);
}
// Log out
function openLogOutMenu(e) {
    e.preventDefault();

    document.querySelector('#username-options').style.maxHeight = document.querySelectorAll('#username-options button').length * 2.5 + 1 + 'rem';

    e.stopPropagation();
    document.addEventListener('click', () => {
        if (!document.querySelector('#username-options').contains(e.target)) {
            document.querySelector('#username-options').style.maxHeight = '0';
            user_btn.addEventListener('click', openLogOutMenu);
            user_btn.blur();
        }
    });
    user_btn.removeEventListener('click', openLogOutMenu);
    document.querySelector('#logout-btn').addEventListener('click', logOut);
}

function logOut(e) {
    e.preventDefault();

    let xhr = new XMLHttpRequest();
    
    xhr.open('GET', 'logout.php');
    xhr.send();

    window.location.href = 'login.php';
}


