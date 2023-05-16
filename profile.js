/*
******************************************************************************************
PROFILE.JS

- Interaction event handler for profile.php and profile-visit.php
- Handles delete post button, edit post button, avatar editor, clear searches, change
name and change bio options


Couple things to note:
- if window.innerWidth > 480px, then it is desktop display; otherwise, it is mobile
******************************************************************************************
*/

// ----------------------------------------------------------------
//  PROFILE.DELETE-POST (DELETES A POST)
// ----------------------------------------------------------------
document.querySelectorAll('.delete-btn').forEach(delete_btn => {
    
    // If the user clicks the delete button, show delete prompt
    delete_btn.addEventListener('click', () => {
        delete_btn.parentElement.querySelector('.delete-confirm').classList.add('active');
    });
});

// If the user clicks yes on the delete prompt, delete the post
function delete_post(postID) {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'delete-post.php');
    xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if (xhr.status === 200) {
            location.reload();
        }
    }
    xhr.send('id=' + encodeURIComponent(postID));
}

// ----------------------------------------------------------------
//  PROFILE.EDIT-POST (EDITS A POST)
// ----------------------------------------------------------------
document.querySelectorAll('.edit-btn').forEach(edit_btn => {
    
    // If the user clicks the edit button, redirect to edit-post.php and add the post title and content
    edit_btn.addEventListener('click', () => {
        
        const form = document.createElement('form');
        form.style.display = 'none';
        document.body.appendChild(form);

        form.action = 'edit-post';
        form.method = 'POST';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = edit_btn.getAttribute('data-id');
        form.appendChild(input);
        
        form.submit();
    });
});

// ------------------------------------------------------------------------
//  PROFILE.EDIT-PROFILE (SELECTS A NEW PROFILE FROM A LIST OF AVATARS)
// ------------------------------------------------------------------------
modify_profile.addEventListener('click', openProfileOptionBox);
modify_profile.parentElement.parentElement.querySelector('g').addEventListener('click', openProfileOptionBox);

// Shows the avatar menu
function openProfileOptionBox() {
    // Shows the blur background
    document.querySelector('#backdrop-profile').style.opacity = '1';

    // Show the window
    document.querySelector('#profile-upload-popup').style.transform = 'scale(1)';

    // Removes the scrollbar to prevent user scrolling while the window is open
    // Note: Nudges the content on non webkit-based browsers that do not support overflow: overlay
    document.querySelector('body').style.overflowY = 'hidden';

    // Remove the tab index of the background tabs (to prevent user navigation on the background while the window is open)
    document.querySelectorAll('.comment-box > textarea, .comment-box > input[type="submit"], #searchbar > input, #tab-box, #user-btn')
    .forEach(container => {container.setAttribute('tabindex', -1);});
};

// ----------------------------------------------------------------------------
//  PROFILE.EXIT (EXIT BUTTON FOR CHANGE PROFILE, CHANGE BIO, AND CHANGE NAME)
// ----------------------------------------------------------------------------
document.querySelectorAll('.exit-btn').forEach(exit_btn => {
    exit_btn.addEventListener('click', function() {
        // Hides the blur background
        document.querySelector('#backdrop-profile').style.opacity = '0';

        // Hide the window
        this.parentElement.style.transform = 'scale(0)';
        
        // Shows the scrollbar again (if scrollable)
        // Does not work on non webkit-based browsers
        document.querySelector('body').style.overflowY = 'overlay';
        
        // Add the tabindex to background tabs to allow tab navigation
        document.querySelectorAll('.comment-box > textarea, .comment-box > input[type="submit"], #searchbar > input, #tab-box, #user-btn')
        .forEach(container => {container.setAttribute('tabindex', 0);});
    
    });
});

// -------------------------------------------------
//  PROFILE.PROFILE-OPTION (THE INDIVIDUAL AVATARS)
// -------------------------------------------------
document.querySelectorAll('.profile-option').forEach(profile => {

    // When avatar is clicked, replace the current profile path with the avatar's src path
    profile.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'change-profile.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();

                // Hide the avatar window and scroll it back to top
                document.querySelector('#profile-upload-popup').style.display = 'none';
                document.querySelector('#profile-avatars').scrollTop = 0;
            }
        }
        xhr.send('newfill=' + encodeURIComponent(profile.getAttribute('href')));
    });
});

// -------------------------------------------------------------------------------------------
//  PROFILE.MODAL-OPTION (CONTAINS CHANGE NAME, CHANGE DESCRIPTION AND CLEAR RECENT SEARCHES)
//  Note: This is desktop-only!
// -------------------------------------------------------------------------------------------
// The option container
const div = document.querySelector('.modal.option');

// The three dots that shows the option container
const option_btn = document.querySelector('#option-btn');

// The change name window
const changeNameForm = document.querySelector('#change-name');

// The change bio window
const changeNameBio = document.querySelector('#change-description');

// The clear recent searches window
const clearSearches = document.querySelector('#clear-searches');

// If the three dots are focused on (either clicked on or tabbed into)
option_btn.addEventListener('focus', e => {
    let index = -1;
    let prevIndex = 0;

    // Remove the active class from all three options
    div.querySelectorAll('.tab-option')[0].classList.remove('active');
    div.querySelectorAll('.tab-option')[1].classList.remove('active');
    div.querySelectorAll('.tab-option')[2].classList.remove('active');

    // If the user clicks the up or down arrow keys, navigate through all the options
    // If the user clicks the Enter key, open the option it is selected on
    function KeyDown(e) {
        if (e.key === 'ArrowDown') {
            e.preventDefault();
            if (index === -1) index = 0;
            else {
                prevIndex = index;
                index = index === 2 ? 0 : index + 1;
        
            }
            div.querySelectorAll('.tab-option')[index].classList.add('active');
            div.querySelectorAll('.tab-option')[prevIndex].classList.remove('active');

            option_btn.addEventListener('blur', () => {
                option_btn.removeEventListener('keydown', KeyDown);
            });

        } else if (e.key === 'ArrowUp') {
            e.preventDefault();
            if (index === -1) index = 2;
            else {
                prevIndex = index;
                index = index === 0 ? 2 : index - 1;
            
            }
            div.querySelectorAll('.tab-option')[index].classList.add('active');
            div.querySelectorAll('.tab-option')[prevIndex].classList.remove('active');
    
            option_btn.addEventListener('blur', () => {
                option_btn.removeEventListener('keydown', KeyDown);
            });

        } else if (e.key === 'Enter') {
            if (index === 0) ChangeName();
            else if (index === 1) ChangeBio();
            else if (index === 2) RemoveSearches();
        }
    }
    option_btn.addEventListener('keydown', KeyDown);

    // Add the click event listeners to all the options, each with their respective functions for each window
    div.querySelectorAll('.tab-option')[0].addEventListener('click', ChangeName);
    div.querySelectorAll('.tab-option')[1].addEventListener('click', ChangeBio);
    div.querySelectorAll('.tab-option')[2].addEventListener('click', RemoveSearches);
    
});

// Add the touch event listeners to all the options in the sidebar (change name, change bio, clear recent searches)
// Note: this is mobile-only!
document.querySelectorAll('.option')[0].addEventListener('touchstart', ChangeName);
document.querySelectorAll('.option')[1].addEventListener('touchstart', ChangeBio);
document.querySelectorAll('.option')[2].addEventListener('touchstart', RemoveSearches);

// ----------------------------------------------------------------------
//  PROFILE.OPTION-FUNCTIONS (OPENS THE FUNCTION'S CORRESPONDING WINDOW)
// Also hides the sidebar (mobile-only)
// ----------------------------------------------------------------------
// Opens the changeName Window
function ChangeName() {
    document.querySelector('#mobile-sidebar').classList.remove('active');
    document.querySelector('#backdrop').classList.remove('active');
    user_btn.addEventListener('touchstart', expandSidebar);

    document.querySelector('#backdrop-profile').style.opacity = '1';
    changeNameForm.style.transform = 'scale(1)';
    document.querySelector('body').style.overflowY = 'hidden';
}

// Opens the changeBio (change description) Window
function ChangeBio() {
    document.querySelector('#mobile-sidebar').classList.remove('active');
    document.querySelector('#backdrop').classList.remove('active');
    user_btn.addEventListener('touchstart', expandSidebar);

    document.querySelector('#backdrop-profile').style.opacity = '1';
    changeNameBio.style.transform = 'scale(1)';
    document.querySelector('body').style.overflowY = 'hidden';
}

// Opens the removeSearches Prompt
function RemoveSearches() {
    document.querySelector('#mobile-sidebar').classList.remove('active');
    document.querySelector('#backdrop').classList.remove('active');
    user_btn.addEventListener('touchstart', expandSidebar);

    document.querySelector('#backdrop-profile').style.opacity = '1';
    clearSearches.style.transform = 'scale(1)';
    document.querySelector('body').style.overflowY = 'hidden';
}

// Clears the search history of the user
function ClearSearchHistory() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'clear-searches.php');
    xhr.onload = function() {
        console.log('Successfully cleared search history');
        location.reload();
    }
    xhr.send();
}

// ---------------------------------------------------------
//  PROFILE.CLEAR-HISTORY (THE CLEAR SEARCH HISTORY WINDOW)
// ---------------------------------------------------------

// When user clicks or taps the yes button, executes the function ClearSearchHistory
clearSearches.querySelector('.yes-btn').addEventListener('click', ClearSearchHistory);

// When user clicks or taps the no button, hides the clear searches window and the blur background
clearSearches.querySelector('.no-btn').addEventListener('click', () => {
    document.querySelector('#backdrop-profile').style.opacity = '0';
    clearSearches.style.transform = 'scale(0)';
    document.querySelector('body').style.overflowY = 'overlay';
});