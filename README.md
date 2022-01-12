# userpoints

<h2>User Points</h2>

<h3>Requirement:</h3>

Please make sure you have the below environment set up.  
PHP version 7 or higher
Mysql or similar db
Apache or a all included 

Clone the files to the folder in your server.
Configure the db in config/Database.php
Run the db.sql included
Now you are ready to use the api.

For front end you will have to edit the apli url to where you host in the js/main.js file.

<h2>API End Points : </h2>
<h3>GET users/</h2> 
Get all users
<b>Response</b>  
Sample response  

[
    {
        "id": "37",
        "name": "test 1",
        "age": "20",
        "address": "address 1",
        "points": "6"
    },
]    

<h3>GET users/:id</h2> 
Get the user with the id
<b>Response</b>
Sample response

{
    "id": "id",
    "name": "test 1",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>POST users/</h2> 
Create user
<b>Request format</b> 
Sample request

{
    "name": "test 1",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<b>Response</b>
Create user will be returned

Sample response
{
    "id": "38",
    "name": "test 1",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>PUT users/:id</h2> 
Update user
<b>Request format</b> 
Sample request

{
   "id" :"id"
    "name": "Update Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<b>Response</b>
Updated user will be returned

Sample response
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>PATCH users/:id</h2> 
Update some user details
<b>Request format</b> 
Sample request

{
   "id" :"id"
    "name": "Update Name",
}

<b>Response</b>
Patched user will be returned

Sample response
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>DELETE users/:id</h2> 
Deleted the user
<b>Request format</b> 

<b>Response</b>
User will be removed

<h3>PATCH users/:id/increment</h2> 
Will increment the point by one and return the user
<b>Request format</b> 
Sample request

{
    "id": "38",
}

<b>Response</b>
Sample response
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>PATCH users/:id/decrement</h2> 
Will decrement the point by one and return the user
<b>Request format</b> 
Sample request

{
    "id": "38",
}

<b>Response</b>
Sample response
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}




    


