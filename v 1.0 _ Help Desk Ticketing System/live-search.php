<!DOCTYPE html>
<html>
<head>
</head>
<body>
    <input type="text" id="searchInput" placeholder="Enter search term...">
    <div id="searchResult"></div>


<!-- 
    1. Create a script to watch if user type keys in the input
    2. Fetch data from database
    3. Display results
  -->


    <!--<script src="js/script.s?v=<?= time(); ?>"></script>-->
    <script src="js/jquery/jquery-3.7.1.min.js"></script>
    <script>

        var typingTimer;              // Timer identifier
        var doneTypingInterval = 500; // Time in ms (500 milliseconds interval)



        document.addEventListener('keyup', function(ev){
            let el = ev.target;

            // If searchInput is the element
            if(el.id === 'searchInput'){
                let searchTerm = el.value;

                // Use clearTimeout to stop running setTimeout
                clearTimeout(typingTimer);

                // Set timeout
                typingTimer = setTimeout(function(){
                    console.log('searching for ...' + searchTerm);
                    searchDB(searchTerm);
                }, doneTypingInterval);

                // console.log(searchTerm);
            }
            // console.log(el);
        });

        function searchDb(searchTerm){

            let searchResult = document.getElementById('searchResult');
            if(searchTerm.length){
                searchResult.style.display = 'block';    
                $.ajax({
                type: 'GET',
                data: {search_term: searchTerm},
                url: 'database/live-search.php',
                success: function(response){
                    let searchResult = document.getElementById('searchResult');
                    if(response.length === 0){
                        //console.log('no data found');
                        searchResult.innerHTML = 'no data found';
                    } else {
                        // Loop
                        Let html = '';
                        for (const [tbl, tblRows] of Object.entries(response.data)) {
                            //console.log(`${key} ${value}`); // "a 5", "b 7", "c 9"
                            tblRows.forEach((row) => {
                                let text = '';
                                let url = '';
                                if(tbl === 'users'){
                                    text = row.first_name + ' ' + row.last_name;
                                    url = 'users-view.php';
                                }
                                if(tbl === 'suppliers'){
                                    text = row.supplier_name;
                                    url = 'supplier-view.php';
                                } 
                                if(tbl === 'products'){
                                    text = row.product_name;
                                    url = 'product-view.php';
                                }  

                                html += '<a href="'+ url +'">'+ text +'</a></br>';
                                //console.log(tblRows);
                            })
                            //console.log(html);
                            searchResult.innerHTML = html;
                        }
                    }
                    //console.log(data);
                },
                dataType: 'json'
                })
            } else {
                searchResult.style.display = 'none';
            }
        }
    </script>
</body>
</html>