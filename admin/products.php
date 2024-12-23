<?php include 'includes/session.php'; ?>
<?php include 'includes/slugify.php'; ?>
<?php include 'includes/header.php'; ?>
<?php include 'includes/fetchSingleProduct.php'; ?>
<body class="hold-transition skin-blue sidebar-mini">
    <!-- Fixed Navbar -->
    <?php include 'includes/navbar.php'; ?>
    <?php include 'includes/menubar.php'; ?>
    <?php
    include 'includes/conn.php';
    // Handle product addition
    if (isset($_POST['product_name'])) {
        $brand = $_POST['brand'];
        $productName = $_POST['product_name'];
        $rate = $_POST['rate'];
        $quantity = $_POST['quantity'];
        $size = $_POST['size'];
        $category = $_POST['category'];
        $status = $_POST['status'];
        // Insert product data
        $sql = "INSERT INTO product (brand_id, product_name, rate, quantity, size, categories_id, status) 
                VALUES ('$brand', '$productName', '$rate', '$quantity', '$size','$category', '$status')";

        if ($conn->query($sql) === TRUE) {
            echo "<script>displaySuccessMessage('Product added successfully!');</script>";
        } else {
            echo "Error: " . $conn->error;
        }
    }
  // Handle Delete Product
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
    $productId = intval($_POST['product_id']);

    $stmt = $conn->prepare("DELETE FROM product WHERE product_id = ?");
    $stmt->bind_param("i", $productId);

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Product successfully deleted.";
        $deleted = true; // Flag indicating successful deletion
    } else {
        $_SESSION['error_message'] = "Error: " . $stmt->error;
        $deleted = false;
    }
    $stmt->close();

    // Return a response to be handled by AJAX
    echo json_encode(['success' => $deleted, 'message' => $deleted ? 'Product deleted successfully.' : 'Failed to delete product.']);
    exit();
}
?>
    <!-- Add padding to avoid overlap with navbar -->
    <div class="content-wrapper" style="padding-top: 30px;">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-12">
                    <ol class="breadcrumb">
                        <li><a href="home.php">Home</a></li>
                        <li class="active">Product</li>
                    </ol>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="page-heading"> <i class="glyphicon glyphicon-edit"></i> Manage Product</div>
                        </div> <!-- /panel-heading -->
                        <div class="panel-body justify-content-center">
                            <div class="remove-messages"></div>
                            <div class="div-action pull pull-right" style="padding-bottom:20px;">
                                <button class="btn btn-default button1" data-toggle="modal" id="addProductModalBtn" data-target="#addProductModal">
                                    <i class="glyphicon glyphicon-plus-sign"></i> Add Product
                                </button>
                            </div> <!-- /div-action -->
                            <table class="table" id="manageProductTable">
                                <thead>
                                    <tr>
                                        <th>Brand Name</th>
                                        <th>Product Name</th>
                                        <th>Quantity Per Product</th>
                                        <th>Size(in ML/L)</th>
                                        <th>Rate in Ksh.</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th style="width:15%;">Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch product data from the database
                                    $sql = "SELECT product.product_id, product.product_name, product.rate, product.quantity, product.size, 
                                    categories.categories_name, brands.brand_name, product.status
                                    FROM product
                                    LEFT JOIN categories ON product.categories_id = categories.categories_id
                                    LEFT JOIN brands ON product.brand_id = brands.brand_id";
                                    $result = $conn->query($sql);

                                    if ($result->num_rows > 0) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<tr>";
                                            echo "<td>" . $row['brand_name'] . "</td>";
                                            echo "<td>" . $row['product_name'] . "</td>";
                                            echo "<td>" . $row['quantity'] . "</td>";
                                            echo "<td>" . $row['size'] . "</td>";
                                            echo "<td>" . $row['rate'] . "</td>";
                                            echo "<td>" . $row['categories_name'] . "</td>";
                                            echo "<td>" . ($row['status'] == 1 ? 'Available' : 'Unavailable') . "</td>";
                                            echo "<td>
                                                <button class='btn btn-info btn-sm edit-product' data-id='" . $row['product_id'] . "'><i class='glyphicon glyphicon-pencil'></i> Edit</button>
                                                <button class='btn btn-danger btn-sm delete-product' data-id='" . $row['product_id'] . "'><i class='glyphicon glyphicon-trash'></i> Delete</button>
                                            </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center'>No or Low products found, please add new products</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                            <!-- /table -->
                        </div> <!-- /panel-body -->
                    </div> <!-- /panel -->
                </div> <!-- /col-md-12 -->
            </div> <!-- /row -->

            <!-- Add Product Modal -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="addProductForm" action="products.php" method="POST" enctype="multipart/form-data">

                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Add New Product</h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <!-- Brands -->
                    <div class="form-group">
                        <label for="newCategory">Brand:</label>
                        <select class="form-control" id="newBrand" name="brand" required>
                            <?php
                            // Fetch brands from the database to populate the dropdown
                            $catSql = "SELECT brand_id, brand_name FROM brands";
                            $catResult = $conn->query($catSql);

                            if ($catResult->num_rows > 0) {
                                while ($catRow = $catResult->fetch_assoc()) {
                                    echo "<option value='" . $catRow['brand_id'] . "'>" . $catRow['brand_name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No brand is available</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="newProductName">Product Name:</label>
                        <input type="text" class="form-control" id="newProductName" name="product_name" required>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="newQuantity">Quantity:</label>
                        <input type="number" class="form-control" id="newQuantity" name="quantity" required>
                    </div>

                    <!-- Size -->
                    <div class="form-group">
                        <label for="newSize">Size in ML/L:</label>
                        <input type="text" class="form-control" id="newSize" name="size" required>
                    </div>

                    <!-- Rate -->
                    <div class="form-group">
                        <label for="newRate">Rate in Ksh.:</label>
                        <input type="number" class="form-control" id="newRate" name="rate" required>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="newCategory">Category:</label>
                        <select class="form-control" id="newCategory" name="category" required>
                            <?php
                            // Fetch categories from the database to populate the dropdown
                            $catSql = "SELECT categories_id, categories_name FROM categories";
                            $catResult = $conn->query($catSql);

                            if ($catResult->num_rows > 0) {
                                while ($catRow = $catResult->fetch_assoc()) {
                                    echo "<option value='" . $catRow['categories_id'] . "'>" . $catRow['categories_name'] . "</option>";
                                }
                            } else {
                                echo "<option value=''>No categories available</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="newStatus">Status:</label>
                        <select class="form-control" id="newStatus" name="status" required>
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Add Product</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <!-- /modal-dialog -->
</div>

            <!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editProductForm" action="includes/updateProduct.php" method="POST" enctype="multipart/form-data">
                
                <!-- Modal Header -->
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Edit Product</h4>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    <input type="hidden" id="product_id" name="product_id">

                    <!-- Brand -->
                    <div class="form-group">
                        <label for="brand">Brand:</label>
                        <input type="text" class="form-control" id="brand" name="brand" readonly>
                    </div>

                    <!-- Product Name -->
                    <div class="form-group">
                        <label for="productName">Product Name:</label>
                        <input type="text" class="form-control" id="productName" name="product_name" required>
                    </div>

                    <!-- Rate -->
                    <div class="form-group">
                        <label for="rate">Rate:</label>
                        <input type="number" class="form-control" id="rate" name="rate" required>
                    </div>

                    <!-- Quantity -->
                    <div class="form-group">
                        <label for="quantity">Quantity:</label>
                        <input type="number" class="form-control" id="quantity" name="quantity" required>
                    </div>

                    <!-- Size -->
                    <div class="form-group">
                        <label for="size">Size (in ML/L):</label>
                        <input type="text" class="form-control" id="size" name="size" required>
                    </div>

                    <!-- Category -->
                    <div class="form-group">
                        <label for="category">Category:</label>
                        <input type="text" class="form-control" id="category" name="category" readonly>
                    </div>

                    <!-- Status -->
                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="1">Available</option>
                            <option value="0">Unavailable</option>
                        </select>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
 <!-- /editProductModal -->
  <!-- Delete User Modal -->
<div class="modal fade" id="deleteProductModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="products.php">
                <div class="modal-header">
                    <h4>Delete Product</h4>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" id="delete_product_id" name="product_id">
                    <p>Are you sure you want to delete this product?</p>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            </form>
        </div>
    </div>
</div>

        </div> 
        <!-- /container-fluid -->
    </div> <!-- /content-wrapper -->
    <script>
    $(document).ready(function() {
        // Edit button click event
        $(document).on('click', '.edit-product', function() {
            var productId = $(this).data('id');

            // AJAX request to fetch product details
            $.ajax({
                url: 'includes/fetchSingleProduct.php', // PHP script to fetch product details
                type: 'POST',
                data: { product_id: productId },
                dataType: 'json',
                success: function(response) {
                    // Check if response is valid
                    if(response) {
                        // Populate the edit form fields
                        $('#product_id').val(response.product_id);
                        $('#productName').val(response.product_name);
                        $('#rate').val(response.rate);
                        $('#quantity').val(response.quantity);
                        $('#size').val(response.size);
                        $('#status').val(response.status);

                        // Show the edit modal
                        $('#editProductModal').modal('show');
                    } else {
                        alert("Error: Product details could not be fetched.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error:", error);
                }
            });
        });
    });

    fetch('updateProduct.php', {
    method: 'POST',
    body: new FormData(document.getElementById('updateProductForm'))
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert(data.message);
        window.location.href = data.redirect; // Redirect to product.php
    } else {
        alert(data.message);
    }
})
.catch(error => console.error('Error:', error));

$('#editProductForm').on('submit', function (e) {
    e.preventDefault(); // Prevent default form submission

    var formData = new FormData(this); // Collect form data

    // AJAX request to update product
    $.ajax({
        url: 'includes/updateProduct.php', // PHP script for updating product
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function () {
            // Redirect to products.php directly after success
            window.location.href = 'http://localhost/mystockcontrol/admin/products.php';
        },
        error: function (xhr, status, error) {
            console.error('AJAX Error:', error);
            alert('An error occurred while updating the product.');
        }
    });
});

</script>
<script>
   $(document).ready(function() {
            // Check if a product was deleted
            <?php if (isset($_SESSION['deleted']) && $_SESSION['deleted'] === true): ?>
                // If the product was successfully deleted, hide the row
                $(".delete-product").each(function() {
                    var productId = $(this).data('id');
                    $(this).closest('tr').fadeOut(); // Hide the row of the deleted product
                });
                <?php unset($_SESSION['deleted']); // Unset after using it ?>
            <?php endif; ?>

            // Trigger Delete Modal
            $('.delete-product').click(function () {
                const productId = $(this).data('id');
                $('#delete_product_id').val(productId);
                $('#deleteProductModal').modal('show');
            });

            // Handle form submission for product deletion
            $('#deleteProductModal form').submit(function(e) {
                e.preventDefault(); // Prevent form submission

                // Perform AJAX request to delete the product
                $.ajax({
                    url: 'products.php', // Same page to handle deletion
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        // Hide the product row after successful deletion
                        var productId = $('#delete_product_id').val();
                        $('tr').filter(function() {
                            return $(this).find('.delete-product').data('id') == productId;
                        }).fadeOut(); // Hide the row after deletion
                        $('#deleteProductModal').modal('hide');
                    },
                    error: function() {
                        alert("Error: Product deletion failed.");
                    }
                });
            });
        });
</script>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
