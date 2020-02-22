<?php

class Database
{
	public $DB_SERVER;
	public $DB_USER;
	public $DB_PASSWORD;
	public $DB_DATABASE;
	
	public function __construct()
	{
	    # set the connection variables
		$this->DB_SERVER = '???';
		$this->DB_USER = '???';
		$this->DB_PASSWORD = '???';
		$this->DB_DATABASE = '???';
	}

	public function getConnection()
	{
		$dataSourceName = 'mysql:dbname='.$this->DB_DATABASE.';host='.$this->DB_SERVER;
		$dbConnection = null;
		try
		{
			$dbConnection = new PDO($dataSourceName, $this->DB_USER, $this->DB_PASSWORD);
		}
		catch(PDOExecption $err)
		{
			echo 'Connection failed: ', $err->getMessage();
		}
        $dbConnection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
		return $dbConnection;
	}

	

    # book a flight and get resulting bookingID, if no seats = 0
    public function bookFlight($customerId, $journeyID)
    {
        $connection = $this->getConnection();

        # call customer login procedure
        $sql = "CALL pr_bookFlight (:customerID,:journeyID,@bookingID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':customerId',$orderId);
		$statement->bindValue(':journeyId',$orderId);
        $statement->execute();

        # get output from procedure
        $sql = "SELECT @bookingID";
        $statement = $connection->query($sql);
        $row = $statement->fetch(PDO::FETCH_NUM);
        $bookingID = $row[0];

        $statement = null;
        $connection = null;

        return $bookingID;
    }
	
	# will return a customerId of 0 if not found, returns the hashes password and customerId for an email address
    public function customerLogin($email)
    {
        $connection = $this->getConnection();

        # call customer login procedure
        $sql = "CALL pr_customerLogin (:email,@hashedPassword,@customerId)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':email',$email);
        $statement->execute();

        # get output from procedure
        $sql = "SELECT @hashedPassword,@customerId";
        $statement = $connection->query($sql);
        $row = $statement->fetch (PDO::FETCH_NUM);
        $hashedPassword = $row[0];
        $customerId = $row[1];

        $statement = null;
        $getConnection = null;

        return array($hashedPassword,$customerId);
    }

    # register a new customer, if already registered returns $results = 'already registered'
    public function registerCustomer($email,$firstName,$lastName,$pass1,$address,$postcode)
    {
        $connection = $this->getConnection();

        # call register customer procedure
        $sql = "CALL pr_registerCustomer (:email,:firstName,:lastName,:hashedPassword,:address,:postcode,@results)";
        $statement = $connection->prepare($sql);
        $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);
        $statement->bindValue(':email',$email);
        $statement->bindValue(':firstName',$firstName);
        $statement->bindValue(':lastName',$lastName);
        $statement->bindValue(':hashedPassword',$hashedPassword);
		$statement->bindValue(':address',$address);
		$statement->bindValue(':postcode',$postcode);
        $statement->execute();

        # get output from procedure
        $sql = "SELECT @results";
        $statement = $connection->query($sql);
        $row = $statement->fetch(PDO::FETCH_NUM);
        $results = $row[0];

        $statement = null;
        $getConnection = null;

        return $results;
    }

}
