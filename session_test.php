<?php

session_start();

if (isset($_SESSION["user_id"])) {

    echo "User ID: " . $_SESSION["user_id"] . "<br>";
    echo "User Name: " . $_SESSION["user_name"] . "<br>";
    echo "Role: " . $_SESSION["role"];

} else {

    echo "No user is logged in!";
}