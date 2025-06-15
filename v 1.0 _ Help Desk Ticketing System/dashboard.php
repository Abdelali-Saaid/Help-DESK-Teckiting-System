<?php
    // Start the session
    session_start();

    // Get SESSION user from data.
    //$session_user = $_SESSION['user'];
    //var_dump($session_user);
    //die;



    if (!isset($_SESSION['user'])) header('location: index.php');

    $user = $_SESSION['user'];

    include('database/po_status_pie_graph.php');
    include('database/supplier_product_bar_graph.php');
    include('database/delivery_history.php');

?>


<!DOCTYPE html> 
<html lang="en">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>DASHBOARD</title>
        <link rel="stylesheet" href="css/styles.css">
        <script src="https://kit.fontawesome.com/6b6c19b623.js" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="dashboardMainContainer">
            <?php include('partials/app-sidebar.php') ?>
            <div class="dashboard_content_container" id="dashboard_content_container">
                <?php include('partials/app-topnav.php') ?>
                <?php 
                    if(in_array('dashboard_view', $user['permissions'])) { 
                ?>
                <div class="dashboard_content"> 
                    <div class="dashboard_content_main">
                        <div class="col50">
                            <figure class="highcharts-figure">
                                <div id="container"></div>
                                    <p class="highcharts-description">
                                        Here is the breakdown of the purchase orders by status.
                                    </p>
                            </figure>
                        </div>
                        <div class="col50">
                            <figure class="highcharts-figure">
                                <div id="containerBarChart"></div>
                                    <p class="highcharts-description">
                                        Here is the breakdown of the purchase orders by status.
                                    </p>
                            </figure>
                        </div>
                        <div id="deliveryHistory">
                            
                        </div>
                    </div>
                </div>
                <?php } else { ?>
                    <div id="errorMessage">Access denied.</div>
                <?php } ?>
            </div>
        </div>
        <script src="js/script.js"></script>
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>  
        
        
        <script>
            var graphData = <?= json_encode($results) ?>;

            Highcharts.chart('container', {
                chart: {
                    plotBackgroundColor: null,
                    plotBorderWidth: null,
                    plotShadow: false,
                    type: 'pie'
                },
                title: {
                    text: 'Purchase Orders By Status',
                    align: 'left'
                },
                tooltip: {
                    pointFormatter: function(){
                        var point = this,
                            series = point.series;
                        return '<b>${series.name}</b>: ${point.y}'
                    }
                },
                plotOptions: {
                    pie: {
                        allowPointSelect: true,
                        cursor: 'pointer',
                        dataLabels: {
                            enabled: true,
                            format: '<b>{point.name}</b>: {point.y}'
                        }
                    }
                },

                series: [{
                    name: 'Status',
                    colorByPoint: true,
                    data: graphData 
                    }]
            });


            var barGraphData = <?= json_encode($bar_chart_data) ?>;
            var barGraphCategories = <?= json_encode($categories) ?>;

            

            Highcharts.chart('containerBarChart', {
                chart: {
                    type: 'column'
                },
                title: {
                    text: 'Product Count Assigned To Suplier' 
                },             
                xAxis: {    
                    categories: barGraphCategories,
                    crosshair: true,
                    accessibility: {
                        description: 'Countries'
                    }
                },
                yAxis: {
                    min: 0,
                    title: {
                        text: '1000 metric tons (MT)'
                    }
                },
                tooltip: {
                    pointerFormatter: function(){
                        var point = this,
                            series = point.series;

                        return '<b>${point.category}</b>: ${point.y}'
                    }
                },
                plotOptions: {
                    column: {
                        pointPadding: 0.2,
                        borderWidth: 0
                    }
                },
                series: [
                    {
                        name: 'Suppliers',
                        data: barGraphData
                    }
                    ]
            });




            var lineCategories <?= json_encode($line_categories) ?>;
            var lineData = <?= json_encode($line_data) ?>
            Highcharts.chart('deliveryHistory', {

                chart: {
                    type: 'spline'
                },

                title: {
                    text: 'Delivery History Per Day',
                    align: 'left'
                },

                yAxis: {
                    title: { 
                    text: 'Product Delivered',
                }
                },

                xAxis: {
                    categories: [
                        lineCategories
                    ]
                },

                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'middle'
                },

                plotOptions: {
                    series: {
                        label: {
                            connectorAllowed: false
                        },
                       
                    }
                },

                series: [{
                    name: 'Product Delivered',
                    data: lineData
                
                        }],

                responsive: {
                    rules: [{
                        condition: {
                            maxWidth: 500
                        },
                chartOptions: {
                    legend: {
                        layout: 'horizontal',
                        align: 'center',
                        verticalAlign: 'bottom'
                    }
                }
            }]
        }

        });

    </script>
    </body>
</html>