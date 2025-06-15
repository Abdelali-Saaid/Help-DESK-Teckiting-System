<?php

$supplier_name = isset($_POST['products']) ? $_POST['supplier_name'] : '';
$supplier_location = isset($_POST['supplier_location']) ? $_POST['supplier_location'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$products = isset($_POST['products']) ? $_POST['products'] : '';


$supplier_id = $_POST['sid'];



try{ 
    $sql = "UPDATE suppliers
            SET 
            supplier_name =?, supplier_location=?, email=?, 
            WHERE id=?";

    include('connection.php');
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_name, $supplier_location, $email, $supplier_id]);


    $sql = "DELETE FROM productsuppliers WHERE supplier=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$supplier_id]);

 

    $products = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];
    foreach($products as $product){
        $supplier_data = [
            'supplier_id' => $supplier_id,
            'product_id' => $product,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
            

        $sql = "INSERT INTO productsuppliers
                        $table_name(supplier, product, updated_at, created_at)
                    VALUES 
                        (:supplier_id, :product_id, :updated_at, :created_at)";


        include('connection.php');

        $stmt = $conn->prepare($sql);
        $stmt->execute($supplier_data);
    }





    $response = [
        'success' => true,
        'message' => "<strong>$supplier_name</strong> successfuly updated to the system."
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => "Error processing your request"
    ];
}






echo json_encode($response);

?> 