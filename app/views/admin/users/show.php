<?php 
    $this->include('header');
    $this->include('navbar');
?>

<div class="row justify-content-center">
    <table class="table w-75 mt-5">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Details</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($current as $value) { ?>
                <tr>
                    <th>
                        Id
                    </th>
                    <th>
                        <?php echo $value['id']; ?>
                    </th>
                </tr>
                <tr>
                    <th>
                        Username
                    </th>
                    <th>
                        <?php echo $value['username']; ?>
                    </th>
                </tr>
                <tr>
                    <th>
                        Email
                    </th>
                    <th>
                        <?php echo $value['email']; ?>
                    </th>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>


