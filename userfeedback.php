<h3 class="text-center text-success">User Feedbacks</h3>

<table class="table table-bordered mt-5">
    <thead>

    <?php
    
    $get_feedback="SELECT * FROM feedback_details";
    $result=mysqli_query($con,$get_feedback);
    $row_count=mysqli_num_rows($result);
    echo "<tr>
    <th class='bg-info'>U No</th>
    <th class='bg-info'>User Name</th>
    <th class='bg-info'>Email Address</th>
    <th class='bg-info'>Home Town</th>
    <th class='bg-info'>Feedback</th>
    <th class='bg-info'>Date</th>
    <th class='bg-info'>Delete</th>
</tr>

</thead>
<tbody>";

    // if($row_count==0){
    //     echo "<h2 class='text-danger text-center mt-5'>No Feedback yet</h2>";
    // }else{
    //     $number=0;

    //     while($row_data=mysqli_fetch_assoc($result)){
    //         $id=$row_data['id'];
    //         $name=$row_data['name'];
    //         $email=$row_data['email'];
    //         $town=$row_data['town'];
    //         $msg=$row_data['msg'];
    //         $date=$row_data['date'];
    //         $number++;

    //         echo "<tr>
    //         <td class='bg-secondary text-light'>$number</td>
    //         <td class='bg-secondary text-light'>$name</td>
    //         <td class='bg-secondary text-light'>$email</td>
    //         <td class='bg-secondary text-light'>$town</td>
    //         <td class='bg-secondary text-light'>$msg</td>
    //         <td class='bg-secondary text-light'>$date</td>
    //         <td class='bg-secondary text-light'><a href='' class='text-light'><i class='fa-solid fa-trash'></i></a></td>
    //     </tr>";
    //     }
    // }

    

        // Initialize the counter
        $number = 0;

        if ($con) {
            $select_brnd = "SELECT * FROM feedback_details";
            $result = mysqli_query($con, $select_brnd);

            if ($result) {
                while ($row = mysqli_fetch_assoc($result)) {
                    $id = $row['id'];
                    $name = $row['name'];
                    $email = $row['email'];
                    $town = $row['town'];
                    $msg = $row['msg'];
                    $date = $row['date'];
                    $number++;
        ?>
                    <tr class="text-center">
                        <td class="bg-secondary text-light"><?php echo $number; ?></td>
                        <td class="bg-secondary text-light"><?php echo $name; ?></td>
                        <td class="bg-secondary text-light"><?php echo $email; ?></td>
                        <td class="bg-secondary text-light"><?php echo $town; ?></td>
                        <td class="bg-secondary text-light"><?php echo $msg; ?></td>
                        <td class="bg-secondary text-light"><?php echo $date; ?></td>
                        <td class="bg-secondary text-light">
                            <a 
                                href="delete_feedback.php?delete_feedback=<?php echo $id; ?>" 
                                onclick="return confirm('Are you sure you want to delete this user?');" 
                                class="btn btn-primary text-light"
                            >
                                <i class="fa-solid fa-trash bg-danger"></i>
                            </a>
                        </td>
                    </tr>
        <?php
                }
            } else {
                echo "Error: " . mysqli_error($con);
            }

            mysqli_close($con);
        } else {
            echo "Failed to connect to the database.";
        }
        ?>
    
        
    </tbody>
</table>
