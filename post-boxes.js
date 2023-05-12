const post_bar = document.querySelector('#post-link');
if (post_bar != null) {
    post_bar.addEventListener('click', () => {
        post_bar.disabled = true;
        window.location.href='create-a-post.php';
    });
}

document.querySelectorAll('.like').forEach(like_button => {
    like_button.addEventListener('click', function() {
        const post_id = this.getAttribute("data-id");

        if (this.querySelector(".like-state").getAttribute("src") == "img/upvote-nofill.png") {
            this.querySelector(".like-state").src = "img/upvote-filled.png";
        } else {
            this.querySelector(".like-state").src = "img/upvote-nofill.png";  
        }
         
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

// make comment area expandable
document.querySelectorAll('.comment-box textarea').forEach(textarea => {
    textarea.addEventListener('input', () => {
        textarea.style.height = '1rem';
        textarea.style.height = textarea.scrollHeight + 'px';
    });
});

// comment
document.querySelectorAll('.comment-box').forEach(form => {
    form.addEventListener('submit', e => {
        e.preventDefault();

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
    });
});

// delete post prompt
document.querySelectorAll('.delete-btn').forEach(delete_btn => {
    delete_btn.addEventListener('click', () => {
        console.log(delete_btn.parentElement.querySelector('.delete-confirm'));
        delete_btn.parentElement.querySelector('.delete-confirm').classList.add('active');
    });
});

// confirm deletion
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

// edit post
document.querySelectorAll('.edit-btn').forEach(edit_btn => {
    edit_btn.addEventListener('click', () => {
        
        const form = document.createElement('form');
        form.style.display = 'none';
        document.body.appendChild(form);

        form.action = 'edit-post.php';
        form.method = 'POST';

        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'id';
        input.value = edit_btn.getAttribute('data-id');
        form.appendChild(input);
        
        form.submit();
    });
});

// edit profile picture
modify_profile.addEventListener('click', openProfileOptionBox);
modify_profile.parentElement.parentElement.querySelector('g').addEventListener('click', openProfileOptionBox);

function openProfileOptionBox() {
    backdrop.style.display = 'block';
    document.querySelector('#profile-upload-popup').style.display = 'flex';

    document.querySelector('body').style.overflowY = 'hidden';

    document.querySelectorAll('.comment-box > textarea, .comment-box > input[type="submit"], #searchbar > input, #tab-box, #user-btn')
    .forEach(container => {container.setAttribute('tabindex', -1);});
};

document.querySelectorAll('.exit-btn').forEach(exit_btn => {
    exit_btn.addEventListener('click', function() {
        backdrop.style.display = 'none';
        this.parentElement.style.display = 'none';
        
        document.querySelector('body').style.overflowY = 'auto';
    
        document.querySelectorAll('.comment-box > textarea, .comment-box > input[type="submit"], #searchbar > input, #tab-box, #user-btn')
        .forEach(container => {container.setAttribute('tabindex', 0);});
    
    });
});

document.querySelectorAll('.profile-option').forEach(profile => {
    profile.addEventListener('click', () => {
        console.log(profile.fill);
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'change-profile.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            }
        }
        xhr.send('newfill=' + encodeURIComponent(profile.getAttribute('fill')));
    });
});

const div = document.querySelector('.modal.option');
const option_btn = document.querySelector('#option-btn');

option_btn.addEventListener('focus', e => {
    div.querySelector('.tab-option:first-child').focus();
});

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

const changeNameForm = document.querySelector('#change-name');
const changeNameBio = document.querySelector('#change-description');

div.querySelectorAll('.tab-option')[0].addEventListener('click', ChangeName);
div.querySelectorAll('.tab-option')[0].addEventListener('keydown', e => {
    if (e.key === 'Space') ChangeName;
});

div.querySelectorAll('.tab-option')[1].addEventListener('click', ChangeBio);
div.querySelectorAll('.tab-option')[1].addEventListener('keydown', e => {
    if (e.key === 'Space') ChangeBio;
});

function ChangeName() {
    backdrop.style.display = 'flex';
    changeNameForm.style.display = 'unset';
}

function ChangeBio() {
    backdrop.style.display = 'flex';
    changeNameBio.style.display = 'unset';
}