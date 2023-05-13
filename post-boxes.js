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