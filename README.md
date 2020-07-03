---
**1\.** Клонируем репозиторий

`git clone git@github.com:WebCodim/luckyonline.git`

---
**2\.** Запускаем docker

`cd luckyonline`

`docker-compose up -d`

---
**3\.** Запускаем миграций для создания таблиц

`docker-compose exec luckyonline_php php yii migrate`

---
**4\.** Запускаем команду seeder для заполнения таблиц

`docker-compose exec luckyonline_php php yii seeder`

---
Проверяем: 

`http://localhost/`

База данных mariadb:
```
хост: localhost

user: root

password: root

database: lucky_online
```
