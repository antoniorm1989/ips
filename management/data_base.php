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


if($data == "getAllProducts"){
	$sql = "SELECT p2.id_product, p2.name, p2.price, p2.low_price, p2.c_name, p2.photo_principal, b.name as b_name, b.description as b_description, c_description as c_description, p2.stock, p2.part_number FROM (SELECT p.id_product, p.name, p.price, p.low_price, p.stock, p.part_number, p.id_cto_brand, p.photo_principal, c.name as c_name, c.description as c_description FROM ".$bd.".product as p INNER JOIN ".$bd.".cto_product_category as c ON p.id_cto_product_category = c.id_cto_product_category) as p2 INNER JOIN ".$bd.".cto_brand as b ON p2.id_cto_brand = b.id_brand;";
	
	$result_json = "[";
	$result = $conn->query($sql);

	if ($result->num_rows > 0) {

	    // output data of each row
	    while($row = $result->fetch_assoc()) {

			/*$result_json_photo = "[";
			$sqlPhotos = "SELECT json_data as json_data FROM ".$bd.".photo WHERE id_product = ".$row["id_product"]." LIMIT 1;";
			$resultPhotos = $conn->query($sqlPhotos);

			if ($resultPhotos->num_rows > 0) {
				while($rowPhoto = $resultPhotos->fetch_assoc()) {
					$result_json_photo = $result_json_photo.$rowPhoto["json_data"].",";
				}
				$result_json_photo = substr($result_json_photo, 0, -1)."]";
			}else{
				$result_json_photo = "[]";
			}*/

	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"id_category\":\"".$row["c_description"]."\", \"id_brand\":\"".$row["b_description"]."\",\"part_number\":\"".$row["part_number"]."\",\"stock\":\"".$row["stock"]."\",\"photo\":\"".$row["photo_principal"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    
		echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}



if($data == "getAllBrand"){
	$sql = "SELECT * FROM ".$bd.".cto_brand";
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_brand\":\"".$row["id_brand"]."\",\"name\":\"".$row["name"]."\",\"description\":\"".$row["description"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[]";
	}
}



if($data == "getAllCategory"){
	$sql = "SELECT * FROM ".$bd.".cto_product_category";
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        $result_json = $result_json."{\"id_category\":\"".$row["id_cto_product_category"]."\",\"name\":\"".$row["name"]."\",\"description\":\"".$row["description"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[]";
	}
}



if($data == "getProductById"){
	
	$postdata = file_get_contents("php://input");
	$request = json_decode($postdata);
	    
	$sql = "SELECT * FROM ".$bd.".product WHERE id_product=".$request->id_product;
	
	$result_json = "[";
	$result = $conn->query($sql);
	
	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {

			$result_json_photo = "[";
			$sqlPhotos = "SELECT json_data as json_data FROM ".$bd.".photo WHERE id_product =".$request->id_product;
			$resultPhotos = $conn->query($sqlPhotos);

			if ($resultPhotos->num_rows > 0) {
				while($rowPhoto = $resultPhotos->fetch_assoc()) {
					$result_json_photo = $result_json_photo.$rowPhoto["json_data"].",";
				}
				$result_json_photo = substr($result_json_photo, 0, -1)."]";
			}else{
				$result_json_photo = "[]";
			}

	        $result_json = $result_json."{\"id_product\":\"".$row["id_product"]."\",\"name\":\"".$row["name"]."\",\"description\":\"".addslashes($row["description"])."\",\"price\":\"".$row["price"]."\",\"low_price\":\"".$row["low_price"]."\", \"id_category\":\"".$row["id_cto_product_category"]."\", \"max_sale\":\"".$row["max_sale"]."\",\"short_description\":\"".addslashes($row["short_description"])."\",\"id_brand\":\"".$row["id_cto_brand"]."\",\"part_number\":\"".$row["part_number"]."\",\"stock\":\"".$row["stock"]."\",\"photos\":".$result_json_photo.",\"pounds\":\"".$row["pounds"]."\",\"ounces\":\"".$row["ounces"]."\",\"length\":\"".$row["length"]."\",\"width\":\"".$row["width"]."\",\"height\":\"".$row["height"]."\"},";
	    }
	    $result_json = substr($result_json, 0, -1)."]";
	    echo $result_json;
	} else {
	    echo "[{\"message\":\"query rusult empty.\"}]";
	}
}






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
	    $photos= json_decode($request->photosJson);
		$pounds= $request->pounds;
		$ounces= $request->ounces;
		$length= $request->length;
		$width= $request->width;
		$height= $request->height;
	
	   $sql = "INSERT INTO ".$bd.".product (name, short_description, description, price, low_price, stock, part_number, id_cto_product_category, id_cto_brand, pounds, ounces, length, width, height) VALUES ('$name','$short_description', '$description', $price, $low_price, $stock, '$part_number', $category, $brand, $pounds, $ounces, $length, $width, $height);";

	
	  if ($conn->query($sql) === TRUE) {

		  foreach ($photos as $photo) {
			  
			$photo_as_string = json_encode($photo);
			$sql_photos = "INSERT INTO ".$bd.".photo (json_data, id_product) VALUES ('$photo_as_string',$conn->insert_id);";

			if ($conn->query($sql_photos) === TRUE) {
				 echo "New record created successfully photos..";
			}else{
				echo "Error: " . $sql_photos . "<br>" . $conn->error;
			}
		 } 
		  echo "New record created successfully";
	  } else {
	      echo "Error: " . $sql . "<br>" . $conn->error;
	  }
	  
	  } catch (Exception $e) {
    		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	  } 

	}
	
	
	
	
	
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
	    $photos= json_decode($request->photosJson); // quitarlas y ponerlas
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
	   . " id_cto_brand=$brand, "
	   . " pounds=$pounds, "
	   . " ounces=$ounces, "
	   . " length=$length, "
	   . " width=$width, "
	   . " height=$height"
	   . " WHERE id_product=$id_product;";
	   
	  echo $sql;
	
	  if ($conn->query($sql) === TRUE) {

		  if ($conn->query("DELETE FROM ".$bd.".photo WHERE id_product=$id_product;") === TRUE) {
		  
			foreach ($photos as $photo) {
				
				$photo_as_string = json_encode($photo);
				$sql_photos = "INSERT INTO ".$bd.".photo (json_data, id_product) VALUES ('$photo_as_string',$id_product);";

				if ($conn->query($sql_photos) === TRUE) {
					echo "New record created successfully photos..";
				}else{
					echo "Error: " . $sql_photos . "<br>" . $conn->error;
				}
			} 
		  }

	      echo "New record updated successfully";
	  } else {
	      echo "Error: " . $sql . "<br>" . $conn->error;
	  }
	  
	  } catch (Exception $e) {
    		echo 'Excepcion capturada: ',  $e->getMessage(), "\n";
	  } 

	}
	
	
	
	if($data == "delete"){
	
	   try {
	
	    $postdata = file_get_contents("php://input");
	    $request = json_decode($postdata);
	    
	    $id_product= $request->id_product;
	    
	   $sql = "DELETE FROM ".$bd.".product WHERE id_product=$id_product;";
	   
	  echo $sql;
	
	  if ($conn->query($sql) === TRUE) {
		  if ($conn->query("DELETE FROM ".$bd.".photo WHERE id_product=$id_product;") === TRUE) {
			echo "Photos deleted successfully";
		  }
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