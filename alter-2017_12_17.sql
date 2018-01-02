ALTER TABLE `contracts` ADD `currency` VARCHAR(10) NOT NULL AFTER `contract_value_usd`;
ALTER TABLE `contracts` CHANGE `contract_value_bdt` `contract_value` DECIMAL(20,3) NOT NULL DEFAULT '0.000';
UPDATE `contracts` SET `currency`='BDT' WHERE contract_value!=0.000;
UPDATE contracts SET contract_value=contract_value_usd, currency='USD' WHERE contract_value_usd!=0.00;
ALTER TABLE `collection_details` ADD `planned_payment_credited_to_bank` DATE NULL DEFAULT NULL AFTER `forecasted_payment_collection_date`;

UPDATE collection_details SET planned_payment_credited_to_bank=(SELECT planned_payment_collection_date FROM collections as c WHERE c.id=collection_details.collection_id);

UPDATE `collection_details` SET forecasted_payment_collection_date=(SELECT forecasted_payment_collection_date FROM collections WHERE collections.id=collection_details.collection_id) WHERE `forecasted_payment_collection_date` IS NULL ;

 UPDATE collection_details SET payment_credited_to_bank_date=SUBSTRING(collection_details.added_date,1,10) WHERE payment_credited_to_bank_date IS NULL ;
