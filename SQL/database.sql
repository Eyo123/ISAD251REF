CREATE TABLE Customer
(
	CustomerID INT AUTO_INCREMENT UNIQUE NOT NULL,
	CustomerFirstName VARCHAR(20) NOT NULL,
	CustomerLastName VARCHAR(20) NOT NULL,
	CustomerEmail VARCHAR(50) UNIQUE NOT NULL,
	CustomerPassword VARCHAR(255) NOT NULL,
	CustomerAddress1 VARCHAR(100) NOT NULL,
	CustomerAddress2 VARCHAR(100) NOT NULL,
	CustomerPostcode VARCHAR(10) NOT NULL,
	CONSTRAINT pk_Customer PRIMARY KEY (CustomerId)
);

CREATE TABLE FlightPlan
(
	FlightPlanID INT AUTO_INCREMENT UNIQUE NOT NULL,
	FlightPlanCode VARCHAR(10) NOT NULL,
	FlightPlanOrigin VARCHAR(20) NOT NULL,
	FlightPlanDestination VARCHAR(20) NOT NULL,
	CONSTRAINT pk_FlightPlan PRIMARY KEY (FlightPlanID)
);

INSERT INTO FlightPlan(FlightPlanCode,FlightPlanOrigin,FlightPlanDestination)
VALUES('LN123','London','New York'),('ES456','Egypt','Switzerland');

CREATE TABLE Journey
(
	JourneyId INT AUTO_INCREMENT NOT NULL,
	JourneyDate DATE NOT NULL,
	JourneyDepartureTime VARCHAR(20) NOT NULL,
	JourneyArrivalTime VARCHAR(20) NOT NULL,
	JourneyAvailableSeats INT NOT NULL,
	JourneyPrice DECIMAL(6,2) NOT NULL,
	FlightPlanID INT NOT NULL,
	CONSTRAINT fk_Journey_FlightPlanID FOREIGN KEY (FlightPlanID) REFERENCES FlightPlan(FlightPlanID),
	CONSTRAINT pk_Journey PRIMARY KEY (JourneyId)
);

INSERT INTO Journey(JourneyDate,JourneyDepartureTime,JourneyArrivalTime,JourneyAvailableSeats,JourneyPrice,FlightPlanID)
VALUES('2020-01-12','11:00','12:00',100,200.00,1),('2020-01-14','10:00','13:00',50,300.00,2),
('2020-05-10','8:00','9:00',100,150.00,1),('2020-07-04','14:00','17:00',50,400.00,2);

CREATE TABLE Booking
(
	BookingID INT AUTO_INCREMENT NOT NULL,
	BookingStatus VARCHAR(10) NOT NULL DEFAULT 'booked' CHECK (BookingStatus IN('booked','cancelled','confirmed')),
	JourneyID INT NOT NULL,
	CustomerID INT NOT NULL,
	CONSTRAINT fk_Booking_JourneyID FOREIGN KEY (JourneyID) REFERENCES Journey(JourneyID),
	CONSTRAINT fk_Booking_CustomerID FOREIGN KEY (CustomerID) REFERENCES Customer(CustomerID),
	CONSTRAINT pk_Booking PRIMARY KEY (BookingID)
);

-- view all available flights
CREATE VIEW vw_availableFlights AS
SELECT FlightPlanOrigin,FlightPlanDestination,JourneyDate,JourneyDepartureTime,JourneyArrivalTime,JourneyAvailableSeats,FlightPlanCode,JourneyID
FROM FlightPlan,Journey
WHERE FlightPlan.FlightPlanID = Journey.FlightPlanID
AND JourneyAvailableSeats > 0
AND JourneyDate >= CURRENT_DATE()

-- book a journey, returns bookingID, if no seats left returns bookingID = 0

DELIMITER //

CREATE PROCEDURE pr_bookFlight(p_customerID INT, p_journeyID INT, OUT p_bookingID INT)
BEGIN
	DECLARE v_journeyAvailableSeats INT;
	SELECT JourneyAvailableSeats INTO v_journeyAvailableSeats FROM Journey WHERE JourneyID = p_journeyID;
	IF v_journeyAvailableSeats > 0
	THEN
		INSERT INTO Booking(CustomerID,JourneyID)
		VALUES(p_customerID, p_journeyID);
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

CREATE PROCEDURE pr_registerCustomer(p_email VARCHAR(50),p_firstName VARCHAR(20),p_lastName VARCHAR(20),p_hashedPassword VARCHAR(100),p_address1 VARCHAR(100),p_address2 VARCHAR(100),p_postcode VARCHAR(10),OUT p_results VARCHAR(20))
BEGIN
	IF EXISTS (SELECT CustomerEmail FROM Customer WHERE CustomerEmail = p_email)
	THEN
		SET p_results = 'already registered';
	ELSE
		INSERT INTO Customer(CustomerEmail,CustomerFirstName,CustomerLastName,CustomerPassword,CustomerAddress1,CustomerAddress2,CustomerPostcode)
		VALUES(p_email,p_firstName,p_lastName,p_hashedPassword,p_address1,p_address2,p_postcode);
		SET p_results = 'registered';
	END IF;
END;

//

DELIMITER ;

-- procedure takes origin, destination, date returns flights
DELIMITER //

CREATE PROCEDURE pr_searchFlights(p_origin VARCHAR(20),p_destination VARCHAR(20),p_date DATE)
BEGIN
	IF p_date < CURRENT_DATE()
	THEN
		SET p_date = CURRENT_DATE();
	END IF;
	SELECT FlightPlanCode, FlightPlanOrigin, FlightPlanDestination, JourneyDate, JourneyID
	FROM FlightPlan, Journey
	WHERE FlightPlan.FlightPlanID = Journey.FlightPlanID
	AND FlightPlanOrigin = p_origin
	AND FlightPlanDestination = p_destination
	AND JourneyAvailableSeats > 0
	AND JourneyDate = p_date;
END;

//

DELIMITER ;

-- procedure takes customerID and returns all bookings
DELIMITER //

CREATE PROCEDURE pr_bookings(p_customerID INT)
BEGIN
	SELECT FlightPlanCode, FlightPlanOrigin, FlightPlanDestination, JourneyDepartureTime, JourneyArrivalTime, JourneyDate, BookingID
	FROM FlightPlan, Journey, Booking
	WHERE FlightPlan.FlightPlanID = Journey.FlightPlanID
	AND Journey.JourneyID = Booking.JourneyID
	AND Booking.CustomerID = p_customerID
	AND JourneyDate >= CURRENT_DATE()
	AND Booking.BookingStatus = 'booked';
END;

//

