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
        $this->DB_SERVER = 'proj-mysql.uopnet.plymouth.ac.uk';
        $this->DB_USER = 'PRCO204_X';
        $this->DB_PASSWORD = 'N52Zbt5JECFQawrQ';
        $this->DB_DATABASE = 'PRCO204_X';
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

	# add a record to the audit log
	private function addAuditLogRecord($record)
	{
		$connection = $this->getConnection();
		$sql = "CALL pr_addAuditLogRecord (:record)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':record',$record);
        $statement->execute();
		
		$statement = null;
        $connection = null;
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
		
		# record in audit log
		$record = "Procedure:bookFlight CustomerID:$customerId JourneyID:$journeyId";
		$this->addAuditLogRecord($record);

        return $bookingID;
    }

    
	
	# will return a customerId of 0 if not found, returns the hashes password and customerId for an email address
    public function customerLogin($email)
    {
        $connection = $this->getConnection();

        # call customer login procedure
        $sql = "CALL pr_customerLogin (:email,@hashedPassword,@customerId,@customerFirstName,@customerLastName)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':email',$email);
        $statement->execute();

        # get output from procedure
        $sql = "SELECT @hashedPassword,@customerId,@customerFirstName,@customerLastName";
        $statement = $connection->query($sql);
        $row = $statement->fetch (PDO::FETCH_NUM);
        $hashedPassword = $row[0];
        $customerId = $row[1];
		$customerFirstName = $row[2];
		$customerLastName = $row[3];
		$customerName = $customerFirstName . " " . $customerLastName;
		
		$statement = null;
        $getConnection = null;
		
		# record in audit log
		$record = "Procedure:customerLogin Email:$email CustomerID:$customerId";
		$this->addAuditLogRecord($record);

        return array($hashedPassword,$customerId,$customerName);
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
		
		# record in audit log
		$record = "Procedure:registerCustomer Email:$email Result:$results";
		$this->addAuditLogRecord($record);

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
		
		# record in audit log
		$record = "Procedure:searchFlights Origin:$origin Destination:$destination Date:$date";
		$this->addAuditLogRecord($record);

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
		
		# record in audit log
		$record = "Procedure:bookings CustomerID:$customerId";
		$this->addAuditLogRecord($record);

        return $rowSet;
    }
	
	public function confirmedBookings($customerId)
    {
        $connection = $this->getConnection();

        # call items flight search procedure
        $sql = "CALL pr_confirmedBookings (:customerID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':customerID',$customerId);
        $statement->execute();

        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:confirmedBookings CustomerID:$customerId";
		$this->addAuditLogRecord($record);

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
		
		# record in audit log
		$record = "Procedure:vw_availableFlights";
		$this->addAuditLogRecord($record);

        return $rowSet;
    }

    public function vw_originAirports()
    {
        $connection = $this->getConnection();
        $sql = "SELECT * FROM vw_originAirports";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $airports = array();

        foreach ($rowSet as $currentAirport){
            $airports[] = $currentAirport['FlightPlanOrigin'] ;
        }

		$statement = null;
        $connection = null;

		# record in audit log
		$record = "Procedure:vw_originAirports";
		$this->addAuditLogRecord($record);

        return $airports;
    }

    public function vw_destinationAirports()
    {
        $connection = $this->getConnection();
        $sql = "SELECT * FROM vw_destinationAirports";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        $airports = array();

        foreach ($rowSet as $currentAirport){
            $airports[] = $currentAirport['FlightPlanDestination'] ;
        }

		$statement = null;
        $connection = null;

		# record in audit log
		$record = "Procedure:vw_destinationAirports";
		$this->addAuditLogRecord($record);

        return $airports;
    }
	
	# add a new flight plan
    public function addFlightPlan($flightPlanCode,$flightPlanOrigin,$flightPlanDestination)
    {
        $connection = $this->getConnection();

        # call addFlightPlan procedure
        $sql = "CALL pr_addFlightPlan (:flightPlanCode,:flightPlanOrigin,:flightPlanDestination)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':flightPlanCode',$flightPlanCode);
        $statement->bindValue(':flightPlanOrigin',$flightPlanOrigin);
        $statement->bindValue(':flightPlanDestination',$flightPlanDestination);
        $statement->execute();

		$statement = null;
        $getConnection = null;

		# record in audit log
		$record = "Procedure:addFlightPlan FlightPlanCode:$flightPlanCode FlightPlanOrigin:$flightPlanOrigin FlightPlanDestination:$flightPlanDestination";
		$this->addAuditLogRecord($record); 
    }

    	# add a new flight plan
        public function addFlightPlanPlusDistance($flightPlanCode,$flightPlanOrigin,$flightPlanDestination,$flightPlanDistance)
        {
            $connection = $this->getConnection();
    
            # call addFlightPlan procedure
            $sql = "CALL pr_addFlightPlanPlusDistance (:flightPlanCode,:flightPlanOrigin,:flightPlanDestination, :flightPlanDistance)";
            $statement = $connection->prepare($sql);
            $statement->bindValue(':flightPlanCode',$flightPlanCode);
            $statement->bindValue(':flightPlanOrigin',$flightPlanOrigin);
            $statement->bindValue(':flightPlanDestination',$flightPlanDestination);
            $statement->bindValue(':flightPlanDistance',$flightPlanDistance);
            $statement->execute();
    
            $statement = null;
            $getConnection = null;
    
            # record in audit log
            $record = "Procedure:addFlightPlan FlightPlanCode:$flightPlanCode FlightPlanOrigin:$flightPlanOrigin FlightPlanDestination:$flightPlanDestination FlightPlanDistance:$flightPlanDistance";
            $this->addAuditLogRecord($record); 
        }
	
	# add a new journey
    public function addJourney($code,$date,$departureTime,$arrivalTime,$availableSeats,$price)
    {
        $connection = $this->getConnection();

        # call addFlightPlan procedure
        $sql = "CALL pr_addJourney (:code,:date,:departureTime,:arrivalTime,:availableSeats,:price)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':code',$code);
        $statement->bindValue(':date',$date);
        $statement->bindValue(':departureTime',$departureTime);
		$statement->bindValue(':arrivalTime',$arrivalTime);
        $statement->bindValue(':availableSeats',$availableSeats);
        $statement->bindValue(':price',$price);
        $statement->execute();
		
		$statement = null;
        $getConnection = null;
		
		# record in audit log
		$record = "Procedure:addJourney Code:$code Date:$date DepartureTime:$departureTime ArrivalTime:$arrivalTime AvailableSeats:$availableSeats Price:$price";
		$this->addAuditLogRecord($record);  
    }
	
	# confirm a flight
    public function confirmFlight($bookingID)
    {
        $connection = $this->getConnection();

        # call confirmFlight procedure
        $sql = "CALL pr_confirmFlight (:bookingID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':bookingID',$bookingID);
        $statement->execute();
		
		$statement = null;
        $getConnection = null;
		
		# record in audit log
		$record = "Procedure:confirmFlight BookingID:$bookingID";
		$this->addAuditLogRecord($record);   
    }

    #cancel a booking for a flight
    public function cancelBooking($bookingID){

        $connection = $this->getConnection();

        $sql = "CALL pr_cancelBooking(:bookingID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':bookingID',$bookingID);
        $statement->execute();

		$statement = null;
        $getConnection = null;
		
		# record in audit log
		$record = "Procedure:cancelBooking BookingID:$bookingID";
		$this->addAuditLogRecord($record);  
    }
	
	public function vw_flightPlanCodes()
    {
        $connection = $this->getConnection();
        $sql = "SELECT * FROM vw_flightPlanCodes";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);
		
        $airports = array();

        foreach ($rowSet as $currentFlightPlan){
            $flightPlanCodes[] = $currentFlightPlan['FlightPlanCode'] ;
        }

		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:vw_flightPlanCodes";
		$this->addAuditLogRecord($record);

        return $flightPlanCodes;
    }
	
	public function vw_airportCodes()
    {
        $connection = $this->getConnection();
        $sql = "SELECT * FROM vw_airportCodes";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);

        foreach ($rowSet as $currentAirport){
            $airportCodes[] = $currentAirport['AirportCode'] ;
        }

		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:vw_airportCodes";
		$this->addAuditLogRecord($record);

        return $airportCodes;
    }
	
	public function vw_flightPlans()
    {
        $connection = $this->getConnection();
        $sql = "SELECT * FROM vw_flightPlans";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:vw_flightPlans";
		$this->addAuditLogRecord($record);

        return $rowSet;
    }
	
	public function vw_auditLogRecords()
	{
		$connection = $this->getConnection();
        $sql = "SELECT * FROM vw_auditLogRecords";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:vw_flightPlans";
		$this->addAuditLogRecord($record);

        return $rowSet;
	}
	
	public function vw_airports()
	{
		$connection = $this->getConnection();
        $sql = "SELECT * FROM vw_airports";
        $statement = $connection->query($sql);
        $rowSet = $statement->fetchAll(PDO::FETCH_ASSOC);
		
		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:vw_airports";
		$this->addAuditLogRecord($record);

        return $rowSet;
	}
	
	# add a new airport
    public function addAirport($code,$name,$country,$lat,$long)
    {
        $connection = $this->getConnection();

        # call addAirport procedure
        $sql = "CALL pr_addAirport (:code,:name,:country,:lat,:long)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':code',$code);
        $statement->bindValue(':name',$name);
        $statement->bindValue(':country',$country);
		$statement->bindValue(':lat',$lat);
        $statement->bindValue(':long',$long);
        $statement->execute();
		
		$statement = null;
        $getConnection = null;
		
		# record in audit log
		$record = "Procedure:addAirport Code:$code Name:$name Country:$country Latitude:$lat Longitude:$long";
		$this->addAuditLogRecord($record);
    }
	
	public function deleteCustomer($customerId)
    {
        $connection = $this->getConnection();

        # call items flight search procedure
        $sql = "CALL pr_deleteCustomer (:customerID)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':customerID',$customerId);
        $statement->execute();
		
		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:deleteCustomer CustomerID:$customerId";
		$this->addAuditLogRecord($record);
    }
	
	public function addFeedback($name,$record)
    {
        $connection = $this->getConnection();

        # call addFeedback procedure
        $sql = "CALL pr_addFeedback (:name,:record)";
        $statement = $connection->prepare($sql);
        $statement->bindValue(':name',$name);
		$statement->bindValue(':record',$record);
        $statement->execute();
		
		$statement = null;
        $connection = null;
		
		# record in audit log
		$record = "Procedure:addFeedback Name:$name";
		$this->addAuditLogRecord($record);
    }
}
