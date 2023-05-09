document.querySelectorAll('.like').forEach(like_button => {
    like_button.addEventListener('click', function() {
        const post_id = this.getAttribute("data-id");

        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = () => {
            if (xhr.readyState == 4 && xhr.status === 200) {
                if (this.querySelector(".like-state").getAttribute("src") == "img/upvote-nofill.png") {
                    this.querySelector(".like-state").src = "img/upvote-filled.png";
                } else {
                    this.querySelector(".like-state").src = "img/upvote-nofill.png";  
                } 
                this.querySelector('.likes').innerHTML = xhr.responseText;
            }
        }
        xhr.open("POST", 'get-likes.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.send('post_id=' + post_id);
    });
});

// comment
document.querySelectorAll('.comment-box').forEach(form => {
    console.log(form);
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