Для корректной работы необходимы:

- PHP версии >= 8.0
- MySQL версии >= 8.0
- Root директория /public_html
- Настройки базы данных /src/config/main.php
- Структура БД /database/structure.sql

Писал быстро, так что не будьте строги. Пожалуйста! )

Соединение с БД осуществляется через интерфейс \App\Data\AccessPointInterface, всего два метода.
Модели поделены непосредственно на модель (\App\Models\AppModel) и на хранилище (\App\Models\AppStorage)
В остальном как обычно в MVC

Из подводных камней:
- Удаление. Если удалять полностью, вероятно будут проблемы. Но я сделал частичное, с флагом
- Дерево с бесконечной вложенностью сделано с помощью рекурсивного запроса, наверное уж будет не очень хорошо работать если вложенность очень большая

Кажется других проблем не было.