<?php
echo !empty($errors['avatar']['required']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['avatar']['required'] . '</em>' : '';

echo !empty($errors['avatar']['allow_ext']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['avatar']['allow_ext'] . '</em>' : '';

echo !empty($errors['avatar']['max_size']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['avatar']['max_size'] . '</em>' : '';

echo !empty($errors['avatar']['file_error']) ? '<br><em class="error-notice" style="color:red;">&nbsp;' . $errors['avatar']['file_error'] . '</em>' : '';
?>