<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
<?php include 'includes/conn.php'; ?>

<?php

$valid['success'] = array('success' => false, 'messages' => array());

if ($_POST) {
    // Handle create category
    if (!empty($_POST['categoriesName'])) {
        $categoriesName = $_POST['categoriesName'];
        $categoriesStatus = $_POST['categoriesStatus'];

        $sql = "INSERT INTO categories (categories_name, categories_active, categories_status) VALUES ('$categoriesName', 1, '$categoriesStatus')";

        if ($conn->query($sql) === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Successfully Added";
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Error while adding the category: " . $conn->error;
        }
    }
    if (isset($_POST['action']) && $_POST['action'] === 'getCategory') {
        $categoryId = (int)$_POST['categoryId'];
        $sql = "SELECT * FROM categories WHERE categories_id=$categoryId";
        $result = $conn->query($sql);
    
        if ($result->num_rows > 0) {
            $categoryData = $result->fetch_assoc();
            $valid['success'] = true;
            $valid['data'] = $categoryData;
        } else {
            $valid['success'] = false;
            $valid['messages'] = "Category not found";
        }
        echo json_encode($valid);
        exit();
    }
    

    // Handle update category
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $categoryId = (int)$_POST['categoryId']; // Get category ID
        $categoryName = isset($_POST['editCategoriesName']) ? trim($_POST['editCategoriesName']) : null;
        $categoryStatus = isset($_POST['editCategoriesStatus']) ? (int)$_POST['editCategoriesStatus'] : null;

        $sql = "UPDATE categories SET categories_name='$categoryName', categories_status='$categoryStatus' WHERE categories_id=$categoryId";
        if ($conn->query($sql) === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Category successfully updated";
        } else {
            $valid['messages'] = "Error: " . $conn->error;
        }
        echo json_encode($valid);
        exit();
    }

    // Handle delete category
    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $categoryId = (int)$_POST['categoryId'];
        $sql = "DELETE FROM categories WHERE categories_id=$categoryId";
        if ($conn->query($sql) === TRUE) {
            $valid['success'] = true;
            $valid['messages'] = "Category successfully deleted";
        } else {
            $valid['messages'] = "Error: " . $conn->error;
        }
        echo json_encode($valid);
        exit();
    }
}

// Fetch categories for the table
$categoryData = [];
$result = $conn->query("SELECT * FROM categories");
while ($row = $result->fetch_assoc()) {
    $categoryData[] = $row;
}
$conn->close();

?>

<?php include 'includes/header.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>

    <div class="content-wrapper" style="padding-top: 30px;">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="home.php">Home</a></li>
                        <li class="active">Category</li>
                    </ol>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Categories</div>
                        </div>
                        <div class="panel-body">

                            <div class="remove-messages"></div>

                            <div class="div-action pull pull-right" style="padding-bottom:20px;">
                                <button class="btn btn-default button1" data-toggle="modal" id="addCategoriesModalBtn" data-target="#addCategoriesModal"> <i class="glyphicon glyphicon-plus-sign"></i> Add Categories </button>
                            </div>
                            
                            <table class="table" id="manageCategoriesTable">
                                <thead>
                                    <tr>
                                        <th>Categories Name</th>
                                        <th>Status</th>
                                        <th style="width:15%;">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($categoryData as $category) { ?>
                                        <tr>
                                            <td><?php echo $category['categories_name']; ?></td>
                                            <td><?php echo $category['categories_status'] == 1 ? 'Available' : 'Not Available'; ?></td>
                                            <td>
                                                <button class="btn btn-warning btn-sm edit-category-btn" data-id="<?php echo $category['categories_id']; ?>">Edit</button>
                                                <button class="btn btn-danger btn-sm delete-category-btn" data-id="<?php echo $category['categories_id']; ?>">Delete</button>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>

            <!-- Add Categories Modal -->
            <div class="modal fade" id="addCategoriesModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-horizontal" id="submitCategoriesForm" action="categories.php" method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fa fa-plus"></i> Add Categories</h4>
                            </div>
                            <div class="modal-body">
                                <div id="add-categories-messages"></div>
                                <div class="form-group">
                                    <label for="categoriesName" class="col-sm-4 control-label">Categories Name: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-7">
                                        <input type="text" class="form-control" id="categoriesName" name="categoriesName" placeholder="Categories Name" autocomplete="off">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="categoriesStatus" class="col-sm-4 control-label">Status: </label>
                                    <label class="col-sm-1 control-label">: </label>
                                    <div class="col-sm-7">
                                        <select class="form-control" id="categoriesStatus" name="categoriesStatus">
                                            <option value="">~~SELECT~~</option>
                                            <option value="1">Available</option>
                                            <option value="2">Not Available</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                                <button type="submit" class="btn btn-primary" id="createCategoriesBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Edit Categories Modal -->
            <div class="modal fade" id="editCategoriesModal" tabindex="-1" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form class="form-horizontal" id="editCategoriesForm" action="categories.php" method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title"><i class="fa fa-edit"></i> Edit Category</h4>
                            </div>
                            <div class="modal-body">
                                <div id="edit-categories-messages"></div>
                                <div class="modal-loading div-hide" style="width:50px; margin:auto;padding-top:50px; padding-bottom:50px;">
                                    <i class="fa fa-spinner fa-pulse fa-3x fa-fw"></i>
                                    <span class="sr-only">Loading...</span>
                                </div>
                                <div class="edit-categories-result">
                                    <div class="form-group">
                                        <label for="editCategoriesName" class="col-sm-4 control-label">Categories Name: </label>
                                        <label class="col-sm-1 control-label">: </label>
                                        <div class="col-sm-7">
                                            <input type="text" class="form-control" id="editCategoriesName" name="editCategoriesName" placeholder="Categories Name" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="editCategoriesStatus" class="col-sm-4 control-label">Status: </label>
                                        <label class="col-sm-1 control-label">: </label>
                                        <div class="col-sm-7">
                                            <select class="form-control" id="editCategoriesStatus" name="editCategoriesStatus">
                                                <option value="">~~SELECT~~</option>
                                                <option value="1">Available</option>
                                                <option value="2">Not Available</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input type="hidden" id="categoryId" name="categoryId" value="">
                                <button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Close</button>
                                <button type="submit" class="btn btn-primary" id="updateCategoriesBtn" data-loading-text="Loading..." autocomplete="off"> <i class="glyphicon glyphicon-ok-sign"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
</body>
<script>
$(document).ready(function() {
    // Edit button click handler
    $('.edit-category-btn').click(function() {
        var categoryId = $(this).data('id');
        $('#editCategoriesModal').modal('show');

        $.ajax({
            url: 'categories.php',
            type: 'POST',
            data: { action: 'getCategory', categoryId: categoryId },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editCategoriesName').val(response.data.categories_name);
                    $('#editCategoriesStatus').val(response.data.categories_status);
                    $('#categoryId').val(categoryId); // Set hidden categoryId in the form
                } else {
                    alert(response.messages);
                }
            }
        });
    });

    // Update button click handler (inside edit modal)
    $('#editCategoriesForm').submit(function(e) {
        e.preventDefault();
        $.ajax({
            url: 'categories.php',
            type: 'POST',
            data: $(this).serialize() + '&action=update',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.messages);
                    location.reload();
                } else {
                    alert(response.messages);
                }
            }
        });
    });

    // Delete button click handler
    $('.delete-category-btn').click(function() {
        var categoryId = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: 'categories.php',
                type: 'POST',
                data: { action: 'delete', categoryId: categoryId },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.messages);
                        location.reload();
                    } else {
                        alert(response.messages);
                    }
                }
            });
        }
    });
});
</script>
<?php require_once 'includes/footer.php'; ?>
</html>

