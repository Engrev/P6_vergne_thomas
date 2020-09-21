# P6_vergne_thomas [![Codacy Badge](https://app.codacy.com/project/badge/Grade/832b806da4a0490d9fd76c101b1bdaaf)](https://www.codacy.com/manual/Engrev/P6_vergne_thomas?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Engrev/P6_vergne_thomas&amp;utm_campaign=Badge_Grade)
 
Require :
* Development environment
* Apache server 2.2.31
* PHP >= 7.2
* MySQL 5.7.24

Get started :
* Clone the repository with : `git clone https://github.com/Engrev/P6_vergne_thomas.git`.
* Make a `composer install` and `yarn install` in the project folder.
* Modify `MAILER_DSN` and `DATABASE_URL` in `.env` file.
* Make a `php bin/console doctrine:migrations:migrate` in the console in the projet folder to create database.
* Make a `php bin/console doctrine:fixtures:load` in the console in the project folder to create the first user.
* Go to the root of the project on your browser (the root have to redirect in the public folder of the project folder).