<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header');
    $this->include('navbar');
?>
<div class="container">

    <?php if(parts\Session::exists("updated")) { ?>
        <div class="my-3"><?php echo parts\Alert::display("success", "updated"); ?></div>
    <?php parts\Session::delete('updated');  } ?>

    <?php if(Session::exists("registered")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "registered"); ?></div>
    <?php Session::delete('registered'); } ?>

    <a class="btn bg-color-sec text-white" href="/admin/users/create">Add user</a>
    
    <table class="table table-striped mt-5">
        
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Show</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($all as $user) { ?>
                    <tr>
                        <td>
                            <?php echo $user['id']; ?>
                        </td>
                        <td>
                            <?php echo $user['username']; ?>
                        </td>
                        <td>
                            <?php echo $user['email']; ?>
                        </td>
                        <td>
                            <?php echo $user['name']; ?>
                        </td>
                        <td>
                            <a class="btn bg-color-sec text-white" href="/admin/users/<?php echo $user["id"]; ?>/username/<?php echo $user["username"]; ?>">Read</a>
                        </td>
                        <td>
                            <a class="btn bg-color-sec text-white" href="/admin/users/<?php echo $user["id"]; ?>/username/<?php echo $user["username"]; ?>/edit">Edit</a>
                        </td>   
                        <td>
                            <a class="btn bg-danger text-white" href="/admin/users/<?php echo $user["id"]; ?>/username/<?php echo $user["username"]; ?>/delete" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
</div>

<?php 
    $this->include('footer');
?>