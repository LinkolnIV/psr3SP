#PSR-3

Простая реализаци стандарта PSR-3 с дополнительными возможнастями конфигурации

### Пример использования 
```php

<?php

require_once '../vendor/autoload.php';

use App\Logger;

$log = new Logger();

$log->info("info mess");
$log->error("error mess");

```

### Файл Конфигурации
```yaml
# полный путь к каталогу логов
file_path: /var/log/ 

# описание соответствия файлов логов к конкретным уровням сообщений 
log_level_files:
  - EMERGENCY: emergency.log
  - ALERT: alert.log
  - CRITICAL: critical.log
  - ERROR: error.log
  - WARNING: warning.log
  - NOTICE: notice.log
  - INFO: info.log
  - DEBUG: debug.log

```
