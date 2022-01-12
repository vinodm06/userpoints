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

<h2>API End Points : </h2><br />
<h3>GET users/</h2> <br />
Get all users
<b>Response</b>  <br />
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

<h3>GET users/:id</h2> <br />
Get the user with the id
<b>Response</b><br />
Sample response

{
    "id": "id",
    "name": "test 1",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>POST users/</h2> <br />
Create user<br />
<b>Request format</b> <br />
Sample request<br />

{
    "name": "test 1",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<b>Response</b><br />
Create user will be returned<br />

Sample response<br />
{
    "id": "38",
    "name": "test 1",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>PUT users/:id</h2> <br />
Update user<br />
<b>Request format</b> <br />
Sample request<br />

{
   "id" :"id"
    "name": "Update Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<b>Response</b><br />
Updated user will be returned<br />

Sample response
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>PATCH users/:id</h2><br />
Update some user details<br />
<b>Request format</b><br />
Sample request

{
   "id" :"id"
    "name": "Update Name",
}

<b>Response</b><br />
Patched user will be returned<br />

Sample response
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>DELETE users/:id</h2><br /> 
Deleted the user<br />
<b>Request format</b><br /> 

<b>Response</b><br />
User will be removed<br />

<h3>PATCH users/:id/increment</h2><br /> 
Will increment the point by one and return the user
<b>Request format</b><br /> 
Sample request<br />

{
    "id": "38",
}

<b>Response</b><br />
Sample response<br />
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}

<h3>PATCH users/:id/decrement</h2><br /> 
Will decrement the point by one and return the user
<b>Request format</b><br /> 
Sample request<br />

{
    "id": "38",
}

<b>Response</b><br />
Sample response<br />
{
    "id": "38",
    "name": "Updated Name",
    "age": "20",
    "address": "address 1",
    "points": "6"
}




    


