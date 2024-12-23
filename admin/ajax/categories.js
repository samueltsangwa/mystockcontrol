
$(document).ready(function() {
    // Handle edit button click
    $(document).on('click', '.edit-category-btn', function() {
        var id = $(this).data('id');

        // Reset the modal form
        $('#editCategoriesModal').find('.modal-loading').hide();
        $('#editCategoriesModal').find('.edit-categories-result').show();
        $('#editCategoriesModal').find('#editCategoriesName').val('');
        $('#editCategoriesModal').find('#editCategoriesStatus').val('');
        $('#editCategoriesModal').find('#categoryId').val('');

        // Show the modal
        $('#editCategoriesModal').modal('show');

        $.ajax({
            url: 'categories.php',
            type: 'post',
            data: {id: id},
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editCategoriesModal').find('#editCategoriesName').val(response.data.categories_name);
                    $('#editCategoriesModal').find('#editCategoriesStatus').val(response.data.categories_status);
                    $('#editCategoriesModal').find('#categoryId').val(response.data.categories_id);
                }
            }
        });
    });

    // Handle delete button click
    $(document).on('click', '.delete-category-btn', function() {
        var id = $(this).data('id');
        if (confirm('Are you sure you want to delete this category?')) {
            $.ajax({
                url: 'categories.php',
                type: 'post',
                data: {action: 'delete', categoryId: id},
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        // Reload the page or remove the row from the table
                        location.reload();
                    } else {
                        alert(response.messages);
                    }
                }
            });
        }
    });
});
