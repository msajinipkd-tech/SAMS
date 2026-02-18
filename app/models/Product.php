<?php
class Product
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function getProducts($category = null, $limit = null)
    {
        $sql = 'SELECT * FROM products';
        if ($category) {
            if ($category == 'Seeds' || $category == 'Seed') {
                $sql .= " WHERE (category = 'Seeds' OR category = 'Seed')";
            } elseif ($category == 'Vegetables' || $category == 'Vegetable') {
                $sql .= " WHERE (category = 'Vegetables' OR category = 'Vegetable')";
            } elseif ($category == 'Plants' || $category == 'Plant') {
                $sql .= " WHERE (category = 'Plants' OR category = 'Plant')";
            } elseif ($category == 'Pesticides' || $category == 'Pesticide') {
                $sql .= " WHERE (category = 'Pesticides' OR category = 'Pesticide')";
            } else {
                $sql .= ' WHERE category = :category';
            }
        }
        $sql .= ' ORDER BY created_at DESC';
        
        if ($limit) {
            $sql .= ' LIMIT ' . $limit;
        }

        $this->db->query($sql);

        if ($category && 
            !in_array($category, ['Seeds', 'Seed', 'Vegetables', 'Vegetable', 'Plants', 'Plant', 'Pesticides', 'Pesticide'])) {
            $this->db->bind(':category', $category);
        }

        return $this->db->resultSet();
    }

    public function addProduct($data)
    {
        $this->db->query('INSERT INTO products (name, price, quantity, description, image) VALUES(:name, :price, :quantity, :description, :image)');
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getProductById($id)
    {
        $this->db->query('SELECT * FROM products WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateProduct($data)
    {
        $this->db->query('UPDATE products SET name = :name, price = :price, quantity = :quantity, description = :description, image = :image WHERE id = :id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function deleteProduct($id)
    {
        $this->db->query('DELETE FROM products WHERE id = :id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function searchProducts($keyword)
    {
        $this->db->query("SELECT * FROM products WHERE name LIKE :keyword OR description LIKE :keyword OR category LIKE :keyword ORDER BY created_at DESC");
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->resultSet();
    }

    public function getCategories()
    {
        $this->db->query("SELECT DISTINCT category FROM products ORDER BY category ASC");
        return $this->db->resultSet();
    }
}
