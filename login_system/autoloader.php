<?php
    spl_autoload_register(function ($class_name) {
        include_once "classes/class." . $class_name . ".php";
    });