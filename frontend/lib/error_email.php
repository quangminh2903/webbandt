<?php
    echo !empty($errors['email']['required']) ? '<em class="error" style="color:red;">' . $errors['email']['required'] . '</em>' : '';

    echo !empty($errors['email']['not_valid']) ? '<em class="error" style="color:red;">' . $errors['email']['not_valid'] . '</em>' : '';

    echo !empty($errors['email']['not_exist']) ? '<em class="error" style="color:red;">' . $errors['email']['not_exist'] . '</em>' : '';

    echo !empty($errors['email']['inactive']) ? '<em class="error" style="color:red;">' . $errors['email']['inactive'] . '</em>' : '';

    echo !empty($errors['email']['delete']) ? '<em class="error" style="color:red;">' . $errors['email']['delete'] . '</em>' : '';

    echo !empty($errors['email']['ban']) ? '<em class="error" style="color:red;">' . $errors['email']['ban'] . '</em>' : '';