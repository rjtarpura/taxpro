<!-- this php file is include in navbar.php at bottom, no need to redclare variables here again
which are already declared there -->

<?php
// Get controller/method to active/inactive sidebar
$page_url	=	$this->router->fetch_class()."/".$this->router->fetch_method();
// $page_url	=	$this->uri->segment(1)."/".$this->uri->segment(2);
?>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <!-- <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $photo_path; ?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><php echo $user_profile['first_name']." ".$user_profile['last_name']; ?></p>
          
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div> -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu" data-widget="tree">
		
		<li class="<?php echo ($page_url == 'users/dashboard')?'active':''; ?>"><a href="<?php echo base_url('index.php/users/dashboard'); ?>"><i class="fa fa-dashboard"></i> <span> Dashboard</span></a></li>
		
        <li class="treeview <?php echo ($page_url == 'users/index' || $page_url == 'users/view_list')?'active':'';?>">
          <a href="#">
            <i class="fa fa-user"></i> <span>Users</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <?php if($user_role_sess==ADMIN): ?>
            <li class="<?php echo ($page_url == 'users/index')?'active':'';?>"><a href="<?php echo base_url('index.php/users'); ?>"><i class="fa fa-plus"></i> Add User</a></li>
          <?php endif; ?>
			<li class="<?php echo ($page_url == 'users/view_list')?'active':'';?>"><a href="<?php echo base_url('index.php/users/view_list'); ?>"><i class="fa fa-list"></i> Users List</a></li>
          </ul>
        </li>
		
		<!-- <li class="treeview <?php echo ($page_url == 'clients/index' || $page_url == 'clients/view_list')?'active':'';?>">
          <a href="#">
            <i class="fa fa-suitcase"></i> <span>Clients</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($page_url == 'clients/index')?'active':'';?>"><a href="<?php echo base_url('index.php/clients'); ?>"><i class="fa fa-plus"></i> Add Client</a></li>
			<li class="<?php echo ($page_url == 'clients/view_list')?'active':'';?>"><a href="<?php echo base_url('index.php/clients/view_list'); ?>"><i class="fa fa-list"></i> Client List</a></li>
          </ul>
        </li> -->
		
		<li class="treeview <?php echo ($page_url == 'products/index' || $page_url == 'products/view_list'  || $page_url == 'products/product_status_master')?'active':'';?>">
          <a href="#">
            <i class="fa fa-dropbox"></i> <span>Products</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($page_url == 'products/index')?'active':'';?>"><a href="<?php echo base_url('index.php/products'); ?>"><i class="fa fa-plus"></i> Add Product</a></li>
			<li class="<?php echo ($page_url == 'products/view_list')?'active':'';?>"><a href="<?php echo base_url('index.php/products/view_list'); ?>"><i class="fa fa-list"></i> Product List</a></li>
        <li class="<?php echo ($page_url == 'products/product_status_master')?'active':'';?>"><a href="<?php echo base_url('index.php/products/product_status_master'); ?>"><i class="fa fa-plus"></i> Status Master</a></li>
          </ul>
        </li>
		
		<li class="treeview <?php echo ($page_url == 'orders/index' || $page_url == 'orders/view_list' || $page_url == 'clients/index' || $page_url == 'clients/view_list')?'active':'';?>">
    <!-- <li class="treeview <?php echo ($page_url == 'orders/index' || $page_url == 'orders/my_orders' || $page_url == 'orders/view_list' || $page_url == 'orders/order_status_list')?'active':'';?>"> -->
          <a href="#">
            <i class="fa fa-pencil-square-o"></i> <span>Sales</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($page_url == 'orders/index')?'active':'';?>"><a href="<?php echo base_url('index.php/orders'); ?>"><i class="fa fa-plus"></i> Order</a></li>            
			     <li class="<?php echo ($page_url == 'clients/index')?'active':'';?>"><a href="<?php echo base_url('index.php/clients'); ?>"><i class="fa fa-plus"></i> Add Client</a></li>
           <li class="<?php echo ($page_url == 'orders/view_list')?'active':'';?>"><a href="<?php echo base_url('index.php/orders/view_list'); ?>"><i class="fa fa-list"></i> Orders List</a></li>
                      
          <li class="<?php echo ($page_url == 'clients/view_list')?'active':'';?>"><a href="<?php echo base_url('index.php/clients/view_list'); ?>"><i class="fa fa-list"></i> My Clients</a></li>

           <!-- <li class="<php echo ($page_url == 'orders/order_status_list')?'active':'';?>"><a href="<?php echo base_url('index.php/orders/order_status_list'); ?>"><i class="fa fa-plus"></i> Status List</a></li> -->           
          </ul>
        </li>
        <li class="treeview <?php echo ($page_url == 'orders/my_orders' || $page_url == 'orders/order_processing')?'active':'';?>">
          <a href="#">
            <i class="fa fa-pencil-square-o"></i> <span>Back Office</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">            
            <li class="<?php echo ($page_url == 'orders/order_processing')?'active':'';?>"><a href="<?php echo base_url('index.php/orders/order_processing'); ?>"><i class="fa fa-refresh text-aqua"></i> Order Processing</a></li>
            <li class="<?php echo ($page_url == 'orders/my_orders')?'active':'';?>"><a href="<?php echo base_url('index.php/orders/my_orders'); ?>"><i class="fa fa-star"></i> My Orders
              <!-- <span class="pull-right-container">
                <?php if($user_role_sess == USER && $pending_orders): ?>
                <small class="label pull-right bg-red"><?php echo $pending_orders; ?></small>
                <?php endif; ?>
              </span>               -->
            </a>

            </li>
          </ul>
        </li>
		
		    <li class="treeview <?php echo ($page_url == 'reports/index' || $page_url == 'reports/sales_summery' || $page_url == 'reports/sales_performance')?'active':'';?>">
          <a href="#">
            <i class="fa fa-line-chart"></i> <span>Reports</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="<?php echo ($page_url == 'reports/sales_performance')?'active':'';?>"><a href="<?php echo base_url('index.php/reports/sales_performance'); ?>"><i class="fa fa-check"></i> Sale Performance</a></li>
            <li class="<?php echo ($page_url == 'reports/sales_summery')?'active':'';?>"><a href="<?php echo base_url('index.php/reports/sales_summery'); ?>"><i class="fa fa-check text"></i> Sales Summry</a></li>            
          </ul>
        </li>
		
		    <?php if($user_role_sess==ADMIN): ?>
          <li class="<?php echo ($page_url == 'settings/index')?'active':''; ?>"><a href="<?php echo base_url('index.php/settings'); ?>"><i class="fa fa-gear"></i> <span> Settings</span></a></li>
        <?php endif; ?>
      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>