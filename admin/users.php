<?php include('../config.php'); ?>
<?php include(ROOT_PATH . '/admin/includes/admin_functions.php'); ?>
<?php 
    // get all admin users from the database
    $admins = getAdminUsers();
    $roles = ['Admin', 'Author'];
?>

<?php include(ROOT_PATH . '/admin/includes/head_section.php'); ?>
    <title>Admin | Manage users</title>
</head>
<body>

    <!--admmin navbar-->
    <?php include(ROOT_PATH . '/admin/includes/navbar.php'); ?>

    <div class="container content">
        <!--include the left side mednu for admin navigation-->
        <?php include(ROOT_PATH  . '/admin/includes/menu.php') ?>

        <!-- section for creating or editing admin users -->
         <div class="action">
            <h1 class="page-title">Create/Edit Admin users</h1>

            <!-- form to create or edit an admin user -->
            <form method="post" action="<?php echo BASE_URL . 'admin/users.php'; ?>" >

                <!-- include file to display validation error, if any -->
                 <?php include(ROOT_PATH . '/includes/errors.php') ?>

                 <!-- hidden input to store user ID when editing an existing user  -->
                  <?php if ($isEditingUser === true): ?>
                    <input type="hidden" name="admin_id" value="<?php echo $admin_id; ?>">
                <?php endif ?>

                <!-- input field for username  -->
                 <input type="text" name="username" value="<?php echo $username; ?>" placeholder="Username">

                 <!-- input filed for email  -->
                  <input type="emai" name="email" value="<?php echo $email; ?>" placeholder="Email">

                  <!-- input field for password -->
                   <input type="password" name="password" placeholder="Password">

                   <!-- input field for confirming the password  -->
                    <input type="password" name="passwordConfirmation" placeholder="Password confirmation">

                    <!-- dropdown to assign a role to a user  -->
                     <select name="role">
                        <option value="" selected disabled>Assign Role</option>
                        <?php foreach ($roles as $key => $role): ?>
                            <option value="<?php echo $role; ?>"><?php echo $role; ?></option> 
                        <?php endforeach ?>
                    </select>

                    <!-- button to update an existing admin user or create a new user  -->
                     <?php if ($isEditingUser === true): ?>
                        <button type="submit" class="btn" name="update_admin">UPDATE</button>
                    <?php else: ?>
                        <button type="submit" class="btn" name="create_admin">Save User</button>
                    <?php endif ?>
            </form>
        </div>

        <!-- section to display existing admin users from the database  -->
        <div class="table-div">
            <!-- include file to display notification messages -->
             <?php include(ROOT_PATH . '/includes/messages.php') ?>

             <!-- check if there are any admin user; if not, display a message  -->
              <?php if (empty($admins)): ?>
                <h1>No admins in the database.</h1>
                <?php else: ?>
                    <!-- display a table of admin users -->
                     <table class="table">
                        <thead>
                            <th>N</th>
                            <th>Admin</th>
                            <th>Role</th>
                            <th colspan="2">Action</th>
                        </thead>
                        <tbody>
                            <?php foreach ($admins as $key => $admin): ?>
                                <tr>
                                    <!-- display the serial number of the admin  -->
                                     <td><?php echo $key + 1; ?></td>

                                     <!-- display the username and email of the admin -->
                                      <td>
                                        <?php echo $admin['username']; ?>, $nbsp;
                                        <?php echo $admin['email']; ?>
                                     </td>

                                     <!-- display the role of the admin -->
                                      <td><?php echo $admin['role']; ?></td>

                                      <!-- edit button to edit an existing admin user  -->
                                       <td>
                                            <a class="fa fa-pencil btn edit"
                                                href="users.php?edit-admin=<?php echo $admin['id'] ?>">
                                            </a>
                                        </td>

                                        <!-- delete button to delete an admin user -->
                                        <td>
                                            <a class="fa fa-trash btn delete"
                                                href="users.php?delete-admin=<?php echo $admin['id'] ?>">
                                            </a>
                                        </td>
                                </tr>
                            <?php endforeach ?>
                            </tbody>
                        </table>
                    <?php endif ?>
                </div>
                <!-- display records from database -->
            </div>
</body>
</html>