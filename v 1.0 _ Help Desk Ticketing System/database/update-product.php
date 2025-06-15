<?php

$product_name = $_POST['product_name'];
$description = $_POST['description'];
$pid = $_POST['pid'];

$target_dir = "../uploads/products/";

$file_name_value = NULL;
$file_data = $_FILES['img'];


if($file_data['tmp_name'] !== ''){

    $file_name = $file_data['name'];
    $file_ext = pathinfo($file_name, PATHINFO_EXTENSION);
    $file_name = 'product-' . time() . '.' . $file_ext;


    $check = getimagesize($file_data['tmp_name']);

    if($check){
        if(move_uploaded_file($file_data['tmp_name'], $target_dir . $file_name)){
            $file_name_value = $file_name;
        }
    } 

}

$suppliers = isset($_POST['suppliers']) ? $_POST['suppliers'] : [];



try{ 
    $sql = "UPDATE products 
            SET 
            product_name =?, description=?, img=?
            WHERE id=?";

    include('connection.php');
    $stmt = $conn->prepare($sql);
    $stmt->execute([$product_name, $description, $file_name_value, $pid]);


    $sql = "DELETE FROM productsuppliers WHERE product=?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$pid]);

 


    foreach($suppliers as $supplier){
        $supplier_data = [
            'supplier_id' => $supplier,
            'product_id' => $pid,
            'updated_at' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ];
            

        $sql = "INSERT INTO productsupplier
                        $table_name(supplier, product, updated_at, created_at)
                    VALUES 
                        (:supplier_id, :product_id, :updated_at, :created_at)";


        include('connection.php');

        $stmt = $conn->prepare($sql);
        $stmt->execute($db_arr);
    }





    $response = [
        'success' => true,
        'message' => "<strong>$product_name</strong> successfuly updated to the system."
    ];

} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => "Error processing your request"
    ];
}






echo json_encode($response);

?>