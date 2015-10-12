# cloaca
Analysis of Course Accreditation for the OLC

## Dependencies

* Composer, the PHP dependency manager
* The Symfony framework

## Installing

* Make sure that `app/logs` and `app/cache` are writeable.
* Copy `app/config/security_default.yml` to `app/config/security.yml` and replace the included password with something more secure.
* Create `app/config/parameters.yml` containing Symfony configurations. It could look like this:
```
parameters:
    database_driver: pdo_mysql
    database_host: 127.0.0.1
    database_port: null
    database_name: symfony
    database_user: root
    database_password: changethis 
    mailer_transport: smtp
    mailer_host: 127.0.0.1
    mailer_user: null
    mailer_password: null
    locale: en
    secret: changethisaswell
```
* Call `composer install` to install external dependencies and set up the configuration.
* Run `php app/console doctrine:schema:update --force` to update the database structure. If you are running this in a setup where you do not have direct access to the MySQL server, run `php app/console doctrine:schema:update --dump-sql` and manually perform the resulting SQL queries.

## How to use

Cloaca expects the following in `web/data`:
* A directory `new` containing folders for each course, named according to their course code. The contents of these folders is explained below.
* A directory `old` containing similar folders for last year.
* A `mapping.csv` file that specifies new courses and courses that had their course ID changed. This file is identical to the one required by the [vakkenranking](https://github.com/wassasin/vakkenranking) project.
* A `grades.csv` file listing the grade details per course, as produced by the [vakkenranking](https://github.com/wassasin/vakkenranking) project.

The course folders inside `new` and `old` should contain two folders, `Studentenevaluaties` and `Docentenevaluaties`. All files contained in these folders are listed on the evaluation forms, for the benefit of the evaluator. There needs to be at least one .xls file in `Studentenevaluaties` that adheres to the regex listed in the [UpdateController](src/Cloaca/EvaluationBundle/Controller/UpdateController.php). This is the typical format of student evaluation files exported from the faculty system.

Prior to starting a new evaluation round, the database may still contain data from previous evaluations. This should be truncated. Note that it is sometimes necessary to explicitly disable foreign key checks when truncating the tables. This can be achieved as follows:

```
SET foreign_key_checks = 0;
TRUNCATE `course`;
TRUNCATE `evaluation`;
TRUNCATE `grade`;
SET foreign_key_checks = 1;
```

The database can then be repopulated using the UpdateController, typically called by accessing `/web/update` in your browser.

## Common problems

Cloaca deals with files produced (at least partially) by humans. This means that things may change, break or otherwise fail.

* The course names are derived from the student evaluation xls files. These names are not always identical to the course names listed in the prospectus. If the names listed in the overview contain errors, correct these by renaming the xls file in the `Studentenevaluaties` directory of that course.

* Make sure that the user that is running the PHP code can actually read the contents of the `web/data` directory. When uploading files to the CnCZ server, change the permissions on all subfolders to 755.
