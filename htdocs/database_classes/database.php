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
        $sql = "CALL pr_BookFlight (:customerID,:journeyID,@bookingID)";
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

}
