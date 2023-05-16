/*
******************************************************************************************
POST-BOXES.JS

- Interaction event handler for all posts and comments
- Handles comment logic, upvoting system and sorting algorithm


Couple things to note:
- if window.innerWidth > 480px, then it is desktop display; otherwise, it is mobile
******************************************************************************************
*/

// ----------------------------------------------------------------
//  POST-BOXES.POST-LINK (THE CREATE A POST INPUT FIELD)
// ----------------------------------------------------------------
const post_bar = document.querySelector('#post-link');
if (post_bar != null) {
    // add focus event listener to post-bar
    post_bar.addEventListener('focus', () => {
        post_bar.disabled = true;
        window.location.href='create-a-post';
    });
}

// ----------------------------------------------------------------
//  POST-BOXES.LIKE (UPVOTE SYSTEM)
// ----------------------------------------------------------------

// Iterate through every upvote button
document.querySelectorAll('.like').forEach(upvote_btn => {

    // Add a click event listener to each button
    upvote_btn.addEventListener('click', function() {

        // Get the ID of the post the button is on
        const post_id = this.getAttribute("data-id");

        // Toggle the button's highlight state
        if (this.querySelector(".like-state").getAttribute("src") == "img/upvote-nofill.png") {
            this.querySelector(".like-state").src = "img/upvote-filled.png";
        } else {
            this.querySelector(".like-state").src = "img/upvote-nofill.png";  
        }
         
        // Set the new number of upvotes, and update the count in the button
        // XMLHttpRequest is done so that the user doesn't need to refresh the page every time the count gets updated
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status === 200) {
                this.querySelector('.likes').innerHTML = xhr.responseText;
            }
        }
        xhr.open("POST", 'get-likes.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('post_id=' + post_id);
    });
});

// ----------------------------------------------------------------
//  POST-BOXES.COMMENT (COMMENT SYSTEM)
// ----------------------------------------------------------------

// Set the textarea element to expand with the number of lines the user types in
document.querySelectorAll('.comment-box textarea').forEach(textarea => {
    textarea.addEventListener('input', () => {
        textarea.style.height = '1rem';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
});

// Adds the typed comment to the comments in the post
document.querySelectorAll('.comment-box').forEach(form => {
    // If the user submits the comment
    form.addEventListener('submit', e => {

        // Prevent normal action of forms
        e.preventDefault();

        // If the comment is not empty
        if (form.querySelector('textarea').value.trim() != '') {

            // Create formData from submitted form, send the comment, and refresh the page
            const formData = new FormData(form);
            for (const entry of formData.entries()) {
                console.log(entry[0] + ': ' + entry[1]);
            }
            
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'add_comment.php');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    location.reload();
                    form.reset();
                }
            }
            xhr.send(formData);
        }
    });
});

// ----------------------------------------------------------------
//  POST-BOXES.SORT (HOME.PHP SORTING ALGORITHM - NEW, HOT, TOP)
// ----------------------------------------------------------------

// For each group, add an event listener that checks for the group's id.
// If it's sort-by-hot, sort the posts by the HOT system (Most upvotes in the last 2 days)
// If it's sort-by-top, sort the posts by the TOP system (Most upvotes of all time)
// If it's sort-by-new, sort the posts by the NEW system (Most recent posts)
document.querySelectorAll('.post-tab > .group').forEach(sortTab => {
    sortTab.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'sorting-algorithm.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();                
            }
        }

        if (sortTab.id == 'sort-by-hot') {
            xhr.send('sortMethod=hot');
        }
        else if (sortTab.id == 'sort-by-top') {
            xhr.send('sortMethod=top');
        }
        else {
            xhr.send('sortMethod=new');
        } 
    });
});