
$(document).ready(function() {
    // Insert new brand
    $('#submitBrandForm').on('submit', function(event) {
        event.preventDefault();
        $.ajax({
            url: 'brands.php',
            type: 'POST',
            data: $(this).serialize() + '&action=insert',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#addBrandModel').modal('hide');
                    $('#submitBrandForm')[0].reset();
                    loadBrandData();
                }
                $('#add-brand-messages').html('<div class="alert alert-' + (response.success ? 'success' : 'danger') + '">' + response.messages + '</div>');
            }
        });
    });

    // Load brand data function
    function loadBrandData() {
        $.ajax({
            url: 'brands.php',
            type: 'GET',
            dataType: 'html',
            success: function(data) {
                $('#manageBrandTable tbody').html($(data).find('#manageBrandTable tbody').html());
            }
        });
    }

    // Edit brand
    $(document).ready(function() {
        // When edit button is clicked
        $(document).on('click', '.edit-brand-btn', function() {
            const brandId = $(this).data('id');
    
            // Show modal
            $('#editBrandModel').modal('show');
            
            // Clear messages and disable save button initially
            $('#edit-brand-messages').html('');
            $('#editBrandBtn').prop('disabled', true);
    
            // Load brand data into modal form fields
            $.ajax({
                url: 'brands.php', // Change to the actual endpoint for fetching a single brand record
                type: 'GET',
                data: { brandId: brandId },
                dataType: 'json',
                success: function(response) {
                    if (response) {
                        // Populate form fields with current brand data
                        $('#editBrandName').val(response.brand_name);
                        $('#editBrandStatus').val(response.brand_status);
                        $('#editBrandId').val(brandId); // Set hidden input to track which brand to update
    
                        // Enable the save button now that data is loaded
                        $('#editBrandBtn').prop('disabled', false);
                    }
                },
                error: function(xhr, status, error) {
                    // Handle errors here
                    console.error("Error loading brand data:", error);
                    $('#edit-brand-messages').html('<div class="alert alert-danger">Could not load brand data.</div>');
                }
            });
        });
    
        // Save changes on submit
        $('#editBrandForm').on('submit', function(event) {
            event.preventDefault();
    
            $.ajax({
                url: 'brands.php',
                type: 'POST',
                data: $(this).serialize() + '&action=update',
                dataType: 'json',
                beforeSend: function() {
                    $('#editBrandBtn').button('loading');
                },
                success: function(response) {
                    $('#editBrandBtn').button('reset');
                    
                    if (response.success) {
                        // Reload the brands table (assuming a function to refresh table data)
                        loadBrandData();
                        $('#editBrandModel').modal('hide'); // Close modal
                        alert(response.messages); // Notify user
                    } else {
                        $('#edit-brand-messages').html('<div class="alert alert-danger">' + response.messages + '</div>');
                    }
                },
                error: function(xhr, status, error) {
                    $('#editBrandBtn').button('reset');
                    console.error("Error updating brand:", xhr.responseText); // Logs any HTML or error message from PHP
                    $('#edit-brand-messages').html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                }
                
            });
        });
    });
    

    // Delete brand
    $(document).on('click', '.delete-brand-btn', function() {
        const brandId = $(this).data('id');
        if (confirm("Do you really want to delete this brand?")) {
            $.ajax({
                url: 'brands.php',
                type: 'POST',
                data: { brandId: brandId, action: 'delete' },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        loadBrandData();
                    }
                    alert(response.messages);
                }
            });
        }
    });

    // Initial load
    loadBrandData();
});
