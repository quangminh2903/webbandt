<?php
echo !empty($errors['username']['required']) ? '&ensp;<em style="color:#F30">' . $errors['username']['required'] . '</em>' : '';
echo !empty($errors['username']['not_exist']) ? '&ensp;<em style="color:#F30">' . $errors['username']['not_exist'] . '</em>' : '';
echo !empty($errors['username']['inactive']) ? '&ensp;<em style="color:#F30">' . $errors['username']['inactive'] . '</em>' : '';
echo !empty($errors['username']['delete']) ? '&ensp;<em style="color:#F30">' . $errors['username']['delete'] . '</em>' : '';