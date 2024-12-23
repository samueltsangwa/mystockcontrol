<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
 <?php include 'includes/conn.php'; ?>
 <?php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
 error_reporting(E_ALL);

$valid = array('success' => false, 'messages' => array());

// Insert new brand
if (isset($_POST['action']) && $_POST['action'] === 'insert') {
    $brandName = $conn->real_escape_string(trim($_POST['brandName']));
    $brandStatus = (int)$_POST['brandStatus'];
    $sql = "INSERT INTO brands (brand_name, brand_active, brand_status) VALUES ('$brandName', '$brandStatus', 1)";
    if ($conn->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Brand successfully added";
    } else {
        $valid['messages'] = "Error: " . $conn->error;
    }
    echo json_encode($valid);
    exit();
}

// Update brand


if (isset($_POST['action']) && $_POST['action'] === 'update') {
	$brandName = isset($_POST['editBrandName']) ? trim($_POST['editBrandName']) : null;
    $brandStatus = isset($_POST['editBrandStatus']) ? (int)$_POST['editBrandStatus'] : null;

    $brandId = (int)$_POST['brandId'];
    // $brandName = $conn->real_escape_string(trim($_POST['brandName']));
    // $brandStatus = (int)$_POST['brandStatus'];
    $sql = "UPDATE brands SET brand_name='$brandName', brand_status='$brandStatus' WHERE brand_id=$brandId";
    if ($conn->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Brand successfully updated";
    } else {
        $valid['messages'] = "Error: " . $conn->error;
    }
    echo json_encode($valid);
    exit();
}

// Delete brand
if (isset($_POST['action']) && $_POST['action'] === 'delete') {
    $brandId = (int)$_POST['brandId'];
    $sql = "DELETE FROM brands WHERE brand_id=$brandId";
    if ($conn->query($sql) === TRUE) {
        $valid['success'] = true;
        $valid['messages'] = "Brand successfully deleted";
    } else {
        $valid['messages'] = "Error: " . $conn->error;
    }
    echo json_encode($valid);
    exit();
}
// Fetch a single brand for editing
if (isset($_GET['brandId'])) {
    $brandId = (int)$_GET['brandId'];
    $sql = "SELECT * FROM brands WHERE brand_id = $brandId";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        echo json_encode($result->fetch_assoc());
    } else {
        echo json_encode(['error' => 'Brand not found']);
    }
    exit();
}


// Retrieve all brands for displaying in the table
$brandData = [];
$result = $conn->query("SELECT * FROM brands");
while ($row = $result->fetch_assoc()) {
    $brandData[] = $row;
}
$conn->close();
?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    <!-- Fixed Navbar -->
    <?php include 'includes/navbar.php'; ?>
    
    <!-- Fixed Sidebar Menubar -->
    <?php include 'includes/menubar.php'; ?>

    <!-- Main content area -->
    <div class="content-wrapper" style="padding-top: 60px;"> <!-- Add padding to avoid overlap with navbar -->
      <div class="container-fluid">
        <div class="row justify-content-center">
          <div class="col-md-12"> <!-- Centered column -->
			<ol class="breadcrumb">
				<li><a href="home.php">Home</a></li>		  
				<li class="active">Brand</li>
			</ol>
            <div class="panel panel-default">
              <div class="panel-heading">
                <i class="glyphicon glyphicon-edit"></i> Manage Brands
              </div>
              <div class="panel-body justify-content-center">
			  	<div class="div-action pull pull-right" style="padding-bottom:20px;">
					<button class="btn btn-default button1" data-toggle="modal" data-target="#addBrandModel"> <i class="glyphicon glyphicon-plus-sign"></i> Add Brand </button>
				</div> 
				<table class="table" id="manageBrandTable">
					<thead>
						<tr>                            
							<th>Brand Name</th>
							<th>Status</th>
							<th style="width:15%;">Options</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($brandData as $brand) { ?>
							<tr>
								<td><?php echo $brand['brand_name']; ?></td>
								<td><?php echo $brand['brand_status'] == 1 ? 'Available' : 'Not Available'; ?></td>
								<td>
									<button class="btn btn-warning btn-sm edit-brand-btn" data-id="<?php echo $brand['brand_id']; ?>">Edit</button>
									<button class="btn btn-danger btn-sm delete-brand-btn" data-id="<?php echo $brand['brand_id']; ?>">Delete</button>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
              </div>
            </div>
          </div>
        </div>
		<div class="modal fade" id="addBrandModel" tabindex="-1" role="dialog">
 		 <div class="modal-dialog">
          <div class="modal-content">
    	<form class="form-horizontal" id="submitBrandForm" action="brands.php" method="POST">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-plus"></i> Add Brand</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="add-brand-messages bg-success"></div>
	        <div class="form-group">
	        	<label for="brandName" class="col-sm-3 control-label">Brand Name: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <input type="text" class="form-control" id="brandName" placeholder="Brand Name" name="brandName" autocomplete="off">
				    </div>
	        </div> <!-- /form-group-->	         	        
	        <div class="form-group">
	        	<label for="brandStatus" class="col-sm-3 control-label">Status: </label>
	        	<label class="col-sm-1 control-label">: </label>
				    <div class="col-sm-8">
				      <select class="form-control" id="brandStatus" name="brandStatus">
				      	<option value="">~~SELECT~~</option>
				      	<option value="1">Available</option>
				      	<option value="2">Not Available</option>
				      </select>
				    </div>
	        </div> <!-- /form-group-->	         	        
	      </div> <!-- /modal-body -->  
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="submit" class="btn btn-primary" id="createBrandBtn" data-loading-text="Loading..." autocomplete="off">Save Changes</button>
	      </div>
     	</form>
    </div>
  </div>
</div>
<div class="modal fade" id="editBrandModel" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    	<form class="form-horizontal" id="editBrandForm" action="brands.php" method="POST">
		<input type="hidden" id="editBrandId" name="brandId">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Brand</h4>
	      </div>
	      <div class="modal-body">
	      	<div id="edit-brand-messages"></div>
	      	<div class="modal-loading div-hide" style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
						<i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
						<span class="sr-only">Loading...</span>
					</div>
		      <div class="edit-brand-result">
		      	<div class="form-group">
		        	<label for="editBrandName" class="col-sm-3 control-label">Brand Name: </label>
		        	<label class="col-sm-1 control-label">: </label>
					    <div class="col-sm-8">
					      <input type="text" class="form-control" id="editBrandName" placeholder="Brand Name" name="editBrandName" autocomplete="off">
					    </div>
		        </div> <!-- /form-group-->	         	        
		        <div class="form-group">
		        	<label for="editBrandStatus" class="col-sm-3 control-label">Status: </label>
		        	<label class="col-sm-1 control-label">: </label>
					    <div class="col-sm-8">
					      <select class="form-control" id="editBrandStatus" name="editBrandStatus">
					      	<option value="">~~SELECT~~</option>
					      	<option value="1">Available</option>
					      	<option value="2">Not Available</option>
					      </select>
					    </div>
		        </div> <!-- /form-group-->	
		      </div>         	        
	      </div> <!-- /modal-body -->
	      
	      <div class="modal-footer editBrandFooter">
	        <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
	        
	        <button type="submit" class="btn btn-success" id="editBrandBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
	      </div>
	      <!-- /modal-footer -->
     	</form>
    </div>
  </div>
</div>
<!-- /edit brand -->
 <!-- remove brand -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeMemberModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Remove Brand</h4>
      </div>
      <div class="modal-body">
        <p>Do you really want to remove ?</p>
      </div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
<!-- /remove brand -->
      </div>
    </div>
  </div>
</body>

<script src="ajax/brand.js"></script>
<?php require_once 'includes/footer.php'; ?>
</html>
