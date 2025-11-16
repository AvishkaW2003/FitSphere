<?php
class Booking {
    private $conn;
    private $table_name = "bookings";

    public $id;
    public $customer_name;
    public $suit;
    public $period;
    public $total;
    public $deposite;
    public $late_days;
    public $late_fees;
    public $refund_amount;
    public $status;
    public $returned_date;
    public $manual_late_days;
    public $manual_late_fee;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                SET customer_name=:customer_name, suit=:suit, period=:period, 
                    total=:total, deposite=:deposite, late_days=:late_days, 
                    late_fees=:late_fees, refund_amount=:refund_amount, status=:status, 
                    returned_date=:returned_date, manual_late_days=:manual_late_days, 
                    manual_late_fee=:manual_late_fee";

        $stmt = $this->conn->prepare($query);

        $this->customer_name = htmlspecialchars(strip_tags($this->customer_name));
        $this->suit = htmlspecialchars(strip_tags($this->suit));
        $this->period = htmlspecialchars(strip_tags($this->period));

        $stmt->bindParam(":customer_name", $this->customer_name);
        $stmt->bindParam(":suit", $this->suit);
        $stmt->bindParam(":period", $this->period);
        $stmt->bindParam(":total", $this->total);
        $stmt->bindParam(":deposite", $this->deposite);
        $stmt->bindParam(":late_days", $this->late_days);
        $stmt->bindParam(":late_fees", $this->late_fees);
        $stmt->bindParam(":refund_amount", $this->refund_amount);
        $stmt->bindParam(":status", $this->status);
        $stmt->bindParam(":returned_date", $this->returned_date);
        $stmt->bindParam(":manual_late_days", $this->manual_late_days);
        $stmt->bindParam(":manual_late_fee", $this->manual_late_fee);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    public function read() {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }
}
?>