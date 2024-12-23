<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar">
   
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu" data-widget="tree">
      <li class=""><a href="home.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a></li>
      <li class="header">MANAGE</li>
      <li class=""><a href="brands.php"><i class="fa fa-btc"></i> <span>Brand</span></a></li>
      <li class=""><a href="categories.php"><i class="fa fa-list"></i> <span>Category</span></a></li>
      <li class=""><a href="products.php"><i class="fa fa-ruble"></i> <span>Products</span></a></li>
      <li class="dropdown" id="navOrder">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-shopping-cart"></i> Orders <span class="caret"></span>
        </a>
        <ul class="dropdown-menu">
          <li id="topNavAddOrder"><a href="orders.php?o=add"><i class="fa fa-plus"></i> Add Orders</a></li>
          <li id="topNavManageOrder"><a href="orders.php?o=manord"><i class="fa fa-edit"></i> Manage Orders</a></li>
        </ul>
      </li>
          <li id="topNavManageOrder"><a href="users.php?o=manord"><i class="fa fa-users"></i> Manage Users</a></li>
      <li class="header">REPORTS</li>
      <li class=""><a href="reports.php"><i class="fa fa-archive"></i> <span>Generate Report</span></a></li> 
      <li class="header">Help</li>
	    <li class=""><a href="Manual.pdf"><i class="fa fa-file-pdf-o"></i> <span>Manual</span></a></li>
    </ul>
  </section>
  <!-- /.sidebar -->
</aside>
<?php include 'config_modal.php'; ?>