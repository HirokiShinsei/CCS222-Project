// Logout button
const logoutBtn = document.querySelector('.tab-option[name="logout"]');

logoutBtn.addEventListener('click', logOut);
logoutBtn.addEventListener('keydown', e => {
    if (e.key === 'Enter') logOut();
});

// Profile button
const profileBtn = document.querySelector('.tab-option[name="profile"]');

profileBtn.addEventListener('click', () => {
    console.log(profileBtn.getAttribute('data-name'));
    if (profileBtn.getAttribute('data-name') == '/CCS222-Project/profile.php' || profileBtn.getAttribute('data-name') == '/CCS222-Project/profile-visit.php') window.location.href = 'home.php';
    else window.location.href = 'profile.php';
});

profileBtn.addEventListener('keydown', e => {
    if (e.key === 'Enter') {
        if (profileBtn.getAttribute('data-name') == '/CCS222-Project/profile.php' || profileBtn.getAttribute('data-name') == '/CCS222-Project/profile-visit.php') window.location.href = 'home.php';
        else window.location.href = 'profile.php';
    }
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

// search bar (input)
const searchInput = document.querySelector('#searchbar > input');

// search box (appears on user type)
const searchBox = document.querySelector('#search-box');

// Search suggestions div
const searchSuggestions = document.querySelector('#search-suggestions');

// First search box option (user typed)
const user_option = document.querySelector('#user-typed');

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
                    suggestionsHTML = suggestions.map(suggestion => `<section>${suggestion}</section>`).join('');
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

// When searchbar loses focus
searchInput.addEventListener('blur', () => {
    searchbar.classList.remove('start-input');
    
    // remove all active class tags
    searchBox.querySelectorAll('section').forEach(option => {
        option.classList.remove('active');
    });
})

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


