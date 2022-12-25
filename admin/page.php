<?php
$do = isset($_GET['do']) ? $_GET['do'] : 'Manage';

if ($do == 'Manage') {
    echo 'Welcome in Manage page';
    echo '<a href="?do=Insert">Add new Category</a>';
} elseif ($do == 'Add') {
    echo 'Welcome in Add page';
} elseif ($do == 'Insert') {
    echo 'Welcome in Insert page';
} else {
    echo 'No such page';
}
