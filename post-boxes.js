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
})