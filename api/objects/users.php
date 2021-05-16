<?php
class Users{
    private $conn;
    private $table_name;
    public $user_name;
    public $old_account;
    public $account_number;
    public $active;
    public $lictype;
    public $productCode;
    public $modifyTime;
    public $version;
    public $latestVersion;

    function __construct($db){
        $this->conn = $db;
        $this->table_name = "users";
        $this->active = 1;
        $this->lictype = "Full";
        $this->productCode = "EA2_FP";
    }

    function create(){
        $sql = "SELECT * FROM {$this->table_name} WHERE UserName = :user_name AND accountNo = :oldnumber";
        $exist = $this->conn->prepare($sql);
        $exist->bindParam(":user_name", $this->user_name);
        $exist->bindParam(":oldnumber", $this->old_account);
        $exist->execute();
        $row_count = $exist->rowCount();

        if($row_count > 0){
            $this->update();
            return true;
        }
        
        // query to insert record
        $query = "INSERT INTO {$this->table_name} SET UserName = :user_name, accountNo = :accountNo, version = :version, latestVersion = :latestVersion, active = :active, lictype = :lictype, productCode = :productCode, modifyTime = NOW()";

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
        // Default value
        $request->bindParam(":active", $this->active);
        $request->bindParam(":lictype", $this->lictype);
        $request->bindParam(":productCode", $this->productCode);

        // execute query
        if($request->execute()){
            return true;
        }

        return false;
    }

    function update(){

        $sql = "SELECT * FROM {$this->table_name} WHERE UserName = :user_name AND accountNo = :oldnumber";
        $notexist = $this->conn->prepare($sql);
        $notexist->bindParam(":user_name", $this->user_name);
        $notexist->bindParam(":oldnumber", $this->old_account);
        $notexist->execute();
        $row_count = $notexist->rowCount();

        if($row_count < 1){
            $this->create();
            return true;
        }

        // query to insert record
        $query = "UPDATE {$this->table_name} SET UserName = :user_name, accountNo = :accountNo, version = :version, latestVersion = :latestVersion, active = :active, lictype = :lictype, productCode = :productCode, modifyTime = NOW() WHERE accountNo = :old_account AND UserName = :user_name";

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

        // Default value
        $request->bindParam(":active", $this->active);
        $request->bindParam(":lictype", $this->lictype);
        $request->bindParam(":productCode", $this->productCode);

        // execute query
        if($request->execute()){
            return true;
        }

        return false;
    }
}