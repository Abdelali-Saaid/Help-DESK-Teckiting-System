<?php
    require('fpdf/fpdf.php');

    class PDF extends FPDF
    {
        
        // Tableau coloré
        function FancyTable($headers, $data, $row_height = 30)
        {
            parent::__construct('L');

            // Couleurs, épaisseur du trait et police grasse
            $this->SetFillColor(255,0,0);
            $this->SetTextColor(255);
            $this->SetDrawColor(128,0,0);
            $this->SetLineWidth(.3);
            $this->SetFont('','B');
            // En-tête
            //$w = array(15, 70, 35, 15, 45, 45, 45);
            //for($i=0;$i<count($header);$i++)
              //  $this->Cell($w[$i],7,$header[$i],1,0,'C',true);

            $width_sum = 0;
            foreach($headers as $header_key => $header_data){
                $this->Cell($header_data['width'],7,$header_key,1,0,'C',true);
                $width_sum += $header_data['width'];
            }
                


            $this->Ln();
            // Restauration des couleurs et de la police
            //$this->SetFillColor(224,235,255);
            $this->SetTextColor(0);
            //$this->SetFont('');
            // Données
            //$fill = false;

            $img_pos_y = 40;
            $header_keys = array_keys($headers);
            foreach($data as $row)
            { 
                foreach($header_keys as $header_key){
                    $content = $row[$header_key]['content'];
                    $width = $headers[$header_key]['width'];
                    $align = $row[$header_key]['align'];
                    if($header_key == 'image')
                        $content = is_null($row[1]) || $row[1] == "" ? 'No Image' : $this->Image('.././uploads/products/' . $row[1], 45, $img_pos_y, 30, 25);


                    $this->Cell($width, $row_height, $content, 'LRBT');
                    
                }
                $this->Ln();
                
                $img_pos_y += 30;

                

/*
                $this->Cell($w[0],30,$row[0],'LRBT',0,'C');
                $this->Cell($w[1],30, $image,'LRBT',0,'L');
                $this->Cell($w[2],30,$row[2],'LRBT',0,'C');
                $this->Cell($w[3],30,$row[3],'LRBT',0,'C');
                $this->Cell($w[4],30,$row[4],'LRBT',0,'L');
                $this->Cell($w[5],30,$row[5],'LRBT',0,'L');
                $this->Cell($w[6],30,$row[6],'LRBT',0,'L');
*/


                

            }
            // Trait de terminaison
            //  $this->Cell(array_sum($w),0,'','T');
        }   
        }
 


        $type = $_GET['report'];

        $report_headers = [
            'product' => 'Product Reports',
            'supplier' => 'Supplier Reports',
            'delivery' => 'Delivery Report',
            'purchase_orders' => 'Purchase Order Report'
        ];

        $row_height = 30;

        include('connection.php');

        if($type == 'product'){







            $headers = [
                'id' => [
                    'width' => 15
                ],
                'image' => [
                    'width' => 70
                ],
                'product_name' => [
                    'width' =>35
                ],
                'stock' => [
                    'width' => 15
                ],
                'created_by' => [
                    'width' => 45
                ],
                'updated_at' => [
                    'width' => 45
                ]
            
            ];
            











            // Titres des colonnes
            $header = array('id', 'image', 'product_name', 'description', 'stock', 'created_by', 'created_at', 'updated_at');
            // Chargement des données

            $stmt = $conn->prepare("SELECT *, products.id as pid FROM products INNER JOIN users ON users.created_by = users.id ORDER BY products.created_at DESC");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $products = $stmt->fetchAll();

            $is_header = true;
            $data = [];
            foreach($products as $product){
 
                $product['created_by'] = $product['first_name'] . ' ' . $product['last_name'];
                unset($product['first_name'], $product['last_name'], $product['password'], $product['email']);


                /*if($is_header){
                    $row = array_keys($product);
                    $is_header = false;
                    echo implode("\t", $row) . "\n";
                }*/

                array_walk($product, function(&$str){
                    $str = preg_replace("/\t/", "\\t", $str);
                    $str = preg_replace("/\r?\n", "\\n", $str);
                    if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"'; 
                });


                $data[] = [
                    'id' => [
                        'content' => $product['pid'],
                        'align' => 'C'
                    ],
                    'image' => [
                        'content' => $product['img'],
                        'align' => 'C'
                    ],
                    'product_name' => [
                        'content' => $product['product_name'],
                        'align' => 'C'
                    ],
                    'stock' => [
                        'content' => number_format($product['stock']),
                        'align' => 'C'
                    ],
                    'created_by' => [
                        'content' => $product['created_by'],
                        'align' => 'L'
                    ],
                    'created_at' => [
                        'content' => date('M d,Y h:i:s A', strtotime($product['created_at'])),
                        'align' => 'L'
                    ],
                    'updated_at' => [
                        'content' => date('M d,Y h:i:s A', strtotime($product['updated_at'])),
                        'align' => 'L'
                    ]
                ];

            }
        }



        if($type === 'supplier'){
        
            $stmt = $conn->prepare("SELECT suppliers.id as sid, suppliers.created_at as 'created at', users.first_name, users.last_name, suppliers.supplier_location, suppliers.email, suppliers.created_by FROM suppliers INNER JOIN users ON suppliers.created_by = users.id ORDER BY suppliers.created_at DESC");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
    
            $suppliers = $stmt->fetchAll();



            $headers = [
                'supplier_id' => [
                    'width' => 30
                ],
                'created at' => [
                    'width' => 70
                ],
                'supplier_location' => [
                    'width' => 50
                ],
                'email' => [
                    'width' => 50
                ],
                'created_by' => [
                    'width' =>  0
                ]
            ];


    
            $is_header = true;
            foreach($suppliers as $supplier){
                
                $supplier['created_by'] = $supplier['first_name'] . ' ' . $supplier['last_name'];
                    


                $data[] = [
                    'id' => [
                        'content' => $supplier['sid'],
                        'align' => 'C'
                    ],
                    'created_at' => [
                        'content' => $supplier['created at'],
                        'align' => 'C'
                    ],
                    
                    'supplier_location' => [
                        'content' => $supplier['supplier_location'],
                        'align' => 'L'
                    ],
                    'email' => [
                        'content' => $product['email'],
                        'align' => 'L'
                    ],
                    'created_by' => [
                        'content' => $supplier['created_by'],
                        'align' => 'L'
                    ],

                ];
            }
            $row_height = 10;
        }
    





        if($type === 'delivery'){
        
            $stmt = $conn->prepare("SELECT date_received, qty_received, first_name, last_name, products.product_name, supplier_name, batch
                                        FROM order_product_history, order_product, users, suppliers, products
                                        WHERE 
                                            order_product_history.order_product_id = order_product.id
                                        AND
                                            order_product.created_by = users.id
                                        AND
                                            order_product.supplier = suppliers.id
                                        AND
                                            order_product.product = productss.id
                                        ORDER BY order_product.batch DESC
                                    ");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);
    




            $headers = [
                'data_received' => [
                    'width' => 40
                ],
                'qty_received' => [
                    'width' => 30
                ],
                'product_name' => [
                    'width' => 50
                ],
                'supplier_name' => [
                    'width' => 60
                ],
                'batch' => [
                    'width' =>  30
                ],
                'created_by' => [
                    'width' =>  60
                ]
            ];





            $deliveries = $stmt->fetchAll();
    

            foreach($deliveries as $delivery){
                $delivery['created_by'] = $delivery['first_name'] . ' ' . $supplier['last_name'];



                $data[] = [
                    'date_received' => [
                        'content' => $delivery['date_received'],
                        'align' => 'C'
                    ],
                    'qty_received' => [
                        'content' => $delivery['qty_received'],
                        'align' => 'C'
                    ],
                    
                    'product_name' => [
                        'content' => $delivery['product_name'],
                        'align' => 'C'
                    ],
                    'supplier_name' => [
                        'content' => $delivery['supplier_name'],
                        'align' => 'C'
                    ],
                    'batch' => [
                        'content' => $delivery['batch'],
                        'align' => 'C'
                    ],
                    'created_by' => [
                        'content' => $delivery['created_by'],
                        'align' => 'C'
                    ],

                ];
            }
        }
    




        if($type === 'purchase_orders'){
        
            $stmt = $conn->prepare("SELECT products.product_name, order_product.id, order_product.quantity_ordered, order_product.quantity_received, order_product.quantity_remaining, order_product.status, order_product.batch, users.first_name, users.last_name, suppliers.supplier_name, order_product.created_at as 'order product created at' 
                                    FROM order_product
                                    INNER JOIN users ON order_product.created_by = users.id
                                    INNER JOIN suppliers ON order_product.supplier = suppliers.id
                                    INNER JOIN products ON order_product.product = products.id
                                    ORDER BY order_product.batch DESC
                                    ");
            $stmt->execute();
            $stmt->setFetchMode(PDO::FETCH_ASSOC);

            $order_products = $stmt->fetchAll();



            $headers = [
                'qty_ordered' => [
                    'width' => 30
                ],
                'qty_received' => [
                    'width' => 30
                ],
                'qty_remaining' => [
                    'width' => 30
                ],
                'status' => [
                    'width' =>  25
                ],
                'batch' => [
                    'width' =>  30
                ],
                'supplier_name' => [
                    'width' =>  50
                ],
                'product_name' => [
                    'width' =>  50
                ],
                'created_at' => [
                    'width' =>  40
                ],
                'created_by' => [
                    'width' =>  40
                ]
            ];







            
    
    
    

            foreach($order_products as $order_product){
                $order_product['created_by'] = $order_product['first_name'] . ' ' . $order_product['last_name'];



                

                $data[] = [
                    'qty_ordered' => [
                        'content' => $order_product['quantity_ordered'],
                        'align' => 'C'
                    ],
                
                    'qty_received' => [
                        'content' => $order_product['quantity_received'],
                        'align' => 'C'
                    ],
                    'qty_remaining' => [
                        'content' => $order_product['quantity_remaining'],
                        'align' => 'C'
                    ],
                    'status' => [
                        'content' => $order_product['status'],
                        'align' => 'C'
                    ],
                    'batch' => [
                        'content' => $order_product['batch'],
                        'align' => 'C'
                    ],
                    'supplier_name' => [
                        'content' => $order_product['supplier_name'],
                        'align' => 'C'
                    ],
                    'product_name' => [
                        'content' => $order_product['product_name'],
                        'align' => 'C'
                    ],
                    'created_at' => [
                        'content' => $order_product['created_at'],
                        'align' => 'C'
                    ],
                    'created_by' => [
                        'content' => $order_product['created_by'],
                        'align' => 'C'
                    ],

                ];
                
            }
        }
            
          
        
    



    $pdf = new PDF();
    $pdf->SetFont('Arial','',20);
    $pdf->AddPage();

    $pdf->Cell(80);
    $pdf->Cell(100,10,$report_headers[$type],0,0,'C');
    $pdf->SetFont('Arial','',10);
    $pdf->Ln();

    $pdf->FancyTable($header,$data);
    $pdf->Output();


?>


