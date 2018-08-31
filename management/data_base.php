<?php
//Bloque de conexion a Mysql
$servername = "192.185.131.29";
$username = "innopbbl_admon";
$password = "aarm1989";
$conn = new mysqli($servername, $username, $password);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if($_GET["store"] == 'motorsport'){
	$bd = 'innopbbl_tienda';
}else{
	$bd = 'innopbbl_innopartserv';
}

$data = $_GET["data"];

/***
  PRODUCTS SECTION
***/

// Get All products
if($data == "getAllProducts"){
	$sql = "SELECT p2.id_product, p2.name, p2.price, p2.low_price, b.description as b_description, c_description as c_description, p2.stock, p2.part_number, p2.photos FROM (SELECT p.id_product, p.name, p.price, p.low_price, p.stock, p.part_number, p.id_brand, p.photos, c.description as c_description FROM ".$bd.".product as p INNER JOIN ".$bd.".cto_product_category as c ON p.id_cto_product_category = c.id_cto_product_category) as p2 INNER JOIN ".$bd.".cto_brand as b ON p2.id_brand = b.id_brand;";
	
	$result_json = "[";
	$result = $conn->query($sql);
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"id_category\":\"".$row["c_description"]."\", \"id_brand\":\"".$row["b_description"]."\",\"part_number\":\"".$row["part_number"]."\",\"stock\":\"".$row["stock"]."\",\"photos\":\"".addslashes($row["photos"])."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    
		echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

// Get product by Id
if($data == "getProductById"){
	
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	    
	$sql = "SELECT * FROM ".$bd.".product WHERE id_product=".$request->id_product;
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"description\":\"".addslashes($row["description"])."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"id_category\":\"".$row["id_cto_product_category"]."\", \"max_sale\":\"".$row["max_sale"]."\",\"short_description\":\"".addslashes($row["short_description"])."\",\"id_brand\":\"".$row["id_brand"]."\",\"part_number\":\"".$row["part_number"]."\",\"stock\":\"".$row["stock"]."\",\"photos\":".$row["photos"].",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

// Add new product
if($data == "create"){

    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_product= $request->id_product;
     $name= addslashes($request->name);
     $short_description = addslashes($request->short_description);
     $description = addslashes($request->description);
     $price= $request->price;
     $low_price= $request->low_price;
     $stock= $request->stock;
     $part_number= addslashes($request->part_number);
     $category= $request->id_category;
     $brand= $request->id_brand;
     $photos= $request->photosJson;
     $pounds= $request->pounds;
     $ounces= $request->ounces;
     $length= $request->length;
     $width= $request->width;
     $height= $request->height;
     
     $sql = "INSERT INTO ".$bd.".product (name, short_description, description, price, low_price, stock, part_number, id_cto_product_category, id_brand, pounds, ounces, length, width, height, photos) VALUES ('$name','$short_description', '$description', $price, $low_price, $stock, '$part_number', $category, $brand, $pounds, $ounces, $length, $width, $height, '$photos');";
     
     if ($conn->query($sql) === TRUE) {
         echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }
 
 // Update product
 if($data == "update"){
 
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_product= $request->id_product;
     $name= addslashes($request->name);
     $short_description = addslashes($request->short_description);
     $description = addslashes($request->description);
     $price= $request->price;
     $low_price= $request->low_price;
     $stock= $request->stock;
     $part_number= addslashes($request->part_number);
     $category= $request->id_category;
     $brand= $request->id_brand;
     $photos= $request->photosJson; 
     $pounds= $request->pounds;
     $ounces= $request->ounces;
     $length= $request->length;
     $width= $request->width;
     $height= $request->height;
     
    $sql = "UPDATE ".$bd.".product SET"
    . " name = '$name', "
    . " short_description ='$short_description', "
    . " description = '$description', "
    . " price=$price, "
    . " low_price=$low_price, "
    . " stock=$stock, "
    . " part_number= '$part_number', "
    . " id_cto_product_category=$category, "
    . " id_brand=$brand, "
    . " pounds=$pounds, "
    . " ounces=$ounces, "
    . " length=$length, "
    . " width=$width, "
    . " height=$height, "
    . " photos='$photos'"
    . " WHERE id_product=$id_product;";
    
   echo $sql;
 
   if ($conn->query($sql) === TRUE) {
       echo "New record updated successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }
 
 // Delete product
 if($data == "delete"){
 
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_product= $request->id_product;
     
    $sql = "DELETE FROM ".$bd.".product WHERE id_product=$id_product;";
 
   if ($conn->query($sql) === TRUE) {
       echo "Record deleted successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }





/***
  BRAND SECTION 
***/

// Get all brands
if($data == "getAllBrand"){
	$sql = "SELECT *, (SELECT COUNT(*) FROM ".$bd.".product as p WHERE p.id_brand = b.id_brand ) as products FROM ".$bd.".cto_brand as b;";
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_brand\":\"".$row["id_brand"]."\",\"description\":\"".$row["description"]."\",\"photos\":\"".addslashes($row["photos"])."\", \"products\":\"".$row["products"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[]";
	}
}

// Get brand by id
if($data == "getBrandById"){
	
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	    
	$sql = "SELECT * FROM ".$bd.".cto_brand WHERE id_brand=".$request->id_brand;
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json = $result_json."{\"id_brand\":\"".$row["id_brand"]."\",\"description\":\"".$row["description"]."\",\"photos\":".$row["photos"].",\"website\":\"".$row["website"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

// Add new brand
if($data == "createBrand"){
	
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_brand= $request->id_brand;
     $description = addslashes($request->description);
     $photos= $request->photosJson;
     $website= $request->website;
 
    $sql = "INSERT INTO ".$bd.".cto_brand (description, photos, website) VALUES ('$description', '$photos', '$website');";
 
   if ($conn->query($sql) === TRUE) {
       echo "New record created successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }

 // Update brand
 if($data == "updateBrand"){
 
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_brand= $request->id_brand;
     $description = addslashes($request->description);
     $photos= $request->photosJson; 
     $website= $request->website; 
     
    $sql = "UPDATE ".$bd.".cto_brand SET"
    . " description = '$description',"
    . " photos='$photos',"
    . " website='$website'"
    . " WHERE id_brand=$id_brand;";
    
   echo $sql;
 
   if ($conn->query($sql) === TRUE) {
       echo "New record updated successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }

  // Delete brand
  if($data == "deleteBrand"){
 
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_brand= $request->id_brand;
     
    $sql = "DELETE FROM ".$bd.".cto_brand WHERE id_brand=$id_brand;";
 
   if ($conn->query($sql) === TRUE) {
       echo "Record deleted successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }


/***
  CATEGORY SECTION
***/

// Get all categories
if($data == "getAllCategory"){
	$sql = "SELECT *, (SELECT COUNT(*) FROM ".$bd.".product as p WHERE p.id_cto_product_category = c.id_cto_product_category ) as products FROM ".$bd.".cto_product_category as c;";
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_category\":\"".$row["id_cto_product_category"]."\",\"description\":\"".$row["description"]."\", \"products\":\"".$row["products"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[]";
	}
}

// Get category by id
if($data == "getCategoryById"){
	
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	    
	$sql = "SELECT * FROM ".$bd.".cto_product_category WHERE id_cto_product_category=".$request->id_category;
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_category\":\"".$row["id_cto_product_category"]."\",\"description\":\"".$row["description"]."\", \"photos\":".$row["photos"]."},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}

// Add new category
if($data == "createCategory"){
	
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_category= $request->id_category;
     $description = addslashes($request->description);
     $photos= $request->photosJson; 
 
    $sql = "INSERT INTO ".$bd.".cto_product_category (description, photos) VALUES ('$description', '$photos');";
 
   if ($conn->query($sql) === TRUE) {
       echo "New record created successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }

 // Update category
 if($data == "updateCategory"){
 
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_category= $request->id_category;
     $description = addslashes($request->description);
     $photos= $request->photosJson; 
     
    $sql = "UPDATE ".$bd.".cto_product_category SET"
    . " photos='$photos',"
    . " description = '$description'"
    
    . " WHERE id_cto_product_category=$id_category;";
    
   echo $sql;
 
   if ($conn->query($sql) === TRUE) {
       echo "New record updated successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }

   // Delete category
   if($data == "deleteCategory"){
 
    try {
 
     $postdata = file_get_contents("php://input");
     $request = json_decode($postdata);
     
     $id_category= $request->id_category;
     
    $sql = "DELETE FROM ".$bd.".cto_product_category WHERE id_cto_product_category=$id_category;";
 
   if ($conn->query($sql) === TRUE) {
       echo "Record deleted successfully";
   } else {
       echo "Error: " . $sql . "<br>" . $conn->error;
   }
   
   } catch (Exception $e) {
         echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
   } 
 }

$conn->close();
?>
