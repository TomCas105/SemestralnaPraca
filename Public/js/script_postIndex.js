class PostAjax {
    constructor() {

        let saveRecipeButton = document.getElementById("save_recipe");
        if (saveRecipeButton != null) {
            saveRecipeButton.onclick = () => this.saveRecipe()
        }

        let recommendRecipeButton = document.getElementById("recommend_recipe");
        if (recommendRecipeButton != null) {
            recommendRecipeButton.onclick = () => this.recommendRecipe()
        }

        let updateReviewButton = document.getElementById("update_review");
        if (updateReviewButton != null) {
            updateReviewButton.onclick = () => this.updateReview()
        }

        let deleteReviewButton = document.getElementById("delete_review");
        if (deleteReviewButton != null) {
            deleteReviewButton.onclick = () => this.deleteReview()
        }

        let reviewTextArea = document.getElementById("review_text");
        if (reviewTextArea != null) {
            reviewTextArea.onchange = () => this.getReviewErrors()
            this.getUserPostReview();
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
        await this.getSavedRecipe();
        await this.getRecommendedRecipe();
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
                    <div class="list-group-item d-flex gap-3 py-3">
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

    async getUserPostReview() {
        let response = await fetch("?c=review&a=getUserPostReview&id=" + post_id);
        let data = await response.json();

        let reviewTextArea = document.getElementById("review_text");
        let reviewRatingBar = document.getElementById("review_rating");

        if (Object.keys(data).length > 0) {
            document.getElementById("review_text").value = data.review_text;
            await this.setRating(data.review_rating);
        } else {
            document.getElementById("review_text").value = "";
            await this.setRating(0);
        }
    }

    async updateReview() {
        if (await this.getReviewErrors()) {
            return;
        }
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
        await this.getPostReviews()
    }

    async deleteReview() {
        let response = await fetch("?c=review&a=deleteReview", {
            method: 'POST',
            headers: {
                'Content-Type': "application/json",
            },
            body: JSON.stringify({
                id: post_id
            })
        });
        document.getElementById("review_text").value = "";
        await this.setRating(0);
        await this.getPostReviews()
    }

    async recommendRecipe() {
        let response = await fetch("?c=post&a=recommendRecipe&id=" + post_id);
        let data = await response.json();
        if (data.ok === 1) {
            document.getElementById("recommend_recipe").innerText = "Odobrať z odporúčaných";
        } else {
            document.getElementById("recommend_recipe").innerText = "Odporúčiť recept";
        }
    }

    async getRecommendedRecipe() {
        if (user == null || user !== "Admin") {
            return;
        }
        let response = await fetch("?c=post&a=isRecommendedRecipe&id=" + post_id);
        let data = await response.json();
        if (data.ok === 1) {
            document.getElementById("recommend_recipe").innerText = "Odobrať z odporúčaných";
        } else {
            document.getElementById("recommend_recipe").innerText = "Odporúčiť recept";
        }
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

    async getReviewErrors() {
        let errors = "";
        let reviewRating = document.getElementById("review_rating");
        if (reviewRating != null && reviewRating.value < 1) {
            errors += `<div class="alert alert-danger" role="alert">Musíte zvoliť hodnotenie.</div>`;
        }
        let reviewText = document.getElementById("review_text");
        if (reviewText != null && reviewText.value.length > 500) {
            errors += `<div class="alert alert-danger" role="alert">Obsah hodnotenia nesmie byť viac ako 500 znakov. (${reviewText.value.length} znakov)</div>`;
        }
        let alertContainer = document.getElementById("alert_container");
        if (alertContainer != null) {
            alertContainer.innerHTML = errors;
            if (errors.length > 0) {
                alertContainer.style = "display: block";
            } else {
                alertContainer.style = "display: none";
            }
        }
        return errors.length > 0;
    }
}

let postAjax;

document.addEventListener('DOMContentLoaded', () => {
    postAjax = new PostAjax();
}, false);