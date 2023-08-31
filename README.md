# Systems
Thusday[systems]
## Getting Started

###  How to start
Create nginx file
First create directory nginx
``` sh
mkdir nginx/conf.d
```
Create configuration file
``` sh
vim nginx/conf.d/default.conf
```
Create compose.yml
``` sh
vim compose.yml
```
Create public directory
``` sh
mkdir public
```
Create PHP file 
``` sh
vim public/shukudai.php
```
Project root of repository, following command
``` sh
docker compose up
```
### Create Table 
Connecting to the MySQL server in the Docker container
``` sh
docker compose exec mysql mysql linh
```
MySQL client,execute the following SQL to create a table
``` sh
 CREATE TABLE 'book' (
`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `tilte` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP
);
ALTER TABLE `book` ADD COLUMN image_filename TEXT DEFAULT NULL;
```
### Access from browser
You can access the bulletin board from your browser with the following URL.
``` sh
http://ec2-44-204-75-62.compute-1.amazonaws.com/shukudai.php
``` 





    

