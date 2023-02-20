<?php
echo !empty($errors['password']['required']) ? '&ensp;<em style="color:red;">' . $errors['password']['required'] . '</em>' : '';

echo !empty($errors['password']['min_length']) ? '&ensp;<em style="color:red;">' . $errors['password']['min_length'] . '</em>' : '';

echo !empty($errors['password']['not_match']) ? '&ensp;<em style="color:red;">' . $errors['password']['not_match'] . '</em>' : '';
