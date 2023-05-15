// delete post prompt
document.querySelectorAll('.delete-btn').forEach(delete_btn => {
    delete_btn.addEventListener('click', () => {
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
    document.querySelector('#backdrop-profile').style.opacity = '1';
    document.querySelector('#profile-upload-popup').style.transform = 'scale(1)';

    document.querySelector('body').style.overflowY = 'hidden';

    document.querySelectorAll('.comment-box > textarea, .comment-box > input[type="submit"], #searchbar > input, #tab-box, #user-btn')
    .forEach(container => {container.setAttribute('tabindex', -1);});
};

document.querySelectorAll('.exit-btn').forEach(exit_btn => {
    exit_btn.addEventListener('click', function() {
        document.querySelector('#backdrop-profile').style.opacity = '0';
        this.parentElement.style.transform = 'scale(0)';
        
        document.querySelector('body').style.overflowY = 'overlay';
    
        document.querySelectorAll('.comment-box > textarea, .comment-box > input[type="submit"], #searchbar > input, #tab-box, #user-btn')
        .forEach(container => {container.setAttribute('tabindex', 0);});
    
    });
});

document.querySelectorAll('.profile-option').forEach(profile => {
    profile.addEventListener('click', () => {
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'change-profile.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
                document.querySelector('#profile-upload-popup').style.display = 'none';
                document.querySelector('#profile-avatars').scrollTop = 0;
            }
        }
        xhr.send('newfill=' + encodeURIComponent(profile.getAttribute('href')));
    });
});

const div = document.querySelector('.modal.option');
const option_btn = document.querySelector('#option-btn');

const changeNameForm = document.querySelector('#change-name');
const changeNameBio = document.querySelector('#change-description');
const clearSearches = document.querySelector('#clear-searches');

option_btn.addEventListener('focus', e => {
    let index = -1;
    let prevIndex = 0;

    div.querySelectorAll('.tab-option')[0].classList.remove('active');
    div.querySelectorAll('.tab-option')[1].classList.remove('active');
    div.querySelectorAll('.tab-option')[2].classList.remove('active');

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
            else if (index === 2) RemoveSearches();sol
        }
    }
    option_btn.addEventListener('keydown', KeyDown);

    div.querySelectorAll('.tab-option')[0].addEventListener('click', ChangeName);
    div.querySelectorAll('.tab-option')[1].addEventListener('click', ChangeBio);
    div.querySelectorAll('.tab-option')[2].addEventListener('click', RemoveSearches);
    
});

document.querySelectorAll('.option')[0].addEventListener('touchstart', ChangeName);
document.querySelectorAll('.option')[1].addEventListener('touchstart', ChangeBio);
document.querySelectorAll('.option')[2].addEventListener('touchstart', RemoveSearches);

function ChangeName() {
    document.querySelector('#mobile-sidebar').classList.remove('active');
    document.querySelector('#backdrop').classList.remove('active');
    user_btn.addEventListener('touchstart', expandSidebar);

    document.querySelector('#backdrop-profile').style.opacity = '1';
    changeNameForm.style.transform = 'scale(1)';
    document.querySelector('body').style.overflowY = 'hidden';
}

function ChangeBio() {
    document.querySelector('#mobile-sidebar').classList.remove('active');
    document.querySelector('#backdrop').classList.remove('active');
    user_btn.addEventListener('touchstart', expandSidebar);

    document.querySelector('#backdrop-profile').style.opacity = '1';
    changeNameBio.style.transform = 'scale(1)';
    document.querySelector('body').style.overflowY = 'hidden';
}

function RemoveSearches() {
    document.querySelector('#mobile-sidebar').classList.remove('active');
    document.querySelector('#backdrop').classList.remove('active');
    user_btn.addEventListener('touchstart', expandSidebar);

    document.querySelector('#backdrop-profile').style.opacity = '1';
    clearSearches.style.transform = 'scale(1)';
    document.querySelector('body').style.overflowY = 'hidden';
}

function ClearSearchHistory() {
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'clear-searches.php');
    xhr.onload = function() {
        console.log('Successfully cleared search history');
        location.reload();
    }
    xhr.send();
}

// Clear searches
clearSearches.querySelector('.yes-btn').addEventListener('click', ClearSearchHistory);
clearSearches.querySelector('.yes-btn').addEventListener('touchstart', ClearSearchHistory);

clearSearches.querySelector('.no-btn').addEventListener('click', () => {
    document.querySelector('#backdrop-profile').style.opacity = '0';
    clearSearches.style.transform = 'scale(0)';
    document.querySelector('body').style.overflowY = 'overlay';
});

clearSearches.querySelector('.no-btn').addEventListener('touchstart', () => {
    document.querySelector('#backdrop-profile').style.opacity = '0';
    clearSearches.style.transform = 'scale(0)';
    document.querySelector('body').style.overflowY = 'overlay';
});