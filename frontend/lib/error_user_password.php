<?php
echo !empty($errors['password']['required']) ? '<br><em class="error-notice" style="coloe:red;">&nbsp;' . $errors['password']['required'] . '</em>' : '';

echo !empty($errors['password']['min_length']) ? '<br><em class="error-notice" style="coloe:red;">&nbsp;' . $errors['password']['min_length'] . '</em>' : '';

echo !empty($errors['password']['not_match']) ? '<br><em class="error-notice" style="coloe:red;">&nbsp;' . $errors['password']['not_match'] . '</em>' : '';
