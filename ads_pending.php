<?php
// Include the database connection
include('../includes/connect.php');

// Handle product confirmation and update in the products table
if (isset($_GET['confirm_product_id'])) {
    $product_id = $_GET['confirm_product_id'];

    // Start a transaction to ensure data consistency
    $con->begin_transaction();

    try {
        // Step 1: Update the status to 1 in the orders_pending table
        $update_status_query = "UPDATE orders_pending SET status = 1 WHERE product_id = ?";
        $stmt_status = $con->prepare($update_status_query);

        if (!$stmt_status) {
            die("Prepare failed for status update: " . $con->error);
        }

        $stmt_status->bind_param("i", $product_id);
        $stmt_status->execute();

        // Step 2: Get product details from orders_pending
        $get_product_details = "
            SELECT product_id, title, product_image1, product_email, product_contact, date, status 
            FROM orders_pending WHERE product_id = ?";
        $stmt_details = $con->prepare($get_product_details);

        if (!$stmt_details) {
            die("Prepare failed for fetching product details: " . $con->error);
        }

        $stmt_details->bind_param("i", $product_id);
        $stmt_details->execute();
        $result_details = $stmt_details->get_result();

        if ($result_details->num_rows > 0) {
            $product = $result_details->fetch_assoc();

            // Step 3: Insert or update product in the products table
            $insert_product_query = "
                INSERT INTO products (product_id, product_title, product_description, product_keywords, 
                                      category_id, brand_id, product_image1, product_image2, product_image3, 
                                      product_email, product_contact, product_price, district, date, status) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE 
                    product_title = VALUES(product_title),
                    product_description = VALUES(product_description),
                    product_keywords = VALUES(product_keywords),
                    category_id = VALUES(category_id),
                    brand_id = VALUES(brand_id),
                    product_image1 = VALUES(product_image1),
                    product_image2 = VALUES(product_image2),
                    product_image3 = VALUES(product_image3),
                    product_email = VALUES(product_email),
                    product_contact = VALUES(product_contact),
                    product_price = VALUES(product_price),
                    district = VALUES(district),
                    date = VALUES(date),
                    status = VALUES(status)";

            $stmt_insert_product = $con->prepare($insert_product_query);

            if (!$stmt_insert_product) {
                die("Prepare failed for product insert: " . $con->error);
            }

            // Assigning placeholders/defaults for missing columns
            $product_description = "Description not available";
            $product_keywords = "No keywords";
            $category_id = 0; // Assuming 0 as default category
            $brand_id = 0;    // Assuming 0 as default brand
            $product_image2 = ''; // No second image
            $product_image3 = ''; // No third image
            $product_price = 0.0; // Default price
            $district = "Unknown"; // Default district

            $stmt_insert_product->bind_param(
                "isssiiissssdssi",
                $product['product_id'],
                $product['title'],
                $product_description,
                $product_keywords,
                $category_id,
                $brand_id,
                $product['product_image1'],
                $product_image2,
                $product_image3,
                $product['product_email'],
                $product['product_contact'],
                $product_price,
                $district,
                $product['date'],
                $product['status']
            );

            $stmt_insert_product->execute();

            echo "<script>alert('Product confirmed and updated successfully.'); 
                  window.location='ads_pending.php';</script>";
        } else {
            echo "<script>alert('No product found with this ID.'); 
                  window.location='ads_pending.php';</script>";
        }

        // Commit the transaction
        $con->commit();

        // Close statements
        $stmt_status->close();
        $stmt_details->close();
        $stmt_insert_product->close();

    } catch (Exception $e) {
        // Rollback the transaction on error
        $con->rollback();
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}

// Fetch all pending orders (for displaying in the table)
$get_products = "SELECT product_id, title, product_image1, product_email, product_contact, date, set_date, status FROM orders_pending";
$result = mysqli_query($con, $get_products);

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
        $set_date = $row['set_date'];
        $status = $row['status'] == 0 ? 'Pending' : 'Confirmed';

        $current_time = new DateTime();
        $set_date_time = new DateTime($set_date);

        if ($set_date_time < $current_time) {
            $active_time = 'Expired';
        } else {
            $interval = $current_time->diff($set_date_time);
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
            <td><?php echo $set_date; ?></td>
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

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
