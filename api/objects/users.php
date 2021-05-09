<?php
class Users{
    private $conn;
    private $table_name;
    public $user_name;
    public $old_account;
    public $account_number;
    public $version;
    public $latestVersion;

    function __construct($db){
        $this->conn = $db;
        $this->table_name = "users";
    }

    function create(){
        // query to insert record
        $query = "INSERT INTO {$this->table_name} SET UserName = :user_name, accountNo = :accountNo, version = :version, latestVersion = :latestVersion";

        // prepare query
        $request = $this->conn->prepare($query);

        // sanitize
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->account_number = htmlspecialchars(strip_tags($this->account_number));
        $this->version = htmlspecialchars(strip_tags($this->version));
        $this->latestVersion = htmlspecialchars(strip_tags($this->latestVersion));

        // bind values
        $request->bindParam(":user_name", $this->user_name);
        $request->bindParam(":accountNo", $this->account_number);
        $request->bindParam(":version", $this->version);
        $request->bindParam(":latestVersion", $this->latestVersion);

        // execute query
        if($request->execute()){
            return true;
        }

        return false;
    }

    function update(){
        // query to insert record
        $query = "UPDATE {$this->table_name} SET UserName = :user_name, accountNo = :accountNo, version = :version, latestVersion = :latestVersion WHERE accountNo = :old_account AND UserName = :user_name";

        // prepare query
        $request = $this->conn->prepare($query);

        // sanitize
        $this->old_account = htmlspecialchars(strip_tags($this->old_account));
        $this->user_name = htmlspecialchars(strip_tags($this->user_name));
        $this->account_number = htmlspecialchars(strip_tags($this->account_number));
        $this->version = htmlspecialchars(strip_tags($this->version));
        $this->latestVersion = htmlspecialchars(strip_tags($this->latestVersion));

        // bind values
        $request->bindParam(":old_account", $this->old_account);
        $request->bindParam(":user_name", $this->user_name);
        $request->bindParam(":accountNo", $this->account_number);
        $request->bindParam(":version", $this->version);
        $request->bindParam(":latestVersion", $this->latestVersion);

        // execute query
        if($request->execute()){
            return true;
        }

        return false;
    }
}