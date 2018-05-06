<?php
//Bloque de conexion a Mysql
$servername = "192.185.131.29";
$username = "innopbbl_admon";
$password = "aarm1989";

$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = $_GET["data"];

// lista productos total
if($data == "allProducts"){

	$sql = "SELECT p.id_product, p.name, p.price, p.low_price,p.part_number, p.max_sale, p.stock, p.pounds, p.ounces, p.length, p.width, p.height, p.id_cto_product_category, p.id_brand, p.photos, p.short_description FROM innopbbl_tienda.product as p";

	$result_json = "[";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {

	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"max_sale\":\"".$row["max_sale"]."\",\"stock\":\"".$row["stock"]."\",\"part_number\":\"".$row["part_number"]."\",\"photos\":".$row["photos"].",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\",\"id_brand\":".$row["id_brand"].", \"id_cto_product_category\":".$row["id_cto_product_category"].",\"short_description\":\"".addslashes($row["short_description"])."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

if($data == "productoDetalle"){
	$sql = "SELECT p2.part_number, p2.id_product, p2.name, p2.price, p2.low_price, p2.description, p2.max_sale, p2.stock, p2.pounds, p2.ounces, p2.length, p2.width, p2.height, p2.short_description, p2.photos, p2.c_description, b.description as b_description  FROM (SELECT p.part_number, p.id_product, p.name, p.price, p.low_price, p.description, p.max_sale, p.stock, p.pounds, p.ounces, p.length, p.width, p.height,p.short_description, p.photos, p.id_brand, c.description as c_description FROM innopbbl_tienda.product as p INNER JOIN innopbbl_tienda.cto_product_category as c ON p.id_cto_product_category = c.id_cto_product_category and p.id_product=".$_GET["productId"].") as p2 INNER JOIN innopbbl_tienda.cto_brand as b ON p2.id_brand = b.id_brand;";
	
	$result_json = "[";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\",\"description\":\"".addslashes($row["description"])."\", \"max_sale\":\"".$row["max_sale"]."\",\"stock\":\"".$row["stock"]."\",\"short_description\":\"".addslashes($row["short_description"])."\",\"category_description\":\"".$row["c_description"]."\",\"brand_description\":\"".$row["b_description"]."\",\"part_number\":\"".$row["part_number"]."\",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\",\"photos\":".$row["photos"]."},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

if($data == "categoria"){
	$sql = "SELECT * FROM innopbbl_tienda.cto_product_category";
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_cto_product_category\":\"".$row["id_cto_product_category"]."\",\"description\":\"".$row["description"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[]";
	}
}

if($data == "brand"){
	$sql = "SELECT * FROM innopbbl_tienda.cto_brand";
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_brand\":\"".$row["id_brand"]."\",\"description\":\"".$row["description"]."\",\"photos\":".$row["photos"].",\"website\":\"".$row["website"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[]";
	}
}

if($data == "productStock"){

	$sql = "SELECT stock as stock FROM innopbbl_tienda.product WHERE id_product = ".$_GET["id_product"].";";
	
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row

	    while($row = $result->fetch_assoc()) {
	        echo $row["stock"];	   
	    }

	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}


$conn->close();
?>