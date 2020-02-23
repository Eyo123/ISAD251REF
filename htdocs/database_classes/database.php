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
        $this->DB_SERVER = 'localhost';
        $this->DB_USER = 'marc';
        $this->DB_PASSWORD = 'kimchi';
        $this->DB_DATABASE = 'flightcrew';
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
    public function bookFlight($customerId, $journeyId)
    {
        $connection = $this->getConnection();

        # call customer login procedure
        $sql = "CALL pr_bookFlight (:customerID,:journeyID,@bookingID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':customerID',$customerId);
		$statement->bindValue(':journeyID',$journeyId);
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
    public function registerCustomer($email,$firstName,$lastName,$pass1,$address1,$address2,$postcode)
    {
        $connection = $this->getConnection();

        # call register customer procedure
        $sql = "CALL pr_registerCustomer (:email,:firstName,:lastName,:hashedPassword,:address1,:address2,:postcode,@results)";
        $statement = $connection->prepare($sql);
        $hashedPassword = password_hash($pass1, PASSWORD_DEFAULT);
        $statement->bindValue(':email',$email);
        $statement->bindValue(':firstName',$firstName);
        $statement->bindValue(':lastName',$lastName);
        $statement->bindValue(':hashedPassword',$hashedPassword);
		$statement->bindValue(':address1',$address1);
        $statement->bindValue(':address2',$address2);
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

    public function searchFlights($origin,$destination,$date)
    {
        $connection = $this->getConnection();

        # call items flight search procedure
        $sql = "CALL pr_searchFlights (:origin,:destination,:date)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':origin',$origin);
        $statement->bindValue(':destination',$destination);
        $statement->bindValue(':date',$date);
        $statement->execute();

        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = null;
        $connection = null;

        return $rowSet;
    }

    public function bookings($customerId)
    {
        $connection = $this->getConnection();

        # call items flight search procedure
        $sql = "CALL pr_bookings (:customerID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':customerID',$customerId);
        $statement->execute();

        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = null;
        $connection = null;

        return $rowSet;
    }

    public function vw_availableFlights()
    {
        $connection = $this->getConnection();
        $sql = "SELECT * FROM vw_availableFlights";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $statement = null;
        $connection = null;

        return $rowSet;
    }
}
