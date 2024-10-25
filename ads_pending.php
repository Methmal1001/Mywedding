<?php
// Include the database connection file
include('../includes/connect.php');

// Handle the form submission for updating set date
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $set_date = $_POST['set_date'];

    // Update the set_date in the database
    $update_query = "UPDATE orders_pending SET set_date = ? WHERE product_id = ?";
    $stmt = $con->prepare($update_query);
    $stmt->bind_param("si", $set_date, $product_id); // "si" means string and integer types

    if ($stmt->execute()) {
        echo "<script>alert('Set date updated successfully.'); window.location='ads_pending.php';</script>";
    } else {
        echo "<script>alert('Error updating set date: " . $stmt->error . "'); window.location='ads_pending.php';</script>";
    }

    $stmt->close();
}

// Automatically delete expired products and their related data
$current_time = new DateTime();
$current_time_formatted = $current_time->format('Y-m-d H:i:s');

// Start transaction
$con->begin_transaction();

try {
    // First delete from orders_pending
    $delete_orders_query = "DELETE FROM orders_pending WHERE set_date < ?";
    $stmt_delete_orders = $con->prepare($delete_orders_query);
    $stmt_delete_orders->bind_param("s", $current_time_formatted);
    $stmt_delete_orders->execute();

    // Then delete from products based on product_id of deleted orders
    $delete_products_query = "DELETE FROM products WHERE product_id IN (SELECT product_id FROM orders_pending WHERE set_date < ?)";
    $stmt_delete_products = $con->prepare($delete_products_query);
    $stmt_delete_products->bind_param("s", $current_time_formatted);
    $stmt_delete_products->execute();

    // Commit transaction
    $con->commit();
    
    // Close statements
    $stmt_delete_orders->close();
    $stmt_delete_products->close();
} catch (Exception $e) {
    // Rollback transaction in case of error
    $con->rollback();
    echo "<script>alert('Error deleting expired products: " . $e->getMessage() . "');</script>";
}

// Fetch all pending orders
$get_products = "SELECT product_id, title, product_image1, product_email, product_contact, date, set_date, status FROM `orders_pending`";
$result = mysqli_query($con, $get_products);

// Check if the query was successful
if (!$result) {
    die("Query Failed: " . mysqli_error($con));
}
?>

<h3 class="text-center text-success">Ads Pending</h3>

<table class="table table-bordered mt-5">
    <thead class="bg-primary text-light">
        <tr>
            <th>Product ID</th>
            <th>Product Title</th>
            <th>Product Image</th>
            <th>Contact</th>
            <th>Email</th>
            <th>Date</th>
            <th>Set Date</th>
            <th>Active</th>
            <th>Status</th>
            <th>Confirmation</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody class="bg-light text-dark">
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        $product_id = $row['product_id'];
        $title = $row['title'];
        $product_image1 = $row['product_image1'];
        $product_email = $row['product_email'];
        $product_contact = $row['product_contact'];
        $date = $row['date'];
        $set_date = $row['set_date']; // Retrieve the manual set date
        $status = $row['status'] == 0 ? 'Pending' : 'Confirmed';

        // Active time calculation
        $current_time = new DateTime(); // Current date and time
        $set_date_time = new DateTime($set_date); // Set date from the database

        // Check if the set date has passed
        if ($set_date_time < $current_time) {
            $active_time = 'Expired';
        } else {
            // Calculate the remaining time
            $interval = $current_time->diff($set_date_time);

            // Format the remaining time to display
            $active_time = sprintf('%d days, %d hours, %d minutes left', 
                                    $interval->d, 
                                    $interval->h, 
                                    $interval->i);
        }
    ?>
        <tr class='text-center <?php echo ($row['status'] == 0) ? 'table-warning' : 'table-success'; ?>'>
            <td><?php echo $product_id; ?></td>
            <td><?php echo $title; ?></td>
            <td>
                <img src='../admin_area/product_images/<?php echo $product_image1; ?>' 
                     class='product_img' 
                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 5px; border: 1px solid #ddd;">
            </td>
            <td><?php echo $product_contact; ?></td>
            <td><?php echo $product_email; ?></td>
            <td><?php echo $date; ?></td>
            <td>
                <form action="" method="POST" class="form-inline">
                    <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                    <input type="datetime-local" name="set_date" value="<?php echo $set_date; ?>" class="form-control mr-2" required>
                    <button type="submit" class="btn btn-primary btn-sm">Update</button>
                </form>
            </td>
            <td><?php echo $active_time; ?></td>
            <td><?php echo $status; ?></td>
            <td>
                <?php if ($row['status'] == 0) { ?>
                    <a href='ads_pending.php?confirm_product_id=<?php echo $product_id; ?>' class='btn btn-success'>
                        OK
                    </a>
                <?php } else { ?>
                    <button class='btn btn-secondary' disabled>Confirmed</button>
                <?php } ?>
            </td>
            <td>
                <button type="button" class="btn btn-danger text-light" 
                        data-toggle="modal" 
                        data-target="#deleteModal<?php echo $product_id; ?>">
                    <i class='fa-solid fa-trash'></i>
                </button>
            </td>
        </tr>

        <!-- Delete Confirmation Modal -->
        <div class="modal fade" id="deleteModal<?php echo $product_id; ?>" 
             tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this product?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        <a href='ads_pending.php?delete_product=<?php echo $product_id; ?>' class="btn btn-danger">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
    </tbody>
</table>

<!-- Include Bootstrap JS and jQuery for modals -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
