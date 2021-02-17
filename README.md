# Тестовый проект для Life Logical


Проект работает на сервере dionmagnus.ru

Доступны урлы:
 - для логина http://dionmagnus.ru/login
 - для работы с каталогом: http://dionmagnus.ru/catalog
 - для работы с пользователями: http://dionmagnus.ru/users

Запросы GET на каталог http://dionmagnus.ru/catalog и POST на создание пользователей http://dionmagnus.ru/users проходят без авторизации. 

Приметы запросов
 - для получения списка продуктов: *curl 'dionmagnus.ru/catalog?page=2&sort=price'*
 - для логина: *curl -v -XPOST -H "Content-Type: application/json" -d @auth.json dionmagnus.ru/login* После логина нужно запомнить id сесии и передавать в запросах на урлы с авторизацией.
 - для создания нового продукта: *curl -XPOST -H "Cookie: PHPSESSID=ifrhdc5b14q45qf882sn31c8iu" -d @product.json dionmagnus.ru/catalog*
 - для редактирования существующего продукта по его id: *curl -XPUT -H "Cookie: PHPSESSID=ifrhdc5b14q45qf882sn31c8iu" -d @product_update.json dionmagnus.ru/catalog/54* id продукта можно посмотреть в ответе при его создании
 - для удаления продукта: *curl -XDELETE -H "Cookie: PHPSESSID=ifrhdc5b14q45qf882sn31c8iu" dionmagnus.ru/catalog/54*
 - для просмотра списка пользователей: *curl -H "Cookie: PHPSESSID=ifrhdc5b14q45qf882sn31c8iu" dionmagnus.ru/users*
 - для регистрации нового пользователя: *curl -XPOST -H "Content-Type: application/json" -d @user.json dionmagnus.ru/users*
 - для удаления пользователя: *curl -XDELETE -H "Cookie: PHPSESSID=ifrhdc5b14q45qf882sn31c8iu" dionmagnus.ru/users/53*
 
 
 Пользователи регистрируются с правами ROLE_USER. Это им по сути ничего не дает.
 Пользователь с email *i3nc4br0hh20h|@abaj9qzxb.org* имеет права администратора. У всех существующих в базе пользователей пароль 12345
 
 Все упомянутые в запросах файлы лежат в папке examples  
  
