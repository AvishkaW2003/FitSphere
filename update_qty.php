<?php
session_start();

if(isset($_POST['id'], $_POST['action'])){
    $id = $_POST['id'];
    $action = $_POST['action'];

    if(isset($_SESSION['cart'][$id])){
        if($action === 'plus'){
            $_SESSION['cart'][$id]['qty'] += 1;
        } elseif($action === 'minus' && $_SESSION['cart'][$id]['qty'] > 1){
            $_SESSION['cart'][$id]['qty'] -= 1;
        }
    }
}

header('Location: cart.php');
exit;
