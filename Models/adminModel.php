<?php

function getAllCategories() {
    return selectQuery(array('*'), ' categories');
    /*dd($s);*/
}

function getOrders($status) {
    return selectQuery(array('*'), ' orders', 'status = "' . $status . '"');
}

function getOrderInfo($id) {
	return selectQuery(array('*'),'orders', 'id = '.$id);
}

function moveOrder($status) {
	$id = $_POST['id'];
	$data = array('status' => $status);
	return updateQuery('orders', $data, 'id = '.$id);
}

function changePaidStatus() {
	$id = $_POST['id'];
	$data = array('paid' => 1);
	return updateQuery('orders', $data, 'id = '.$id);
}

function changeCategoryName() {
	$name =  htmlspecialchars($_POST['newName'], ENT_QUOTES);
	$id = $_POST['id'];
	$data = array('name' => $name);

	return updateQuery('categories', $data, 'id = '.$id);
}

function changeCategoryURL() {
	$name =  htmlspecialchars($_POST['newURL'], ENT_QUOTES);
	$id = $_POST['id'];
	$data = array('url' => $name);

	return updateQuery('categories', $data, 'id = '.$id);
}

function deleteCategory() {
	$id = $_POST['id'];
	//dd($id);
	deleteQuery('categories', 'id = '.$id);
	//$pics = PATH_TO_DELETE;
	//$imgs_src = selectQuery(array('img_src'), 'products', 'category_id = '.$id);
	//dd($imgs_src);
	deleteQuery('products', 'category_id = '.$id );
}

function hide_showCategory($display) {
	$id = $_POST['id'];
	$data = array('display' => $display);
	return updateQuery('categories', $data, 'id = '.$id);
}

function getProductsFromCat($idCategory = null){
	if ($idCategory) {
		return selectQuery(array('*'), 'products', 'category_id ='.$idCategory);
	}
	else return selectQuery(array('*'), 'products');
}

function getIdFromUrlCat($url) {
	//dd($url);
	$idCategory = selectQuery(array('id'), ' categories', 'url = "'.$url.'"');
	//dd($idCategory[0]['id']);
	return $idCategory[0]['id'];
}

function addProduct() {
	$name = htmlspecialchars($_POST['title'], ENT_QUOTES);
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES);
	$categoryId = intval($_POST['categoryId']);
	$price = floatval($_POST['price']);
	$image = $_FILES['file']['tmp_name'];

	$type = exif_imagetype($image);
	//dd($type);
	if(in_array($type, array(1,2,3))){
		if($type = 1) $picName = md5($image) . '.gif';
		if($type = 2) $picName = md5($image) . '.jpg';
		if($type = 3) $picName = md5($image) . '.png';
		move_uploaded_file($image, PATH_TO_SAVE . DIRECTORY_SEPARATOR . $picName );
		$imageToDB = '/images/' . $picName;
	} else {
        $imageToDB = '/images/';
    }

	if ($_POST['display'] == 'on') {
		$display = '1';
	}
	else $display = '0';

	if ($_POST['on_main'] == 'on') {
		$onMain = '1';
	}
	else $onMain = '0';
	$data = array('id' => 'NULL', 'title' => $name, 'img_src' => $imageToDB, 'price' => $price, 'description' => $description, 'category_id' => $categoryId, 'on_main' => $onMain, 'display' => $display);
	insertQuery('products', $data);
	//echo 'Продукт успешно добавлен в БД';
	//dd($image);
    header("Location: /admin/products/");
    die();
}

function deleteProduct() {
	$id = $_POST['iD'];
	//dd($id);
	$pic = PATH_TO_DELETE;
	$img_src = selectQuery(array('img_src'), 'products', 'id = '.$id);
	//dd($pic);
	$pic .= $img_src[0]["img_src"];
	//dd($pic);
	unlink($pic);
	return deleteQuery('products', 'id = '.$id);
	//dd($id);
}

function hide_showProduct($display) {
	$id = $_POST['id'];
	$data = array('display' => $display);
	return updateQuery('products', $data, 'id = '.$id);
}

function hide_showProductOnMain($on_main) {
	$id = $_POST['id'];
	$data = array('on_main' => $on_main);
	return updateQuery('products', $data, 'id = '.$id);
}

function getProductInfo($id) {
	return selectQuery(array('*'),'products', 'id = '.$id);
}

function getCategoryNameById($category_id) {
	$name = selectQuery(array('name'), 'categories', 'id = '.$category_id);
	return $name[0]['name'];
}

function productChange() {
	$id = intval($_POST['id']);
	$name = htmlspecialchars($_POST['title'], ENT_QUOTES);
	$description = htmlspecialchars($_POST['description'], ENT_QUOTES);
	$categoryId = intval($_POST['categoryId']);
	$price = floatval($_POST['price']);
	$image = $_FILES['file']['tmp_name'];
	$type = exif_imagetype($image);
	//dd($type);
	if(in_array($type, array(1,2,3))){
		if($type = 1) $picName = md5($image) . '.gif';
		if($type = 2) $picName = md5($image) . '.jpg';
		if($type = 3) $picName = md5($image) . '.png';
		move_uploaded_file($image, PATH_TO_SAVE . DIRECTORY_SEPARATOR . $picName );
	}

	if ($_POST['display'] == 'on') {
		$display = '1';
	}
	else $display = '0';

	if ($_POST['on_main'] == 'on') {
		$onMain = '1';
	}
	else $onMain = '0';
	$data = array('id' => $id, 'title' => $name, 'img_src' => '/images/' . $picName, 'price' => $price, 'description' => $description, 'category_id' => $categoryId, 'on_main' => $onMain, 'display' => $display);
	//dd($data);
	updateQuery('products', $data, 'id = '.$id);
	header("Location: /admin/products/");
}