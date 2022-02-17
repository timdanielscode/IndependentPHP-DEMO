<?php use parts\validation\Errors; ?>
<?php use core\Csrf; ?>
<?php use parts\Session; ?>

<?php 
    $this->include('header');
    $this->include('navbar');
?>
<div class="container">


<h1 class="my-5"><?php echo $user['username']; ?></h1>
 
    <form action="" method="POST" class="d-block m-auto">
        <div class="form-group">
            <label for="username">Username:</label>
            <input name="username" type="text" id="username" class="form-control <?php Errors::addValidClass($rules, 'username'); ?>" value="<?php echo $user["username"]; ?>">
            <div class="invalid-feedback">
                <?php echo Errors::get($rules, 'username'); ?>
            </div>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input name="email" type="email" id="email" class="form-control <?php Errors::addValidClass($rules, 'email'); ?>" value="<?php echo $user["email"]; ?>">
            <div class="invalid-feedback">
                <?php echo Errors::get($rules, 'email'); ?>
            </div>
        </div>
        <div class="form-group">
            <input type="hidden" name="id" value="<?php echo $user["id"]; ?>"> 
            <button name="submit" type="submit" class="mt-3 btn btn-primary">Update</button>
            <input type="hidden" name="token" value="<?php echo CSRF::token('get');?>" />
        </div>
</form>

</div>

<?php 
    $this->include('footer');
?>
