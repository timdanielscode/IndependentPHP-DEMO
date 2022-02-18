<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>
<?php use parts\Alert; ?>

<?php 
    $this->include('header');
    $this->include('navbar');
?>
<div class="containter">

    <?php if(parts\Session::exists("updated")) { ?>
        <div class="my-3"><?php echo parts\Alert::display("success", "updated"); ?></div>
    <?php parts\Session::delete('updated');  } ?>

    <?php if(Session::exists("registered")) { ?>
        <div class="my-5 w-75 mx-auto"><?php echo Alert::display("warning", "registered"); ?></div>
    <?php Session::delete('registered'); } ?>

    <a href="/users/create">Add user</a>
    <div class="row justify-content-center">
    
        <table class="table w-75 mt-5">
        
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Show</th>
                    <th scope="col">Edit</th>
                    <th scope="col">Delete</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($all as $user) { ?>
                    <tr>
                        <th>
                            <?php echo $user['id']; ?>
                        </th>
                        <th>
                            <?php echo $user['username']; ?>
                        </th>
                        <th>
                            <?php echo $user['email']; ?>
                        </th>
                        <th>
                            <a href="/users/<?php echo $user["id"]; ?>">Read</a>
                        </th>
                        <th>
                            <a href="/users/<?php echo $user["id"]; ?>/edit">Edit</a>
                        </th>   
                        <th>
                            <a href="/users/<?php echo $user["id"]; ?>/delete" onclick="return confirm('Are you sure you want to delete this?');">Delete</a>
                        </th>
                    </tr>
                <?php } ?>
                
            </tbody>
        </table>
    </div>
</div>

<?php 
    $this->include('footer');
?>