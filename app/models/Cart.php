<?php
class Cart
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function addToCart($userId, $productId, $quantity)
    {
        // Check if product already in cart
        $this->db->query('SELECT * FROM cart WHERE user_id = :user_id AND product_id = :product_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':product_id', $productId);
        $row = $this->db->single();

        if ($row) {
            // Update quantity
            $newQuantity = $row->quantity + $quantity;
            $this->db->query('UPDATE cart SET quantity = :quantity WHERE id = :id');
            $this->db->bind(':quantity', $newQuantity);
            $this->db->bind(':id', $row->id);
        } else {
            // Insert new
            $this->db->query('INSERT INTO cart (user_id, product_id, quantity) VALUES(:user_id, :product_id, :quantity)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':product_id', $productId);
            $this->db->bind(':quantity', $quantity);
        }

        return $this->db->execute();
    }

    public function getCartItems($userId)
    {
        $this->db->query('SELECT c.*, p.name, p.price, p.description, (c.quantity * p.price) as total_price 
                          FROM cart c 
                          JOIN products p ON c.product_id = p.id 
                          WHERE c.user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function removeFromCart($cartId)
    {
        $this->db->query('DELETE FROM cart WHERE id = :id');
        $this->db->bind(':id', $cartId);
        return $this->db->execute();
    }

    public function clearCart($userId)
    {
        $this->db->query('DELETE FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
}
