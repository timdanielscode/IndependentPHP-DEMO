<?php 
    $this->include('header');
    $this->include('navbar');
?>
<div class="containter">
    <?php if(parts\Session::exists("updated")) { ?>
        <div class="my-3"><?php echo parts\Alert::display("success", "updated"); ?></div>
    <?php parts\Session::delete('updated');  } ?>
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
