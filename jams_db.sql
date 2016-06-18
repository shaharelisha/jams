DROP TABLE IF EXISTS hr_orderitems;
DROP TABLE IF EXISTS hr_order;
DROP TABLE IF EXISTS hr_product;
DROP TABLE IF EXISTS hr_customer;
DROP TABLE IF EXISTS hr_newsletter;

CREATE TABLE hr_newsletter (
    email VARCHAR(255) PRIMARY KEY
);

CREATE TABLE hr_customer (
    email VARCHAR(255) PRIMARY KEY, 
    fname VARCHAR(100), 
    sname VARCHAR(100), 
    postcode VARCHAR(7), 
    pass VARCHAR(41)
);

CREATE TABLE hr_product (
    pid INT AUTO_INCREMENT PRIMARY KEY , 
    name VARCHAR(100), 
    description TEXT,
    imagepath VARCHAR(100),
    price DECIMAL(10, 2)
);

CREATE TABLE hr_order (
    oid INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    FOREIGN KEY (email) REFERENCES hr_customer(email)
);

CREATE TABLE hr_orderitems (
    oid INT,
    pid INT,
    qty INT,
    PRIMARY KEY (oid, pid),
    FOREIGN KEY (oid) REFERENCES hr_order(oid),
    FOREIGN KEY (pid) REFERENCES hr_product(pid)
);

INSERT INTO hr_product VALUES 
(NULL, 'Blackcurrant Jam (12oz)', 'Whole Ben Hope blackcurrants have been gently stirred by hand in traditional copper lined pans to create a tangy blackcurrant jam bursting with fruit and flavour.', 'Images/blackcurrant-jam.jpg', 3.25), 

(NULL, 'Raspberry Jam (12oz)', 'Dark red Wilhemette and Lulin raspberries bursting with flavour and natural sugars are gently picked by hand then stirred in traditional open copper lined pans to create a lusciously sweet and intensely delicious jam.', 'Images/raspberry-jam.jpg', 3.25), 

(NULL, 'Strawberry Jam (12oz)', 'British grown Albion strawberries from Herefordshire are gently stirred by hand in traditional open copper lined pans with Honeoye strawberries to create the perfect marriage for a lusciously rich strawberry jam.', 'Images/strawberry-jam.jpg', 3.25), 

(NULL, 'Apricot Jam (12oz)', 'Ripe velvety apricots are gently stirred by hand in traditional open copper lined pans to create an intense and smooth apricot jam.', 'Images/apricot-jam.jpg', 3.25), 

(NULL, 'Lemon Curd (12oz)', 'Our beautifully smooth and creamy lemon curd with the zing of organic Sicilian lemons is carefully made by hand in traditional open copper lined pans.', 'Images/lemon-curd.jpg', 3.25), 

(NULL, 'Orange Marmalade (12oz)', 'We finely slice the peel of tangy Seville Oranges, sun ripened in family run orchards which is then gently stirred in traditional open copper pans to create a deep and tangy marmalade.', 'Images/orange-jam.jpg', 3.25);


    