/*
******************************************************************************************
HEADER.JS

- Interaction event handler for header.php
- Handles search results, naviation, and logout system


Couple things to note:
- if window.innerWidth > 480px, then it is desktop display; otherwise, it is mobile
******************************************************************************************
*/


// ----------------------------------------------------------------
//  HEADER.H2 (DISCUSSDEN TITLE)
// ----------------------------------------------------------------

// Redirect to home page when the title is clicked (desktop-only)
if (window.innerWidth > 480) {
    document.querySelector('header > h2').onclick = function() {
        window.location.href = 'home';
    }
}

// ----------------------------------------------------------------
//  HEADER.SEARCHBAR (SEARCH BAR, SEARCH SUGGESTIONS)
// ----------------------------------------------------------------

// The visible search bar (the global container)
const searchbar = document.querySelector('#searchbar')

// The search bar input area (<input>)
const searchInput = searchbar.querySelector('input');

// Contains recent searches and search suggestions
const searchBox = searchbar.querySelector('#search-box');

// Contains the search suggestions (only)
const searchSuggestions = searchBox.querySelector('#search-suggestions');

// The search option that contains the user's input (the topmost section)
const user_option = searchBox.querySelector('#user-typed');

// Listener that checks if the searchbar has input
// Opens the searchBox and loads all search suggestions
searchInput.addEventListener('input', () => {

    // remove all active class tags
    searchBox.querySelectorAll('section').forEach(option => {
        option.classList.remove('active');
    });

    // Add the active tag to the topmost section
    user_option.classList.add('active');

    // if the search box is not opened, show it and add the class start-input to the searchbar
    if (!searchbar.classList.contains('start-input')) {
        searchbar.classList.add('start-input');
        user_option.classList.add('active');
    }

    // Contains the current search value, trimmed to remove whitespace
    const searchValue = searchInput.value.trim();

    // If the user has typed some value into the searchbar, and the search value is not empty,
    // Show the search suggestions
    if (searchValue.length > 0) {

        // Set the topmost section's value to the current search value
        user_option.innerHTML = searchValue;

        // Create a new XMLHttpRequest to get the search suggestions
        // This is done so that there is no need to refresh the page every time there is a suggestion found for the search
        const xhr = new XMLHttpRequest();

        // Open a post form to submit to search-suggesstions.php (NOTE: this is inaccessible directly from URL)
        xhr.open('POST', 'search-suggestions.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        // If the PHP script has returned a response, show the suggestions 
        xhr.onload = () => {
            // xhr.status === 200 means that the PHP script has executed successfully and has returned a value
            if (xhr.status === 200) {

                // Get all the suggestions and place it into an array using JSON.parse
                const suggestions = JSON.parse(xhr.responseText);
                
                // Temporary container for all the search suggestions
                let suggestionsHTML = '';
                
                // If there are search suggestions, map each one to suggestionsHTML as a section tag
                if (suggestions.length > 0) {
                    suggestionsHTML = suggestions.map(suggestion => `<section ontouchstart="window.location.href='search-results.php?search=${suggestion}'" onclick="window.location.href='search-results.php?search=${suggestion}'">${suggestion}</section>`).join('');

                    // Show the search suggestions label
                    document.querySelector('#search-suggest-label').innerHTML = 'POSTS RELATED TO YOUR SEARCH';
                    document.querySelector('#search-suggest-label').style.marginBlock = "1em";
                } else {
                    // If there are no search suggestions, remove the label
                    document.querySelector('#search-suggest-label').innerHTML = '';
                    document.querySelector('#search-suggest-label').style.marginBlock = "0";

                    // Set the suggestions container to null or empty
                    suggestionsHTML = '';
                }
                // Set the search suggestions container's innerHTML to the temporary variable's value
                searchSuggestions.innerHTML = suggestionsHTML;
            }
        };

        // Send the request and add the search value to the PHP file
        xhr.send('searchTerm=' + encodeURIComponent(searchValue));
        
    } else {

        // If the searchbar is empty, remove the start-input class from the searchbar and remove the content of search suggestions container
        searchbar.classList.remove('start-input');
        searchSuggestions.innerHTML = '';
    }
});

// When the searchbar is focused on and the up or down arrow keys are pressed
searchInput.addEventListener('keydown', e => {

    // if there is content on the searchbar, allow arrow navigation
    if (searchInput.value.length > 0 && (e.key === 'ArrowDown' || e.key === 'ArrowUp')) {
        
        // If the searchbox is not active, show it
        if (!searchbar.classList.contains('start-input')) {
            searchbar.classList.add('start-input');

            // Add the active tag to the topmost section
            user_option.classList.add('active');

        } else {
            // If the searchbox is active, then navigate through every section with the up and down arrow keys
            
            // Prevent the default action of up and down arrow keys
            e.preventDefault();

            // Get all the sections in the search box for navigation
            const searchSections = searchBox.querySelectorAll("section");

            // Get the current search index (the index of the section that contains the active class tag)
            let sectionIndex = Array.from(searchSections).findIndex(section => section.classList.contains('active'));

            // If down key is pressed, increase the index (loops to 0 if index exceeds the number of sections)
            if (e.key === 'ArrowDown') {
                sectionIndex = sectionIndex === searchSections.length - 1 ? 0 : sectionIndex + 1;
            } 
            // If up key is pressed, decrease the index (loops to searchSections.length if index gets below zero)
            else if (e.key === 'ArrowUp') {
                sectionIndex = sectionIndex === 0 ? searchSections.length - 1 : sectionIndex - 1;
            }

            // Add the active class tag to the selected section, and remove the active class tag from all other sections
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

// ----------------------------------------------------------------
//  HEADER.USER_BTN (USER BUTTON)
// ----------------------------------------------------------------

// Get the user button element
const user_btn = document.querySelector('#user-btn');

// Add touch event listener if client is mobile, click event otherwise
if (window.innerWidth > 480) {
    user_btn.addEventListener('click', openOptionMenu);
} else {
    user_btn.addEventListener('touchstart', expandSidebar);
}

// Expand the sidebar (mobile-only)
function expandSidebar(e) {

    // Show the sidebar and the blur background
    document.querySelector('#backdrop').classList.add('active');
    document.querySelector('#mobile-sidebar').classList.add('active');
    
    // Close the sidebar after the user taps outside the sidebar
    function closeSidebar(e) {
        if (!document.querySelector('#mobile-sidebar').contains(e.target)) {
            // Close the sidebar and hide the blur background
            document.querySelector('#mobile-sidebar').classList.remove('active');
            document.querySelector('#backdrop').classList.remove('active');

            // Add the expand function back to the user button
            user_btn.addEventListener('touchstart', expandSidebar);
        }
    }
    // Prevent the above function from executing immediately
    e.stopPropagation();

    // Add the close function
    document.addEventListener('touchstart', closeSidebar);

    // Remove the expand function temporarily
    user_btn.removeEventListener('touchstart', expandSidebar);

    // Add the logout function to the logout option in the sidebar
    document.querySelector('#log-out-option').addEventListener('touchstart', logOut);
}


// Show the user option menu (desktop-only)
function openOptionMenu(e) {

    // Show the option menu
    document.querySelector('#username-options').style.maxHeight = document.querySelectorAll('#username-options button').length * 2.5 + 1 + 'rem';

    // Prevent the click event below from executing immediately
    e.stopPropagation();

    // Close the option menu after clicking outside of it
    document.addEventListener('click', () => {
        if (!document.querySelector('#username-options').contains(e.target)) {
            document.querySelector('#username-options').style.maxHeight = '0';

            // Add the show option menu event listener again and remove focus from the user button
            user_btn.addEventListener('click', openOptionMenu);
            user_btn.blur();
        }
    });

    // Remove the show option menu event listener temporarily
    user_btn.removeEventListener('click', openOptionMenu);

    // Add the logout function to the logout option in the option menu
    document.querySelector('#logout-btn').addEventListener('click', logOut);
}

// The Log Out Function
function logOut(e) {
    // Prevent default behavior of click
    e.preventDefault();

    // Open an XMLHttpRequest to open logout.php, simply to destroy the session and log the user out
    let xhr = new XMLHttpRequest();
    
    xhr.open('GET', 'logout.php');
    xhr.send();

    // Redirect to login page
    window.location.href = 'login';
}


