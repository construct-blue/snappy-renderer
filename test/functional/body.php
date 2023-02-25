<?php

declare(strict_types=1);

return fn($model) => yield <<<HTML
<body>
<h1>My App</h1>
<p>$model->greeting</p>
</body>
HTML;