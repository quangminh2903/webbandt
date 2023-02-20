<?php
    echo !empty($errors['phone']['required']) ? '<em class="error">' . $errors['phone']['required'] . '</em>' : '';

    echo !empty($errors['phone']['not_type']) ? '<em class="error">' . $errors['phone']['not_type'] . '</em>' : '';

    echo !empty($errors['phone']['not_valid']) ? '<em class="error">' . $errors['phone']['not_valid'] . '</em>' : '';

    echo !empty($errors['phone']['exist']) ? '<em class="error">' . $errors['phone']['exist'] . '</em>' : '';