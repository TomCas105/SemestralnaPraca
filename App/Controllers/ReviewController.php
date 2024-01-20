<?php

namespace App\Controllers;

use App\Core\AControllerBase;
use App\Core\HTTPException;
use App\Core\Responses\Response;
use App\Models\Post;
use App\Models\Review;
use Exception;

class ReviewController extends AControllerBase
{

    public function index(): Response
    {
        throw new HTTPException(501, "Not Implemented");
    }

    /**
     * @throws Exception
     */
    public function getPostReviews(): Response
    {
        $post_id = $this->request()->getValue("id");

        $reviews = Review::getAll("post_id = '" . $post_id . "'", orderBy: "date DESC");
        $reviews2 = array();

        if (empty($reviews)) {
            return $this->json("");
        }

        foreach ($reviews as $review) {
            $reviews2[] = array(
                "review_author" => $review->getReviewAuthor(),
                "review_text" => $review->getReviewText(),
                "age" => $review->getFormattedAge(),
                "rating" => $review->getRating()
            );
        }

        return $this->json($reviews2);
    }

    /**
     * @throws Exception
     */
    public function getLatestReviews(): Response
    {
        $reviews = Review::getAll(orderBy: "date DESC", limit: 10);

        $reviews2 = array();

        foreach ($reviews as $review) {
            $post = Post::getOne($review->getPostId());
            $reviews2[] = array(
                "post_title" => $post->getTitle(),
                "post_id" => $review->getPostId(),
                "review_author" => $review->getReviewAuthor(),
                "review_text" => $review->getReviewTextShort(),
                "age" => $review->getFormattedAge(),
                "rating" => $review->getRating()
            );
        }

        return $this->json($reviews2);
    }

    /**
     * @throws HTTPException
     * @throws Exception
     */
    public function updateReview(): Response
    {
        $data = json_decode(file_get_contents('php://input'));

        $id = (int)$data->id;
        $post = Post::getOne($id);

        if (!isset($post)) {
            throw new HTTPException(404);
        }

        $review_text = $data->review_text;
        $user = $this->app->getAuth()->getLoggedUserName();
        $review_rating = (int)$data->rating;

        $review = null;

        $reviews = Review::getAll(whereClause: "review_author='" . $user . "' and post_id='" . $id . "'");
        if (empty($reviews)) {
            $review = new Review();
            $review->setPostId($id);
            $review->setReviewAuthor($user);
        } else {
            $review = $reviews[0];
        }
        date_default_timezone_set("Europe/Prague");
        $review->setDate(date("Y-m-d H:i:s"));
        $review->setRating($review_rating);
        $review->setReviewText($review_text);
        $review->save();

        return $this->json(array('ok' => true));
    }
}