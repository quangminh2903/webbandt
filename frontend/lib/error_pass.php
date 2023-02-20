<?php
    echo !empty($errors['password']['required']) ? '<em class="error" style="color:red;">' . $errors['password']['required'] . '</em>' : '';

    echo !empty($errors['password']['min_length']) ? '<em class="error" style="color:red;">' . $errors['password']['min_length'] . '</em>' : '';

    echo !empty($errors['wrong']) ? '<em class="error" style="color:red;">' . $errors['wrong'] . '</em>' : '';