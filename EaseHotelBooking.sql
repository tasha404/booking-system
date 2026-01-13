create database EASEHOTEL

use EASEHOTEL

CREATE TABLE users (
    users_id VARCHAR(12) PRIMARY KEY,
    users_name VARCHAR(50) NOT NULL UNIQUE,
    users_phone VARCHAR(10) NOT NULL,
    users_email VARCHAR(100) NOT NULL UNIQUE,
    users_password VARCHAR(255) NOT NULL
);

CREATE TABLE rooms (
    room_number VARCHAR(3) NOT NULL PRIMARY KEY,
    room_type VARCHAR(50) NOT NULL,
    price_per_night DECIMAL(10,2) NOT NULL,
    capacity INT NOT NULL,
    description VARCHAR(1000),
    is_available BIT DEFAULT 1
);

CREATE TABLE Bookings (
    Id VARCHAR(12) PRIMARY KEY,
    Users_ID VARCHAR(12) NOT NULL,
    RoomNo VARCHAR(3) NOT NULL,
    Check_In_Date DATE NOT NULL,
    Check_Out_Date DATE NOT NULL,
    Total_Price DECIMAL(10,2) NOT NULL,
    Booking_Status VARCHAR(10) DEFAULT 'pending'
        CHECK (Booking_Status IN ('pending','confirmed','cancelled')),
    Booked_At DATETIME DEFAULT GETDATE(),
    FOREIGN KEY (Users_ID) REFERENCES users(users_id),
    FOREIGN KEY (RoomNo) REFERENCES rooms(room_number)
);


INSERT INTO users (users_id, users_name, users_phone, users_email, users_password) 
VALUES ('010503101201', 'Maryam', '0752433782', 'Mary@gmail.com', 'abc426');

INSERT INTO users (users_id, users_name, users_phone, users_email, users_password) 
VALUES ('020727581819', 'Ali', '0273846582', 'Ali@gmail.com', 'ali9201');

INSERT INTO rooms (room_number, room_type, price_per_night, capacity)
VALUES ('401','Standard',60.00,3),
       ('737','Family',100.00,6);

INSERT INTO Bookings (Id, Users_ID, RoomNo, Check_In_Date, Check_Out_Date, Total_Price, Booking_Status, Booked_At) 
VALUES ('B1', '010503101201', '737', '2025-07-29', '2025-07-31', 300.00, 'confirmed', GETDATE());


SELECT * FROM users;
SELECT * FROM rooms;
SELECT * FROM Bookings;