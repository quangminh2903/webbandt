<?php
echo !empty($errors['current_password']['required']) ? '&ensp;<em style="color:red;">' . $errors['current_password']['required'] . '</em>' : '';

echo !empty($errors['current_password']['wrong']) ? '&ensp;<em style="color:red;">' . $errors['current_password']['wrong'] . '</em>' : '';
