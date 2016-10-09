<?php


if(isset($_GET['file']) && !empty($_GET['file'])){
    $fileName = htmlspecialchars($_GET['file']);
    if(file_exists($fileName)){
       show_source($fileName);
    }
}