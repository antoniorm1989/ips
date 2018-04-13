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

	$sqlWhere = "1=1";
	
	if($_GET["category"] != "all"){
		$sqlWhere = $sqlWhere." AND id_cto_product_category = ".$_GET["category"];
	}
	
	if($_GET["brand"] != "all"){
		$sqlWhere = $sqlWhere." AND id_cto_brand = ".$_GET["brand"];
	}

	$sql = "SELECT p.id_product, p.name, p.price, p.low_price,p.part_number, p.max_sale, p.stock, p.pounds, p.ounces, p.length, p.width, p.height, p.id_cto_brand, p.id_cto_product_category FROM innopbbl_tienda.product as p WHERE ".$sqlWhere;
	
	$result_json = "[";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row

	    while($row = $result->fetch_assoc()) {

	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"max_sale\":\"".$row["max_sale"]."\",\"stock\":\"".$row["stock"]."\",\"part_number\":\"".$row["part_number"]."\",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\",\"id_cto_product_category\":\"".$row["id_cto_product_category"]."\",\"id_cto_brand\":\"".$row["id_cto_brand"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}


// lista productos paginada
if($data == "paginatedProducts"){

	$sqlWhere = "1=1";
	
	if($_GET["category"] != "all"){
		$sqlWhere = $sqlWhere." AND id_cto_product_category = ".$_GET["category"];
	}
	
	if($_GET["brand"] != "all"){
		$sqlWhere = $sqlWhere." AND id_cto_brand = ".$_GET["brand"];
	}
	
	$sql = "SELECT p2.part_number, p2.id_product, p2.name, p2.price, p2.low_price, p2.max_sale, p2.stock, p2.c_name, p2.pounds, p2.ounces, p2.length, p2.width, p2.height, p2.c_description, b.name as b_name, b.description as b_description  FROM (SELECT p.id_product, p.name, p.price, p.low_price,p.part_number, p.max_sale, p.stock, p.pounds, p.ounces, p.length, p.width, p.height, p.id_cto_brand, c.name as c_name, c.description as c_description FROM (SELECT * FROM innopbbl_tienda.product WHERE ".$sqlWhere.") as p INNER JOIN innopbbl_tienda.cto_product_category as c ON p.id_cto_product_category = c.id_cto_product_category) as p2 INNER JOIN innopbbl_tienda.cto_brand as b ON p2.id_cto_brand = b.id_brand LIMIT ".$_GET["currentPage"] * $_GET["productsByPage"].", ".$_GET["productsByPage"].";";
	
	$result_json = "[";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row

	    while($row = $result->fetch_assoc()) {
		
				$result_json_photo = "[";
				$sqlPhotos = "SELECT json_data as json_data FROM innopbbl_tienda.photo WHERE id_product = ".$row["id_product"]." LIMIT 1;";
				$resultPhotos = $conn->query($sqlPhotos);

				if ($resultPhotos->num_rows > 0) {
					while($rowPhoto = $resultPhotos->fetch_assoc()) {
						$result_json_photo = $result_json_photo.$rowPhoto["json_data"].",";
					}
					$result_json_photo = substr($result_json_photo, 0, -1)."]";

				}else{
					$result_json_photo = "[]";
				}

	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"category_name\":\"".$row["c_name"]."\", \"max_sale\":\"".$row["max_sale"]."\",\"stock\":\"".$row["stock"]."\",\"category_description\":\"".$row["c_description"]."\",\"brand_name\":\"".$row["b_name"]."\",\"brand_description\":\"".$row["b_description"]."\",\"part_number\":\"".$row["part_number"]."\",\"photos\":".$result_json_photo.",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

if($data == "productoDetalle"){
	$sql = "SELECT p2.part_number, p2.id_product, p2.name, p2.price, p2.low_price, p2.description, p2.max_sale, p2.stock, p2.pounds, p2.ounces, p2.length, p2.width, p2.height, p2.short_description, p2.c_name, p2.c_description, b.name as b_name, b.description as b_description  FROM (SELECT p.part_number, p.id_product, p.name, p.price, p.low_price, p.description, p.max_sale, p.stock, p.pounds, p.ounces, p.length, p.width, p.height,p.short_description, p.id_cto_brand, c.name as c_name, c.description as c_description FROM innopbbl_tienda.product as p INNER JOIN innopbbl_tienda.cto_product_category as c ON p.id_cto_product_category = c.id_cto_product_category and p.id_product=".$_GET["productId"].") as p2 INNER JOIN innopbbl_tienda.cto_brand as b ON p2.id_cto_brand = b.id_brand;";
	
	$result_json = "[";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {

			$result_json_photo = "[";
			$sqlPhotos = "SELECT json_data as json_data FROM innopbbl_tienda.photo WHERE id_product = ".$row["id_product"].";";
			$resultPhotos = $conn->query($sqlPhotos);

			if ($resultPhotos->num_rows > 0) {
				while($rowPhoto = $resultPhotos->fetch_assoc()) {
					$result_json_photo = $result_json_photo.$rowPhoto["json_data"].",";
				}
				$result_json_photo = substr($result_json_photo, 0, -1)."]";
			}else{
				$result_json_photo = "[]";
			}

	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\",\"description\":\"".addslashes($row["description"])."\", \"category_name\":\"".$row["c_name"]."\", \"max_sale\":\"".$row["max_sale"]."\",\"stock\":\"".$row["stock"]."\",\"short_description\":\"".addslashes($row["short_description"])."\",\"category_description\":\"".$row["c_description"]."\",\"brand_name\":\"".$row["b_name"]."\",\"brand_description\":\"".$row["b_description"]."\",\"part_number\":\"".$row["part_number"]."\",\"photos\":".$result_json_photo.",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\"},";
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
	        $result_json = $result_json."{\"id_cto_product_category\":\"".$row["id_cto_product_category"]."\",\"name\":\"".$row["name"]."\",\"description\":\"".$row["description"]."\"},";
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
	        $result_json = $result_json."{\"id_cto_brand\":\"".$row["id_brand"]."\",\"name\":\"".$row["name"]."\",\"description\":\"".$row["description"]."\"},";
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