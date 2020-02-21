CREATE TABLE Customer
(
	CustomerID INT AUTO_INCREMENT UNIQUE NOT NULL,
	CustomerFirstName VARCHAR(20) NOT NULL,
	CustomerLastName VARCHAR(20) NOT NULL,
	CustomerEmail VARCHAR(50) UNIQUE NOT NULL,
	CustomerPassword VARCHAR(255) NOT NULL,
	CustomerAddress VARCHAR(100) NOT NULL,
	CustomerPostcode VARCHAR(10) NOT NULL,
	CONSTRAINT pk_Customer PRIMARY KEY (CustomerId)
);

CREATE TABLE FlightPlan
(
	FlightPlanID INT AUTO_INCREMENT UNIQUE NOT NULL,
	FlightPlanOrigin VARCHAR(20) NOT NULL,
	FlightPlanDestination VARCHAR(20) NOT NULL,
	CONSTRAINT pk_FlightPlan PRIMARY KEY (FlightPlanID)
);

CREATE TABLE Journey
(
	JourneyId INT AUTO_INCREMENT NOT NULL,
	JourneyStartDate DATE NOT NULL,
	JourneyEndDate DATE NOT NULL,
	JourneyAvailableSeats INT NOT NULL,
	FlightPlanID INT NOT NULL,
	CONSTRAINT fk_Journey_FlightPlanID FOREIGN KEY (FlightPlanID) REFERENCES FlightPlan(FlightPlanID),
	CONSTRAINT pk_Journey PRIMARY KEY (JourneyId)
);

CREATE TABLE Booking
(
	BookingID INT AUTO_INCREMENT NOT NULL,
	BookingPaid CHAR(1) NOT NULL DEFAULT 'n' CHECK (BookingPaid IN('y','n')),
	JourneyID INT NOT NULL,
	CustomerID INT NOT NULL,
	CONSTRAINT fk_Booking_JourneyID FOREIGN KEY (JourneyID) REFERENCES Journey(JourneyID),
	CONSTRAINT fk_Booking_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
	CONSTRAINT pk_Booking PRIMARY KEY (BookingID)
);

-- book a journey, returns bookingID, if no seats left returns bookingID = 0

DELIMITER //

CREATE PROCEDURE pr_BookFlight(p_customerID INT, p_journeyID INT, OUT p_bookingID INT)
BEGIN
	DECLARE v_journeyAvailableSeats INT;
	SELECT JourneyAvailableSeats INTO v_journeyAvailableSeats FROM Journey WHERE JourneyID = p_journeyID;
	IF v_journeyAvailableSeats > 0
	THEN
		INSERT INTO Booking(CustomerID,JourneyID)
		VALUES(p_customerID, p_journeyID)
		SET p_bookingID = @@IDENTITY;
	ELSE
		SET p_bookingID = 0;
	END IF;
END;

//

DELIMITER ;

-- procedure takes email and returns hashed password and customerId, if no matching email returns customerId = 0
DELIMITER //

CREATE PROCEDURE pr_customerLogin (p_email VARCHAR(50),OUT p_hashedPassword VARCHAR(100),OUT p_customerId INT)
BEGIN
	SELECT customerId INTO p_customerId FROM Customer WHERE customerEmail = p_email;
	SELECT CustomerPassword INTO p_hashedPassword FROM Customer WHERE customerEmail = p_email;
	IF NOT EXISTS (SELECT customerId FROM Customer WHERE customerEmail = p_email)
	THEN
		SET p_customerId = 0;
	END IF;
END;

//

DELIMITER ;

-- procedure takes email, firstName, lastName, hashedPassword; return results = 'registered' or 'already registered'
DELIMITER //

CREATE PROCEDURE pr_registerCustomer(p_email VARCHAR(50),p_firstName VARCHAR(20),p_lastName VARCHAR(20),p_hashedPassword VARCHAR(100),p_address VARCHAR(100),p_postcode VARCHAR(10),OUT p_results VARCHAR(20))
BEGIN
	IF EXISTS (SELECT CustomerEmail FROM Customer WHERE CustomerEmail = p_email)
	THEN
		SET p_results = 'already registered';
	ELSE
		INSERT INTO Customer(CustomerEmail,CustomerFirstName,CustomerLastName,CustomerPassword,CustomerAddress,CustomerPostcode)
		VALUES(p_email,p_firstName,p_lastName,p_hashedPassword,p_address,p_postcode);
		SET p_results = 'registered';
	END IF;
END;

//

DELIMITER ;

-- view all available flights
CREATE VIEW vw_availableFlights AS
SELECT FlightPlanOrigin,FlightPlanDestination,JourneyStartDate,JourneyEndDate,JourneyAvailableSeats
FROM FlightPlan,Journey
WHERE FlightPlan.FlightPlanID = Journey.FlightPlanID
AND JourneyAvailableSeats > 0
AND JourneyStartDate >= CURRENT_DATE()
