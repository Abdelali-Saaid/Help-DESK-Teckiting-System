<?php
    include('connection.php');

    $id = $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM products WHERE id=$pid");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    


                                                   
    $stmt = $conn->prepare("SELECT supplier_name, suppliers.id 
                            FROM suppliers, productsuppliers 
                            WHERE 
                                    productsuppliers.product = $id
                                        AND    
                                    productsuppliers.supplier = suppliers.id
        ");


    $stmt->execute();
    $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);


    $row['suppliers'] = array_column($suppliers, 'id');


    echo json_encode($row);

?>