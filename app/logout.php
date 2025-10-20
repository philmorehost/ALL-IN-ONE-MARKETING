<?php
require_once 'functions.php';

session_destroy();
redirect('../public/login.php');
