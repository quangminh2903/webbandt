<?php
    echo !empty($errors['name']['required']) ? '&ensp;<em style="color: red;">' . $errors['name']['required'] . '</em>' : '';
