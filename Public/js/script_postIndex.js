class PostAjax {
    constructor() {

        let saveRecipeButton = document.getElementById("save_recipe");
        if (saveRecipeButton != null) {
            saveRecipeButton.onclick = () => this.saveRecipe()
        }

        let updateReviewButton = document.getElementById("update_review");
        if (updateReviewButton != null) {
            updateReviewButton.onclick = () => this.updateReview()
        }

        for (let i = 1; i <= 5; i++) {
            let rating = document.getElementById("rating" + i);
            if (rating != null) {
                rating.onclick = () => this.setRating(i)
            }
        }

        this.refresh();

        setInterval(() => this.refresh(), 5000);
    }

    async refresh() {
        await this.getPostReviews();
        await this.getSavedRecipe()
    }

    async getPostReviews() {
        let response = await fetch("?c=review&a=getPostReviews&id=" + post_id);
        let data = await response.json();
        if (Object.keys(data).length > 0) {
            document.getElementById("review_container").style = "display: block";
            let reviewList = document.getElementById("reviewList");
            let html = "";
            data.forEach((review) => {
                html += `
                <div class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                             class="rounded-circle user-icon">
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-1">${review.review_author}</h6>
                                <div class="rating-bar">
                                        <span class="rating-bar-star-bg">
                                            <span class="rating-bar-star"
                                                  style="width: ${review.rating * 20}%"></span>
                                        </span>
                                </div>`
                if (review.review_text != null && review.review_text !== "") {
                    html += `<p class="mt-2 mb-0">${review.review_text}</p>`
                }
                html += `</div>
                            <small class="opacity-50 text-nowrap">${review.age}</small>
                        </div>
                </div>
            `
            });
            reviewList.innerHTML = html;
        } else {
            document.getElementById("review_container").style = "display: none";
        }
    }

    async updateReview() {
        let response = await fetch("?c=review&a=updateReview", {
            method: 'POST',
            headers: {
                'Content-Type': "application/json",
            },
            body: JSON.stringify({
                id: post_id,
                rating: document.getElementById("review_rating").value,
                review_text: document.getElementById("review_text").value
            })
        });
        if (response.ok) {
            document.getElementById("review_text").value = "";
            await this.setRating(0);
        }
        await this.getPostReviews()
    }

    async toggleRecommend() {

    }

    async saveRecipe() {
        let response = await fetch("?c=post&a=saveRecipe&id=" + post_id);
        let data = await response.json();
        if (data.ok === 1) {
            document.getElementById("save_recipe").innerText = "Odobrať z uložených";
        } else {
            document.getElementById("save_recipe").innerText = "Uložiť recept";
        }
    }

    async getSavedRecipe() {
        if (user == null || user === "") {
            return;
        }
        let response = await fetch("?c=post&a=isSavedRecipe&id=" + post_id);
        let data = await response.json();
        if (data.ok === 1) {
            document.getElementById("save_recipe").innerText = "Odobrať z uložených";
        } else {
            document.getElementById("save_recipe").innerText = "Uložiť recept";
        }
    }

    async setRating(rating) {
        let input = document.getElementById("review_rating");
        input.value = rating;
        await this.refreshRating(input.value);
    }

    async refreshRating(rating) {
        let stars = document.getElementsByClassName("rating-star");
        for (let i = 0; i < rating; i++) {
            stars[i].className = "rating-star rating-star-checked";
        }
        for (let i = rating; i < 5; i++) {
            stars[i].className = "rating-star";
        }
    }
}

let postAjax;

document.addEventListener('DOMContentLoaded', () => {
    postAjax = new PostAjax();
}, false);