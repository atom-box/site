# TATLL is a URL shortener

## Introduction

If given a long URL, this app generates a shortened URL, stores it in a table, and becomes a redirecter for the new shortened URL.

## Design of the shortened URL      
I decided to not make hashes for the URLs.  My shortened URLs have a human readable mix of consonants and vowels to make them easier to remember and say.  The shortened URLs end in a two-digit suffix.  The URL shortener function checks the existing db to avoid a collision with existing shortened URLs.  There are over 500 million urls possible before running out of combinations.

## Built with
* PHP
* Apache
* MySQL
* phpunit

## Schema

Deploy notes for the database (PostgreSQL).  The app uses three tables:  

### sessions
```
CREATE TABLE IF NOT EXISTS sessions (
    session_id  serial,
    user_id     int NOT NULL,
    date        timestamp
);
```

### users
```
CREATE TABLE IF NOT EXISTS users (
    user_id     serial,
    email       varchar(255) UNIQUE NOT NULL,
    last_reset  timestamp,
    name        varchar(60),
    type        varchar(12) DEFAULT 'free',
    created     timestamp,
    password    varchar(32)
);
```

### links
```
CREATE TABLE IF NOT EXISTS links (
    link_id     serial,
    session_id  int,
    long        varchar(1000) NOT NULL,
    short       varchar(40) UNIQUE
);
```
[done]: https://user-images.githubusercontent.com/29199184/32275438-8385f5c0-bf0b-11e7-9406-42265f71e2bd.png "Done"

|               Section              | 1<br>Basics | 2<br>Works   | 3<br>Polished     | 4<br>Linted |
|:-------------------------------- |:-----------------:|:-------------:|:-------------:|:----------------:|
|**three pages of html**    |   ![done][done]     |  |   |
|**schema**           |  ![done][done]        |   ![done][done]   |  |                                  |
|**SQL**           |   ![done][done]      |  ![done][done]  |  |                                  |
|**tests**    |   ![done][done]    |  |   |                        |
|**write shortener logic (5 - 9 alphanumeric)**   |    ![done][done]    |    ![done][done]             |               |                                  |
|**Session management**         |                   |               |               |                                  |
|**CSS**         |![done][done]   |               |               |                                  |


## Apache deploy  

__/etc/apache2/apache2.conf__
```
#...
Nothing added here at all. Not even 'RewriteEngine on'
```

__/etc/apache2/sites-enabled/tatll.org.conf__  
Note: Must set up reference map outside of the directory block. Okay to say 'rewriterule' inside of block though! 
```
  GNU nano 4.8                                                                       /etc/apache2/sites-available/tatll.org.conf                                                                                  
 1 <VirtualHost *:80>
 2     ServerName tatll.org
 3     ServerAlias www.tatll.org
 4     ServerAdmin webmaster@localhost
 5     DocumentRoot /var/www/tatll.org
 6     # LogLevel: Control the severity of messages logged to the error_log.
 7     # Available values: trace8, ..., trace1, debug, info, notice, warn,
 8     LogLevel trace6
 9
10     ErrorLog ${APACHE_LOG_DIR}/error.log
11     CustomLog ${APACHE_LOG_DIR}/access.log combined
12     RewriteEngine on
13     RewriteMap pickleFlat txt:/home/evan/projects/do-not-commit/shortener.txt
14     <Directory /var/www/tatll.org>
15         Options Indexes FollowSymLinks
16         AllowOverride All
17         Require all granted
18         RewriteRule \/?(\w{6}\d{2})$ ${pickleFlat:$1} [L]
19         # note the slash is a literal char
20         # note the [L] is required
21
22         # For all requests where files and folders do not exist
23         RewriteCond %{REQUEST_FILENAME} !-d
24         RewriteCond %{REQUEST_FILENAME} !-f
25         RewriteRule . templates/404.php [L]
26     </Directory>
27 </VirtualHost>
28

```

__/var/www/tatll.org/utilities/pickleFlat.txt__
```
abcdef12 https://example.com
```

## Deploy sync
__Files to ignore__    
.gitignore    
phpunit*    
__Folders to ignore__  
.git    
vendor    
    
## todo list
[x] Write the schema  
[x] Make the Apache root directory  
[x] Config file in dev and prod and .gitignore  
[x] Start the rsync to the Apache root dir  

> run as `sudo /bin/bcompare` 
or
> sudo chown -R $USER:$USER /var/www/your_domain

[x] Serve a test page  from prod  
[x] Serve cards from php
[x] config file   
[x] Hook up the database  
[x] Basic Database Configuration.    
[x] loading the homepage pings the dataconnection to get a count query of the db  
[x] flushing css to bottom. Did this by making a wrap div    100% height    
[x] pressing button should append a stripe div, repeatedly    on the success page     
[x] Make a html with put to db; every time home page loads a    random url will be put to db   
[x] Make a html with get from db; every time home page loads    all the links retrieve and display from db   
[x] Imitate a short, existing stack of yours on first run    ('Worlds smallest LAMP')     
[x] fix NULL in session id in links   
[x] Imitate the CSS style of an admired page   
[x] Ugh, whole-project pause: reorganize code to 1 class, 3    controllers, two main templates. Make sure still works.   
[x] Make the shortener function   
[x] Pass in the long URL from form to page 2   
[x] First trivial test. Config phpunit.     
[x] Make the checker for avoiding collisions, like the robot    names? Use prepared statements per link below.   
[x] Formify everything on the first page   

[x] Make second page   
[x] ~~Configure apache to use a SQL table to do redirect~~    
[x] Configure apache to use a rewrite table TEXTFILE to do redirect     
[ ] fix function notUnique with hard coding at shortUrlToCheck 
[x] ~~Make a wrapper class around the dbTransaction for get/set the sql~~    
[x] Make newlines append to the flat file used by Apache redirect
[x] button on success page should say 'make another'   
[x] change menu bar to public always
[x] re-Enable --->public function notUnique(); make sure unit test still works
[x] use frontend validation    
g
[ ] Sanitize inputs using 12-1 from d powers book
[x] SQL statement is a prepared statement
[ ] David Powers 11-6,    
[ ] scrape new links for their favicons
[ ] Get to here by Tuesday pm
[ ] deploy to live at Digital Ocean
[ ] leverage composer libraries  (for sanitizing??)    
[ ] convert inputs to lower case
[ ] Write tests for addLinkToDbTest.php
[x] implement DBM with Apache.  DB writes to a flat file (text, mod_rewrite) 
[ ] confirm db, file errors go to logging.  Get a logger from composer. Add log path to config      
[ ] call logging in core/helpers/addToLinkages.php    
[ ] make helpers into classes, OOP
[ ] accessible 
[ ] remove unused USE statements    
[ ] Write at least three test cases in the tests/ folder.     Run php bin/phpunit    
[ ] Add a parser option to put the originals URL in as a     dubdomain.    
[ ] Composer package to avoid obscenity, blacklisted sites?    
[ ] Lint: todos.  Error printers.  Commented out code.    
[ ] Add captcha?    
[ ] Look over the actual algorithm ideas at https://    stackoverflow.com/questions/742013/    how-do-i-create-a-url-shortener     
[ ] refactor require/use/namespace everywhere, especially in     tests.  If desperate can try to use global namespace option     onn some things per https://blog.eduonix.com/    web-programming-tutorials/namespaces-in-php/      
[ ] add a number of times clicked   
[ ] add private account   
[ ] <p class="card-text">Your link is by default public but can also be saved to your private account. After shortening the URL, check how many clicks it received.</p> 
   
[ ] rightclick format document on everything or run phpcs at     BASh     


## Resources

[PDO examples for calling the db]
(https://www.php.net/manual/en/pdo.prepare.php)
[frontend validation](https://css-tricks.com/form-validation-part-1-constraint-validation-html/)