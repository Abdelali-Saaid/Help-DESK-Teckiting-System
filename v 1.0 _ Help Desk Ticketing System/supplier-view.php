<?php
    // Start the session
    session_start();
    if (!isset($_SESSION['user'])) header('location: login.php');

    // $_SESSION['table'] = 'products';
    $show_table = 'suppliers';

    $suppliers = include('database/show.php');
?> 

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title> View Purchase Orders - DASHBOARD</title>
        <?php include('partials/app-header-scripts.php'); ?>
    </head>
    <body>
        <div id="dashboardMainContainer">
            <?php include('partials/app-sidebar.php') ?>
            <div class="dashboard_content_container" id="dashboard_content_container">
                <?php include('partials/app-topnav.php') ?>
                <div class="dashboard_content">

                    <?php if(in_array('supplier_view', $user['permissions'])) { ?>

                    <div class="dashboard_content_main">
                        <div class="row">
                            <div class="column column-12">
                                <h1 class="section_header"><i class="fa-solid fa-list"></i> List of Purchase Order</h1>
                                <div class="section_content">
                                    <div class="users">
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th>Id</th>
                                                    <th>Supplier Name</th>
                                                    <th>Supplier Location</th>
                                                    <th>Contact Details</th>
                                                    <th>Products</th>
                                                    <th>Created By</th>
                                                    <th>Created At</th>
                                                    <th>Update At</th>
                                                    <th>Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach($suppliers as $index => $supplier) { ?>
                                                    <tr>
                                                        <td><?= $index + 1 ?></td>
                                                        <td>
                                                            <?= $supplier['supplier_name'] ?>
                                                        </td>
                                                        <td><?= $supplier['supplier_location'] ?></td>
                                                        <td><?= $supplier['email'] ?></td>
                                                        <td>
                                                            <?php

                                                                $product_list = '-';
                                                                
                                                                  
                                                                $sid = $supplier['id'];
                                                                $stmt = $conn->prepare("SELECT product_name 
                                                                                        FROM products, productsuppliers 
                                                                                        WHERE 
                                                                                                productsuppliers.supplier = $sid
                                                                                                    AND    
                                                                                                productsuppliers.product = products.id
                                                                    ");
                                                                $stmt->execute();
                                                                $row = $stmt->fetchAll(PDO::FETCH_ASSOC);
                                                                if($row){
                                                                    $product_arr = array_column($row, 'product_name');
                                                                    $product_list = '<li>' . implode("</li><li>", $product_arr);
                                                                }

                                                                echo $product_list;
                                                                
                                                            ?>
                                                        </td>
                                                        <td>
                                                            <?php
                                                                $uid = $supplier['created_by'];
                                                                $stmt = $conn->prepare("SELECT * FROM users WHERE id=$uid");
                                                                $stmt->execute();
                                                                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                                                
                                                                $created_by = $row['first_name'] . ' ' . $row['last_name'];
                                                                echo $created_by_name;
                                                            ?>
                                                        </td>
                                                        <td><?= date('M d,Y @ h:i:s', strtotime($supplier['created_at'])) ?></td>
                                                        <td><?= date('M d,Y @ h:i:s', strtotime($supplier['updated_at'])) ?></td>
                                                        <td>
                                                            <a href="" 
                                                            class="<?= in_array('supplier_edit', $user['permissions']) ? 'updateSupplier': 'AccessDeniedErr' ?>" 
                                                            data-sid="<?= $supplier['id'] ?>">
                                                            <i class="fa fa-pencil"></i> Edit</a>

                                                            <a href="" 
                                                            class="<?= in_array('supplier_delete', $user['permissions']) ? 'deleteSupplier': 'AccessDeniedErr' ?>" 
                                                            data-name="<?= $supplier['supplier_name'] ?>" data-sid="<?= $supplier['id'] ?>">
                                                            <i class="fa fa-trash"></i> Delete</a>
                                                        </td>
                                                    </tr> 
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                        <p class="userCount"><?= count($suppliers) ?> Suppliers</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php } else { ?>
                        <div id="errorMessage"> Access denied. </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php include('partials/app-scripts.php'); ?>
        <script>

            function script(){
                var vm = this;

                this.registerEvents = function(){
                    document.addEventListener('click', function(e){
                        targetElement = e.target;
                        classList = targetElement.classList;


                        if(classList.contains('deleteSupplier')){
                            e.preventDefault();


                            sId = targetElement.dataset.sid;
                            supplierName = targetElement.dataset.name;



                            BootstrapDialog.confirm({
                                type: BootstrapDialog.TYPE_DANGER,
                                title: 'Delete Supplier',
                                message: 'Are you sure to delete <strong>'+ supplierName +'</strong>?',
                                callback: function(isDelete){
                                    if(isDelete){

                                        $.ajax({
                                        method: 'POST',
                                        data: {
                                            id: sId,
                                            table: 'suppliers'   
                                        },
                                        url: 'database/delete.php',
                                        dataType: 'json',
                                        success: function(data){
                                            message = data.success ?
                                                supplierName + 'successfully deleted!' : 'Error processing your request!';


                                                BootstrapDialog.alert({
                                                    type: data.success ? BootstrapDialog.TYPE_SUCCESS :  BootstrapDialog.TYPE_DANGER,
                                                    message: message,
                                                    callback: function(){
                                                    location.reload();
                                                    }
                                                });
                                        }   
                                        });
                                    }
                                }      
                            
                            });
                        }


                        if(classList.contains('accessDeniedErr')){
                            e.preventDefault(); // this prevents the default mechanism.
                            BootstrapDialog.alert({
                                type: BootstrapDialog.TYPE_DANGER,
                                message: 'Access denied'
                            });
                        }


                        if(classList.contains('updateSupplier')){
                            e.preventDefault();


                            sId = targetElement.dataset.sid;
                            vm.showEditDialog(sId);

                        }
                    });

                    //document.addEventListener('submit', function(e){
                    //    targetElement = e.target;

                     //   alert(targetElement.id);
                       // e.preventDefault(); 
                    //});

                    //$('#editProductForm').on('submit', function(e){
                    //    e.preventDefault();
                    //});

                    document.addEventListener('submit', function(e){
                        e.preventDefault();
                        targetElement = e.target;

                        if(targetElement.id === 'editSupplierForm'){
                            vm.saveUpdatedData(targetElement);
                        }
                    })
                },
                this.saveUpdatedData = function(form){
                    $.ajax({
                        method: 'POST', 
                        data: {
                            supplier_name: document.getElementById('supplier_name').value,
                            supplier_location: document.getElementById('supplier_location').value,
                            email: document.getElementById('email').value,
                            products: document.getElementById('products').value,
                            sid: document.getElementById('sid').value
                        },
                        url: 'database/update-supplier.php',
                        dataType: 'json',
                        success: function(data){
                            BootstrapDialog.alert({
                                type: data.success ? BootstrapDialog.TYPE_SUCCESS : BootstrapDialog.TYPE_DANGER,
                                message: data.message,
                                callback: function(){
                                    if(data.success) location.reload();
                                }
                            })
                            
                            }
                        });
                    
                }


                this.showEditDialog = function(id){
                    $.get('/database/get-supplier.php', {id: id}, function(supplierDetails){

 
                        let curProducts = supplierDetails['suppliers'];
                        let productOptions = '';

                        for (const [pId, Name] of Object.entries(productsList)){
                            selected = curProducts.indexOf(pId) > -1 ? 'selected' : '';
                            productOptions += "<option "+ selected +" value='"+ pId +"'>"+ pName +"</option>";
                        }


                        BootstrapDialog.confirm({
                                    title: 'Update <strong>' + supplierDetails.supplier_name + '</strong>',
                                    message: '<form action="database/add.php" enctype="multipart/form-data" id="editProductForm">\
                                        <div class="appFormInputContainer">\
                                            <label for="supplier_name"> Supplier Name</label>\
                                            <input type="text" class="appFormInput" id="supplier_name" value="'+ supplierDetails.supplier_name +'" placeholder="Enter supplier name..." name="supplier_name">\
                                        </div>\
                                        <div class="appFormInputContainer">\
                                            <label for="supplier_location"> Location</label>\
                                            <input type="" class="appFormInput" value="'+ supplierDetails.supplier_location +'" placeholder="Enter product supplier location..." id="supplier_location" name="supplier_location">\
                                        </div>\
                                        <div class="appFormInputContainer">\
                                            <label for="email">Email</label>\
                                            <input type="text" class="appFormInput" value="'+ supplierDetails.email +'" placeholders="Enter supplier email..." id="email" name="email">\
                                        </div>\
                                            <div class="appFormInputContainer">\
                                            <label for="products"> Products</label>\
                                            <select name="products[]" id="products" multiple="">\
                                                <option value="">Select Products</option>\
                                                '+   $supplierOptions +'\
                                            </select>\
                                            </div>\
                                            <div class="appFormInputContainer">\
                                                <label for="description"> Description</label>\
                                                <textarea class="appFormInput productTextAreaInput" placeholder="Enter product description..." id="description" name="description"> '+ supplierDetails.supplier_name +' </textarea>\
                                            </div>\
                                            <input type="hidden" name="sid" id="sid" value="'+ supplierDetails.id +'"/>\
                                            <input type="submit" value="submit" id="editSupplierSubmitBtn" class="hidden"/>\
                                        </form>\
                                    ',
                                    callback: function(isUpdate){
                                        if(isUpdate){ // If user click 'Ok' button.

                                            document.getElementById('editSupplierSubmitBtn').click();

                                        }
                                    }
                            }); 

                
                    }, 'json');


                    BootstrapDialog.confirm({
                                    title: 'Update ' + firstName + ' ' + lastName,
                                    message: '<form>\
                                        <div class="form-group">\
                                            <label for="firstName">First Name:</label>\
                                            <input type="text" class="form-control" id="firstName" value="'+ firstName +'">\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="lastName">Last Name:</label>\
                                            <input type="text" class="form-control" id="lastName" value="'+ lastName +'">\
                                        </div>\
                                        <div class="form-group">\
                                            <label for="email">Email address:</label>\
                                            <input type="email" class="form-control" id="emailUpdate" value="'+ email +'">\
                                        </div>\
                                    </form>',
                                    callback: function(isUpdate){
                                        if(isUpdate){ // If user click 'Ok' button.
                                            $.ajax({
                                                method: 'POST',
                                                data: {
                                                    user_id: userId,
                                                    f_name: document.getElementById('firstName').value,
                                                    l_name: document.getElementById('lastName').value,
                                                    email: document.getElementById('email').value
                                                },
                                                url: 'database/update-user.php',
                                                dataType: 'json',
                                                success: function(data){
                                                    if(data.success){
                                                        BootstrapDialog.alert({
                                                            type: BootstrapDialog.TYPE_SUCCESS,
                                                            message: data.message,
                                                            callback: function(){
                                                                location.reload();
                                                            }
                                                        });

                                                    } else 
                                                        BootstrapDialog.alert({
                                                            type: BootstrapDialog.TYPE_DANGER,
                                                            message: data.message,
                                                        });
                                                }
                                            })
                                        }
                                    }
                            }); 

                }

            }

                this.initialize = function(){
                    this.registerEvents();
                }

            var script = new script;
            script.initialize();
        </script>
    </body>
</html>