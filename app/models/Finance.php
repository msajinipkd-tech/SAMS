<?php
class Finance
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    public function addExpense($data)
    {
        $this->db->query('INSERT INTO expenses (user_id, category, amount, date, description, related_cycle_id) VALUES(:user_id, :category, :amount, :date, :description, :related_cycle_id)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':amount', $data['amount']);
        $this->db->bind(':date', $data['date']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':related_cycle_id', $data['related_cycle_id']);
        return $this->db->execute();
    }

    public function getExpenses($user_id)
    {
        $this->db->query('SELECT e.*, c.season as cycle_season FROM expenses e LEFT JOIN crop_cycles c ON e.related_cycle_id = c.id WHERE e.user_id = :user_id ORDER BY e.date DESC');
        $this->db->bind(':user_id', $user_id);
        return $this->db->resultSet();
    }

    public function getTotalRevenue($user_id)
    {
        // Join harvests with crop_cycles to filter by user_id
        $this->db->query('SELECT SUM(h.revenue) as total_revenue
                          FROM harvests h
                          JOIN crop_cycles cc ON h.cycle_id = cc.id
                          WHERE cc.user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row->total_revenue ?? 0;
    }

    public function getTotalExpenses($user_id)
    {
        $this->db->query('SELECT SUM(amount) as total_expenses FROM expenses WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        $row = $this->db->single();
        return $row->total_expenses ?? 0;
    }
}
