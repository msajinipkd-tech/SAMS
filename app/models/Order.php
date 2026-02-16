<?php
class Order
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function createOrder($userId, $cartItems)
    {
        // Check stock availability first
        foreach ($cartItems as $item) {
            $this->db->query('SELECT quantity FROM products WHERE id = :id');
            $this->db->bind(':id', $item->product_id);
            $product = $this->db->single();

            if (!$product || $product->quantity < $item->quantity) {
                return false; // Insufficient stock
            }
        }

        // Proceed with order creation and stock reduction
        $success = true;
        foreach ($cartItems as $item) {
            $totalPrice = $item->price * $item->quantity; // Calculate total based on current price
            
            // Deduct stock
            $this->db->query('UPDATE products SET quantity = quantity - :qty WHERE id = :id');
            $this->db->bind(':qty', $item->quantity);
            $this->db->bind(':id', $item->product_id);
            if (!$this->db->execute()) {
                $success = false;
                break; 
            }

            // Create Order
            $this->db->query('INSERT INTO orders (user_id, product_id, quantity, total_price, status) VALUES(:user_id, :product_id, :quantity, :total_price, :status)');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':product_id', $item->product_id);
            $this->db->bind(':quantity', $item->quantity);
            $this->db->bind(':total_price', $totalPrice);
            $this->db->bind(':status', 'pending');
            
            if (!$this->db->execute()) {
                $success = false;
            }
        }
        return $success;
    }

    public function getOrdersByUserId($userId)
    {
        $this->db->query('SELECT o.*, p.name as product_name, p.price as product_price 
                          FROM orders o 
                          JOIN products p ON o.product_id = p.id 
                          WHERE o.user_id = :user_id 
                          ORDER BY o.created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function cancelOrder($orderId)
    {
        // Get order details to restore stock
        $this->db->query('SELECT product_id, quantity FROM orders WHERE id = :id');
        $this->db->bind(':id', $orderId);
        $order = $this->db->single();

        if ($order) {
            // Restore stock
            $this->db->query('UPDATE products SET quantity = quantity + :qty WHERE id = :id');
            $this->db->bind(':qty', $order->quantity);
            $this->db->bind(':id', $order->product_id);
            $this->db->execute();
        }

        // Only allow cancelling if pending
        $this->db->query('UPDATE orders SET status = :status WHERE id = :id AND status = :current_status');
        $this->db->bind(':status', 'cancelled');
        $this->db->bind(':id', $orderId);
        $this->db->bind(':current_status', 'pending');
        return $this->db->execute();
    }
}
