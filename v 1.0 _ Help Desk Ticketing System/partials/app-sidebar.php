<?php 
    $user = $_SESSION['user'];
?>


<div class="dashboard_sidebar" id="dashboard_sidebar">
    <h3 class="dashboard_logo" id="dashboard_logo"> H.D <h3>
    <div class="dashboard_sidebar_user">
        <img src="images/user.jpg" alt="User image." id="userImage">
        <span><?= $user['first_name'] . ' ' . $user['last_name'] ?></span>
    </div>
    <div class="dashboard_sidebar_menus">
        <ul class="dashboard_menu_lists">
            <!-- class="menuActive" -->
            <li class="liMainMenu">
                <a href="./dashboard.php"><i class="fa-solid fa-gauge"></i><span class="menuText"> Dashboard</span></a>
            </li>

            <li class="liMainMenu">
                <a href="./report.php"><i class="fa-solid fa-file"></i><span class="menuText"> Reports</span></a>
            </li>

            <li class="liMainMenu">
                <a href="javascript:void(0);" class="showHideSubMenu">
                    <i class="fa-brands fa-product-hunt"></i>
                    <span class="menuText showHideSubMenu"> Product Management</span>
                    <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                <ul class="subMenus">
                    <li><a class="subMainLink" href="./product-view.php"><i class="fa fa-circle-o"></i> View Product</a></li>
                    <li><a class="subMainLink" href="./product-add.php"><i class="fa fa-circle-o"></i> Add Product</a></li>
                    <li><a class="subMainLink" href="./product-order.php"><i class="fa fa-circle-o"></i> Order Product</a></li>
                </ul>
            </li>

            <li class="liMainMenu">
                <a href="javascript:void(0);" class="showHideSubMenu">
                    <i class="fa-solid fa-truck showHideSubMenu"></i>
                    <span class="menuText showHideSubMenu"> Supplier Management</span>
                    <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                <ul class="subMenus">
                    <li><a class="subMainLink" href="./supplier-view.php"><i class="fa fa-circle-o"></i> View Supplier</a></li>
                    <li><a class="subMainLink" href="./supplier-add.php"><i class="fa fa-circle-o"></i> Add Supplier</a></li>
                </ul>
            </li>

            <li class="liMainMenu">
                <a href="javascript:void(0);" class="showHideSubMenu">
                    <i class="fa-solid fa-shopping-cart showHideSubMenu"></i>
                    <span class="menuText showHideSubMenu"> Purchase Order</span>
                    <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                <ul class="subMenus">
                    <li><a class="subMainLink" href="./product-order.php"><i class="fa fa-circle-o"></i> Create Order</a></li>
                    <li><a class="subMainLink" href="./view-order.php"><i class="fa fa-circle-o"></i> View Orders</a></li>
                </ul>
            </li>

            <li class="liMainMenu showHideSubMenu">
                <a href="javascript:void(0);" class="showHideSubMenu">
                    <i class="fa-solid fa-user-plus showHideSubMenu"></i>
                    <span class="menuText showHideSubMenu"> User Management</span>
                    <i class="fa-solid fa-angle-left mainMenuIconArrow showHideSubMenu"></i>
                </a>
                <ul class="subMenus">
                    <li><a class="subMainLink" href="./users-view.php"><i class="fa fa-circle-o"></i> View Users</a></li>
                    <li><a class="subMainLink" href="./users-add.php"><i class="fa fa-circle-o"></i> Add Users</a></li>
                </ul>
            </li>
        </ul>
    </div>
</div>



