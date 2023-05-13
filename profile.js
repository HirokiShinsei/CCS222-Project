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
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'change-profile.php');
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200) {
                location.reload();
            }
        }
        xhr.send('newfill=' + encodeURIComponent(profile.getAttribute('href')));
    });
});

const div = document.querySelector('.modal.option');
const option_btn = document.querySelector('#option-btn');

const changeNameForm = document.querySelector('#change-name');
const changeNameBio = document.querySelector('#change-description');

option_btn.addEventListener('focus', e => {
    let index = 1;

    div.querySelectorAll('.tab-option')[0].classList.remove('active');
    div.querySelectorAll('.tab-option')[1].classList.remove('active');

    function KeyDown(e) {
        if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
            e.preventDefault();
            let prevIndex = index;
            index = index == 0 ? 1 : 0;
    
            div.querySelectorAll('.tab-option')[index].classList.add('active');
            div.querySelectorAll('.tab-option')[prevIndex].classList.remove('active');
            
    
            option_btn.addEventListener('blur', () => {
                option_btn.removeEventListener('keydown', KeyDown);
            });

        } else if (e.key === 'Enter') {
            if (index === 0) ChangeName();
            else ChangeBio();
        }
    }
    option_btn.addEventListener('keydown', KeyDown);
    div.querySelectorAll('.tab-option')[0].addEventListener('click', ChangeName);
    div.querySelectorAll('.tab-option')[1].addEventListener('click', ChangeBio);
});

function ChangeName() {
    backdrop.style.display = 'flex';
    changeNameForm.style.display = 'unset';
    document.querySelector('body').style.overflowY = 'hidden';
}

function ChangeBio() {
    backdrop.style.display = 'flex';
    changeNameBio.style.display = 'unset';
    document.querySelector('body').style.overflowY = 'hidden';
}