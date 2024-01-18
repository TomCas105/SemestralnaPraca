function setRating(rating) {
    let input = document.getElementById("review_rating");
    input.value = rating;
    refreshRating(input.value);
}

function refreshRating(rating) {
    let stars = document.getElementsByClassName("rating-star");
    for (let i = 0; i < rating; i++) {
        stars[i].className = "rating-star rating-star-checked";
    }
    for (let i = rating; i < 5; i++) {
        stars[i].className = "rating-star";
    }
}