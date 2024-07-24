<?php 

    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Headers: *");
    header("Access-Control-Allow-Methods: GET, POST");

    $conn = mysqli_connect("localhost", "u606518727_rcrud", "P4tr1ckSt4r", "u606518727_patrick_rcrud");

    if(!$conn) {
        die("ERROR: Could Not Connect" . mysqli_connect_error());
    }

    $method = $_SERVER['REQUEST_METHOD'];

    switch($method) {

        case "GET":
            $sql = "SELECT * FROM products WHERE deleted_at IS NULL";
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0) {
                $products = mysqli_fetch_all($result, MYSQLI_ASSOC);
                echo htmlspecialchars_decode(json_encode($products));
                return;
            } else {
                echo json_encode([]);
                return;
            }
            break;
        
        case "POST":

            if(isset($_FILES['image'])) {

                $title = htmlspecialchars($_POST['title']);
                $price = htmlspecialchars($_POST['price']);
                $image = htmlspecialchars($_FILES['image']['name']);
                $image_temp = htmlspecialchars($_FILES['image']['tmp_name']);
                $destination = $_SERVER['DOCUMENT_ROOT'].'/patrick/api/react_crud/images'."/".$image;

                $sql = "INSERT INTO products(prod_title, prod_price, prod_image, prod_status) VALUES('$title', '$price', '$image', '1')";
                $result = mysqli_query($conn, $sql);

                if($result) {
                    move_uploaded_file($image_temp, $destination);
                    echo json_encode(["dialogue"=>"Product inserted successfully"]);
                    return;
                } else {
                    echo json_encode(["dialogue"=>"Product not inserted"]);
                    return;
                }
            } else {
                echo json_encode(["dialogue"=>"Incorrect data format"]);
                return;
            }
            break;
    }
?>