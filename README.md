Live site: https://tmcglafl.w3.uvm.edu/cs148/live-lab5

Project Background
This is a PHP web application that I developed for one of my classes. The goal is for a user to be able to search for houses that 
are currently on the market and contact the realtor that is assigned to that particular house by filling out a form. The admin page is
only available to realtors or the owner who can login to add/update house information. Although the admin page is protected from users
outside of my school domain, go to the screenshots directory to see screenshots of some of the admin pages. Also, note that I may have lost some data
base priveleges so some pages may not work as expected. 


Code Breakdown
As mentioned before, this is a PHP application. In order to communicate with my database, I used pdo objects and used prepared SQL statements
to insert form data. I also used pdo reading objects to query information needed for each page. If you navigate to the pages with the word "form"
in them you will see how I sanitize and write the information into the database. Also, if you go to browseHouses or displayHouses you can see
examples of some of the SQL join statements that I put together.


 
