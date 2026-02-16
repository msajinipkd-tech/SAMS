<?php
class Review {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    // Add Review
    public function addReview($data) {
        $this->db->query('INSERT INTO reviews (product_id, user_id, rating, review) VALUES(:product_id, :user_id, :rating, :review)');
        // Bind values
        $this->db->bind(':product_id', $data['product_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':review', $data['review']);

        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get Reviews by Product ID
    public function getReviewsByProductId($id) {
        $this->db->query('SELECT *, 
                        reviews.id as reviewId, 
                        users.id as userId, 
                        reviews.created_at as reviewCreated
                        FROM reviews 
                        INNER JOIN users 
                        ON reviews.user_id = users.id 
                        WHERE reviews.product_id = :product_id
                        ORDER BY reviews.created_at DESC');
        $this->db->bind(':product_id', $id);

        return $this->db->resultSet();
    }

    // Get Average Rating
    public function getAvgRating($id) {
        $this->db->query('SELECT AVG(rating) as avg_rating FROM reviews WHERE product_id = :product_id');
        $this->db->bind(':product_id', $id);
        $row = $this->db->single();
        return $row->avg_rating;
    }

    // Get all reviews (for Farmer/Admin)
    public function getAllReviews() {
        $this->db->query('SELECT *, 
                        reviews.id as reviewId, 
                        users.id as userId, 
                        products.id as productId,
                        reviews.created_at as reviewCreated,
                        products.name as productName,
                        users.username as userName
                        FROM reviews 
                        INNER JOIN users 
                        ON reviews.user_id = users.id
                        INNER JOIN products
                        ON reviews.product_id = products.id
                        ORDER BY reviews.created_at DESC');

        return $this->db->resultSet();
    }
}
