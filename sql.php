// sql for creating the buyer table
CREATE TABLE `TMCGLAFL_cs148_final`.`tblBuyer`
 ( `pmkBuyerEmail` VARCHAR(50) NOT NULL , `fldFullName` VARCHAR(75) NOT NULL ,
 `fldAgreedToTerms` TINYINT NOT NULL , PRIMARY KEY (`pmkBuyerEmail`)) E
// sql for creating house table
CREATE TABLE `TMCGLAFL_cs148_final`.`tbl` ( `pmkHouseId` INT NOT NULL AUTO_INCREMENT 
, `fldPrice` INT NOT NULL , `fldState` VARCHAR(2) NOT NULL , `fldCity` VARCHAR(50) NOT NULL
 , `fldSquareFeet` INT NOT NULL , `fldDateListed` DATE NOT NULL , `fldDateSold` DATE NULL , PRIMARY KEY (`pmkHouseId`)) ENGINE = InnoDB;
// sql for creating BuyerHouse relationship table
CREATE TABLE `TMCGLAFL_cs148_final`.`tblBuyerHouse` ( `pmkPurchaseId` INT NOT NULL AUTO_INCREMENT ,
 `fpkBuyerEmail` VARCHAR(75) NOT NULL , `fpkHouseId` INT NOT NULL , PRIMARY KEY (`pmkPurchaseId`)) ENGINE = InnoDB
// sql for creating Realtor table
CREATE TABLE `TMCGLAFL_cs148_final`.`tblRealtor` ( `pmkNetId` VARCHAR(15) NOT NULL , `fldFullName` VARCHAR(75) NOT NULL
 , `fldRealtorEmail` VARCHAR(75) NOT NULL , `fldPhoneNumber` VARCHAR(15) NOT NULL , `fldHireDate` DATE NOT NULL ,
 PRIMARY KEY (`pmkNetId`)) ENGINE = InnoDB;

