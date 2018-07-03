# Book-Api
Spider Webdev Task 3

## Features 

* App with secure login and sign up options.
* Users can add a book to their library/bookshelf, mark it as favorite, like the book, mark a book as ‘Want To Read’, ‘Currently Reading’ or ‘Finished Reading’.
* A user’s profile page have the following - user’s favorite books, books liked, books in
the user’s bookshelves, books that the user is currently reading and the activity of the
user.
* A search bar is provided in the home/profile page where the user can search books by Title,
Author, Publisher, ISBN or subject.
* Asynchronous Instant Searching. Users will be able to see the search suggestions changing as the user types (without refreshing/ pressing the search button).
* Facebook sharing implemented. (Not Fully)

----

**Framework used : PHP on Apache**  
**Database 	 : MySQL**  
**Server	 : Apache** 

----

**Connections to database**
* Enter your username and password of mySQL database in connect.php
```html
define ('DB_USER','Your-Username');
```
```html
define ('DB_PASSWORD','Your-Password');
```
replace the string "Your-Username" and "Your-Password" with your own username and password of mySQL database.

----

**Captcha System**

* The signup/register page uses Google reCaptcha to prevent bot users.
* Go to [this link](https://www.google.com/recaptcha/intro/index.html). Click on *get reCaptcha* button in top right corner.
* Sign in through your Gmail account.(If you are already signed up, then ignore this step).
* In the *Register a new site* box, type in a label(say localhost) and your domain name(say localhost). 
* Click on *Register*.
* You will get two keys, a public key and a private key.
* Copy the public key,private key. Open register.php. You will see a line 
```html
<div class="g-recaptcha" data-sitekey="Your-public-key"></div>
```
Paste this public key in the 'data-sitekey' attribute,replacing "Your-public-key".
Paste the private key in initialization line.(register.php)

----

#### How to run :

* Clone/download this repository.
* Copy the folder Books to your localhost directory.
* Start your XAMPP/WAMP or any apache distribution software.
* Start your apache server and mySQL modules.
* Open up your browser. Type http://localhost/Books/ as the URL.
* The App is ready to go.

----

## Built With

* [PHP](http://php.net/)
* [Vanilla JS](http://vanilla-js.com/)
* [AJAX](https://developer.mozilla.org/en-US/docs/Web/Guide/AJAX)
* [HTML](https://www.w3.org/html/)
* [CSS](https://www.w3.org/Style/CSS/)
* [Google Books API](https://developers.google.com/books/)
* [reCaptcha API](https://www.google.com/recaptcha/)
