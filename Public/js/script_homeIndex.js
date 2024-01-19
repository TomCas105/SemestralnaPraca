class ReviewAjax {
    constructor() {
        this.getLatestReviews();

        setInterval(() => this.getLatestReviews(), 5000);
    }

    async getLatestReviews() {
        let response = await fetch("?c=review&a=getLatestReviews");
        let data = await response.json();
        let reviewList = document.getElementById("reviewList");
        let html = "";
        data.forEach((review) => {
            html += `
            <a href="?c=post&id=${review.post_id}" class="list-group-item list-group-item-action d-flex gap-3 py-3">
                        <img src="App/Resources/images/icons/user_icon_01.png" alt="..."
                             class="rounded-circle user-icon">
                        <div class="d-flex gap-2 w-100 justify-content-between">
                            <div>
                                <h6 class="mb-1">${review.review_author}</h6>
                                <h6 class="mb-1 opacity-50">${review.post_title}</h6>
                                <div class="rating-bar">
                                        <span class="rating-bar-star-bg">
                                            <span class="rating-bar-star"
                                                  style="width: ${review.rating * 20}%"></span>
                                        </span>
                                </div>`
            if (review.review_text != null && review.review_text !== "") {
                html += `<p class="mt-1 mb-0">${review.review_text}</p>`
            }
            html += `</div>
                            <small class="opacity-50 text-nowrap">${review.age}</small>
                        </div>
                </a>
            `
        });
        reviewList.innerHTML = html;
    }
}

let reviewAjax;

document.addEventListener('DOMContentLoaded', () => {
    reviewAjax = new ReviewAjax();
}, false);